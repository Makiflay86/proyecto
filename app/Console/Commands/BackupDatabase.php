<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Comando artisan para generar backups completos de la base de datos.
 *
 * Uso:
 *   ./vendor/bin/sail artisan backup:database   → dentro de Sail
 *   php artisan backup:database                 → fuera de Sail (dev local)
 *
 * Guarda el archivo en storage/backups/backup_YYYYMMDD_HHMMSS.sql
 * La carpeta se crea automáticamente si no existe.
 */
class BackupDatabase extends Command
{
    protected $signature   = 'backup:database';
    protected $description = 'Genera un backup completo de la BD en storage/backups/';

    public function handle(): int
    {
        // Leemos las credenciales desde config/database.php que a su vez lee el .env
        // Así el comando funciona en cualquier entorno sin hardcodear nada
        $connection = config('database.default');
        $database   = config("database.connections.{$connection}.database");
        $username   = config("database.connections.{$connection}.username");
        $password   = config("database.connections.{$connection}.password");
        $host       = config("database.connections.{$connection}.host");
        $port       = config("database.connections.{$connection}.port", 3306);

        // Validación básica: si faltan credenciales, abortamos con mensaje claro
        if (empty($database) || empty($username) || empty($host)) {
            $this->error('Faltan credenciales de BD en el .env (DB_DATABASE, DB_USERNAME, DB_HOST).');
            return self::FAILURE;
        }

        // Crear el directorio storage/backups/ si no existe
        $backupsDir = storage_path('backups');
        if (!File::exists($backupsDir)) {
            File::makeDirectory($backupsDir, 0755, true);
            $this->line("  Carpeta creada: storage/backups/");
        }

        // Nombre del archivo con fecha y hora: backup_20260310_143022.sql
        $filename   = 'backup_' . now()->format('Ymd_His') . '.sql';
        $outputPath = $backupsDir . DIRECTORY_SEPARATOR . $filename;

        $this->info("Generando backup de «{$database}»...");

        // Construimos el array de argumentos para mysqldump.
        // Usamos array en lugar de string para evitar inyección de comandos.
        //
        // --single-transaction: snapshot consistente sin bloquear tablas (InnoDB)
        // --routines:           incluye procedimientos almacenados y funciones
        // --triggers:           incluye los triggers de las tablas
        // --no-tablespaces:     evita el error de permiso "PROCESS privilege" en MySQL 8+
        $command = [
            'mysqldump',
            "--host={$host}",
            "--port={$port}",
            "--user={$username}",
            '--single-transaction',
            '--routines',
            '--triggers',
            '--no-tablespaces',
            $database,
        ];

        // Pasamos la contraseña como variable de entorno MYSQL_PWD en lugar de --password=
        // para evitar el warning "Using a password on the command line interface can be insecure"
        // y para manejar correctamente contraseñas con caracteres especiales.
        $env = array_filter(['MYSQL_PWD' => (string) $password]);

        $process = new Process($command, null, $env ?: null);
        $process->setTimeout(300); // 5 minutos máximo para bases de datos grandes

        try {
            $process->mustRun();

            // Escribimos el output de mysqldump directamente al archivo
            File::put($outputPath, $process->getOutput());

            $sizeKb = round(File::size($outputPath) / 1024, 2);

            $this->info("  Backup completado correctamente.");
            $this->line("  Archivo : storage/backups/{$filename}");
            $this->line("  Tamaño  : {$sizeKb} KB");

            $this->limpiarBackupsAntiguos($backupsDir);

            return self::SUCCESS;

        } catch (ProcessFailedException $e) {
            // Si mysqldump falla, mostramos el error exacto que devuelve
            $this->error('mysqldump ha fallado:');
            $this->error($process->getErrorOutput());

            // Borramos el archivo vacío o parcial si se llegó a crear
            if (File::exists($outputPath)) {
                File::delete($outputPath);
            }

            return self::FAILURE;
        }
    }

    /**
     * Elimina los backups más antiguos dejando solo los últimos 5.
     * Ordena por fecha de modificación (el nombre incluye timestamp, así que
     * orden alfabético = orden cronológico) y borra los sobrantes.
     */
    private function limpiarBackupsAntiguos(string $backupsDir, int $maxBackups = 5): void
    {
        $archivos = collect(File::files($backupsDir))
            ->filter(fn ($f) => str_ends_with($f->getFilename(), '.sql'))
            ->sortBy(fn ($f) => $f->getFilename()) // alfabético = cronológico por el formato YYYYMMDD_HHMMSS
            ->values();

        $sobrantes = $archivos->count() - $maxBackups;

        if ($sobrantes <= 0) {
            return;
        }

        // Cogemos los más antiguos (los primeros tras ordenar) y los borramos
        $archivos->take($sobrantes)->each(function ($archivo) {
            File::delete($archivo->getPathname());
            $this->line("  Eliminado backup antiguo: " . $archivo->getFilename());
        });
    }
}
