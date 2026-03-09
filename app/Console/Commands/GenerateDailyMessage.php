<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyMessage;

class GenerateDailyMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-daily-message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = now()->toDateString();
        
        // Verificar si ya existe mensaje hoy
        if (DailyMessage::whereDate('date', $today)->exists()) {
            return;
        }
        
        // Array de mensajes
        $messages = [
            'Hoy es un buen día para revisar tus productos 📊',
            'Consejo: Agrupa productos relacionados 💡',
            '¿Sabías que las descripciones detalladas venden más? ✨',
            'Motiva a tus clientes con ofertas especiales 🎉',
            'Cada venta cuenta, ¡sigue adelante! 🚀',
            'Revisa tus estadísticas de hoy y aprende 📈',
            'Los clientes felices generan más ventas 😊',
            'Actualiza tus precios si es necesario 💰',
            'Crea promociones para productos con menos ventas 🎁',
            'El servicio al cliente es tu mejor herramienta 🛠️',
            'Analiza qué productos venden mejor 🔍',
            'Invierte en marketing digital 📱',
            'Las fotos de calidad venden más 📸',
            'Responde rápido a los clientes ⚡',
            'Ofrece envíos gratis en compras grandes 🚚',
            'Crea un programa de lealtad para clientes 🎖️',
            'Pide comentarios a tus clientes 💬',
            'Optimiza tu inventario hoy 📦',
            'Los detalles pequeños hacen la diferencia ✨',
            'Crea bundle de productos relacionados 🎯',
            'La confianza es la clave del éxito 🔐',
            'Prueba nuevas categorías de productos 🆕',
            'Mantén tu tienda organizada y limpia 🏪',
            'Ofrece múltiples métodos de pago 💳',
            'El tiempo es dinero, optimiza procesos ⏰',
            'Sé transparente con tus clientes 👁️',
            'Colabora con otros vendedores 🤝',
            'Usa redes sociales para promocionarte 📲',
            'Crea contenido de calidad para tu tienda 📝',
            'Los clientes buscan soluciones, no solo productos 💡',
            'Mantente actualizado con tendencias de mercado 📚',
            'La paciencia y persistencia pagan 💪',
            'Invierte en tu crecimiento hoy 📈',
            'Tu marca es tu identidad, cuídala 🎨',
            'Escucha a tu comunidad y adapta 👂',
            'Los datos son tu mejor aliado 📊',
            'Celebra cada logro, por pequeño que sea 🎉',
            'La educación continua es clave 🎓',
            'Automatiza lo que puedas para ganar tiempo ⚙️',
            'Tu energía positiva atrae clientes 🌟',
            'Mejora un proceso cada día 🔧',
            'La consistencia vence a la velocidad 🏃',
            'Tus competidores también están creciendo, muévete rápido ⚡',
            'Crea una experiencia memorable para tus clientes 🎭',
            'Los pequeños detalles generan grandes diferencias 🎁',
            'Sé auténtico en tu propuesta de valor 🎪',
            'Mide, analiza y mejora constantemente 📉',
            'Tu comunidad es tu activo más valioso 👥',
            'Hoy es el mejor día para empezar algo nuevo 🌅',
            'El éxito no es un destino, es un viaje 🛤️',
            '¡Tú puedes lograrlo! 💯',
        ];
        
        $message = $messages[array_rand($messages)];
        
        DailyMessage::create([
            'message' => $message,
            'date' => $today
        ]);
    }
}
