<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Message;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Usuario administrador personal
        User::firstOrCreate(
            ['email' => 'francisco@gmail.com'],
            ['name' => 'Francisco', 'password' => bcrypt('1234567890#'), 'is_admin' => true]
        );

        // Todos los usuarios de demo — contraseña: 1234567890#
        $users = [
            User::firstOrCreate(['email' => 'carlos@demo.com'],  ['name' => 'Carlos Martínez', 'password' => bcrypt('1234567890#')]),
            User::firstOrCreate(['email' => 'laura@demo.com'],   ['name' => 'Laura Sánchez',   'password' => bcrypt('1234567890#')]),
            User::firstOrCreate(['email' => 'miguel@demo.com'],  ['name' => 'Miguel Torres',   'password' => bcrypt('1234567890#')]),
            User::firstOrCreate(['email' => 'ana@demo.com'],     ['name' => 'Ana Gómez',       'password' => bcrypt('1234567890#')]),
            User::firstOrCreate(['email' => 'sergio@demo.com'],  ['name' => 'Sergio Ruiz',     'password' => bcrypt('1234567890#')]),
        ];

        // ═══════════════════════════════════════════════════════════════
        // NIVEL 1 — Categorías raíz
        // ═══════════════════════════════════════════════════════════════
        $electronica  = Category::firstOrCreate(['name' => 'Electrónica',   'parent_id' => null]);
        $moda         = Category::firstOrCreate(['name' => 'Moda',          'parent_id' => null]);
        $hogar        = Category::firstOrCreate(['name' => 'Hogar',         'parent_id' => null]);
        $deportes     = Category::firstOrCreate(['name' => 'Deportes',      'parent_id' => null]);
        $vehiculos    = Category::firstOrCreate(['name' => 'Vehículos',     'parent_id' => null]);
        $libros       = Category::firstOrCreate(['name' => 'Libros y Ocio', 'parent_id' => null]);

        // ═══════════════════════════════════════════════════════════════
        // NIVEL 2 — Subcategorías
        // ═══════════════════════════════════════════════════════════════
        $moviles      = Category::firstOrCreate(['name' => 'Móviles',          'parent_id' => $electronica->id]);
        $portatiles   = Category::firstOrCreate(['name' => 'Portátiles',       'parent_id' => $electronica->id]);
        $televisores  = Category::firstOrCreate(['name' => 'Televisores',      'parent_id' => $electronica->id]);
        $auriculares  = Category::firstOrCreate(['name' => 'Auriculares',      'parent_id' => $electronica->id]);

        $ropaHombre   = Category::firstOrCreate(['name' => 'Ropa Hombre',      'parent_id' => $moda->id]);
        $ropaMujer    = Category::firstOrCreate(['name' => 'Ropa Mujer',       'parent_id' => $moda->id]);
        $zapatillas   = Category::firstOrCreate(['name' => 'Zapatillas',       'parent_id' => $moda->id]);
        $complementos = Category::firstOrCreate(['name' => 'Complementos',     'parent_id' => $moda->id]);

        $muebles      = Category::firstOrCreate(['name' => 'Muebles',          'parent_id' => $hogar->id]);
        $electrodom   = Category::firstOrCreate(['name' => 'Electrodomésticos','parent_id' => $hogar->id]);
        $decoracion   = Category::firstOrCreate(['name' => 'Decoración',       'parent_id' => $hogar->id]);

        $ciclismo     = Category::firstOrCreate(['name' => 'Ciclismo',         'parent_id' => $deportes->id]);
        $running      = Category::firstOrCreate(['name' => 'Running',          'parent_id' => $deportes->id]);
        $fitness      = Category::firstOrCreate(['name' => 'Fitness',          'parent_id' => $deportes->id]);

        $coches       = Category::firstOrCreate(['name' => 'Coches',           'parent_id' => $vehiculos->id]);
        $motos        = Category::firstOrCreate(['name' => 'Motos',            'parent_id' => $vehiculos->id]);
        $bicicletas   = Category::firstOrCreate(['name' => 'Bicicletas',       'parent_id' => $vehiculos->id]);

        $libroscat    = Category::firstOrCreate(['name' => 'Libros',           'parent_id' => $libros->id]);
        $videojuegos  = Category::firstOrCreate(['name' => 'Videojuegos',      'parent_id' => $libros->id]);
        $musica       = Category::firstOrCreate(['name' => 'Música',           'parent_id' => $libros->id]);

        // ═══════════════════════════════════════════════════════════════
        // NIVEL 3 — Sub-subcategorías (marca / tipo)
        // ═══════════════════════════════════════════════════════════════

        // Móviles
        $apple        = Category::firstOrCreate(['name' => 'Apple',          'parent_id' => $moviles->id]);
        $samsung      = Category::firstOrCreate(['name' => 'Samsung',        'parent_id' => $moviles->id]);
        $xiaomi       = Category::firstOrCreate(['name' => 'Xiaomi',         'parent_id' => $moviles->id]);
        $google       = Category::firstOrCreate(['name' => 'Google',         'parent_id' => $moviles->id]);
        $oneplus      = Category::firstOrCreate(['name' => 'OnePlus',        'parent_id' => $moviles->id]);

        // Portátiles
        $macbooks     = Category::firstOrCreate(['name' => 'MacBooks',       'parent_id' => $portatiles->id]);
        $portGaming   = Category::firstOrCreate(['name' => 'Gaming',         'parent_id' => $portatiles->id]);
        $ultrabook    = Category::firstOrCreate(['name' => 'Ultrabook',      'parent_id' => $portatiles->id]);

        // Auriculares
        $aurWireless  = Category::firstOrCreate(['name' => 'Inalámbricos',   'parent_id' => $auriculares->id]);
        $aurGaming    = Category::firstOrCreate(['name' => 'Gaming',         'parent_id' => $auriculares->id]);

        // Zapatillas
        $nikeCat      = Category::firstOrCreate(['name' => 'Nike',           'parent_id' => $zapatillas->id]);
        $adidasCat    = Category::firstOrCreate(['name' => 'Adidas',         'parent_id' => $zapatillas->id]);
        $nbCat        = Category::firstOrCreate(['name' => 'New Balance',    'parent_id' => $zapatillas->id]);

        // Ropa Hombre
        $rhCamisetas  = Category::firstOrCreate(['name' => 'Camisetas',      'parent_id' => $ropaHombre->id]);
        $rhChaquetas  = Category::firstOrCreate(['name' => 'Chaquetas',      'parent_id' => $ropaHombre->id]);
        $rhPantalones = Category::firstOrCreate(['name' => 'Pantalones',     'parent_id' => $ropaHombre->id]);

        // Ropa Mujer
        $rmVestidos   = Category::firstOrCreate(['name' => 'Vestidos',       'parent_id' => $ropaMujer->id]);
        $rmAbrigos    = Category::firstOrCreate(['name' => 'Abrigos',        'parent_id' => $ropaMujer->id]);

        // Muebles
        $mueblesOfic  = Category::firstOrCreate(['name' => 'Oficina',        'parent_id' => $muebles->id]);
        $mueblesSalon = Category::firstOrCreate(['name' => 'Salón',          'parent_id' => $muebles->id]);
        $mueblesDorm  = Category::firstOrCreate(['name' => 'Dormitorio',     'parent_id' => $muebles->id]);

        // Electrodomésticos
        $edCocina     = Category::firstOrCreate(['name' => 'Cocina',         'parent_id' => $electrodom->id]);
        $edLimpieza   = Category::firstOrCreate(['name' => 'Limpieza',       'parent_id' => $electrodom->id]);

        // Ciclismo
        $mtb          = Category::firstOrCreate(['name' => 'MTB',            'parent_id' => $ciclismo->id]);
        $carretera    = Category::firstOrCreate(['name' => 'Carretera',      'parent_id' => $ciclismo->id]);

        // Videojuegos
        $playstation  = Category::firstOrCreate(['name' => 'PlayStation',    'parent_id' => $videojuegos->id]);
        $nintendo     = Category::firstOrCreate(['name' => 'Nintendo',       'parent_id' => $videojuegos->id]);
        $xbox         = Category::firstOrCreate(['name' => 'Xbox',           'parent_id' => $videojuegos->id]);

        // Música
        $guitarras    = Category::firstOrCreate(['name' => 'Guitarras',      'parent_id' => $musica->id]);
        $pianos       = Category::firstOrCreate(['name' => 'Pianos y Teclados', 'parent_id' => $musica->id]);

        // Motos
        $cascosMoto   = Category::firstOrCreate(['name' => 'Cascos',         'parent_id' => $motos->id]);
        $ropaMoto     = Category::firstOrCreate(['name' => 'Ropa Moto',      'parent_id' => $motos->id]);

        // Bicicletas
        $biciElect    = Category::firstOrCreate(['name' => 'Eléctricas',     'parent_id' => $bicicletas->id]);
                        Category::firstOrCreate(['name' => 'Urbanas',        'parent_id' => $bicicletas->id]);

        // Running
        $zapRunning   = Category::firstOrCreate(['name' => 'Zapatillas Running', 'parent_id' => $running->id]);
        $relojesRun   = Category::firstOrCreate(['name' => 'Relojes GPS',    'parent_id' => $running->id]);

        // Fitness
        $pesas        = Category::firstOrCreate(['name' => 'Pesas',          'parent_id' => $fitness->id]);
        $yoga         = Category::firstOrCreate(['name' => 'Yoga y Pilates', 'parent_id' => $fitness->id]);

        // Libros
        $novela       = Category::firstOrCreate(['name' => 'Novela',         'parent_id' => $libroscat->id]);
        $noFiccion    = Category::firstOrCreate(['name' => 'No Ficción',     'parent_id' => $libroscat->id]);

        // ═══════════════════════════════════════════════════════════════
        // NIVEL 4 — Sub-sub-subcategorías (modelo / serie / especialidad)
        // ═══════════════════════════════════════════════════════════════

        // Apple > iPhone 14 / iPhone 15 / iPad / Accesorios Mac
        $iphone14     = Category::firstOrCreate(['name' => 'iPhone 14',      'parent_id' => $apple->id]);
        $iphone15     = Category::firstOrCreate(['name' => 'iPhone 15',      'parent_id' => $apple->id]);
        $ipad         = Category::firstOrCreate(['name' => 'iPad',           'parent_id' => $apple->id]);
        $macAcc       = Category::firstOrCreate(['name' => 'Accesorios Mac', 'parent_id' => $apple->id]);

        // Samsung > Galaxy S / Galaxy A
        $galaxyS      = Category::firstOrCreate(['name' => 'Galaxy S',       'parent_id' => $samsung->id]);
        $galaxyA      = Category::firstOrCreate(['name' => 'Galaxy A',       'parent_id' => $samsung->id]);

        // Nike > Running / Lifestyle / Fútbol
        $nikeRun      = Category::firstOrCreate(['name' => 'Nike Running',   'parent_id' => $nikeCat->id]);
        $nikeLif      = Category::firstOrCreate(['name' => 'Nike Lifestyle',  'parent_id' => $nikeCat->id]);
        $nikeFut      = Category::firstOrCreate(['name' => 'Nike Fútbol',    'parent_id' => $nikeCat->id]);

        // Adidas > Running / Originals / Fútbol
        $adidasRun    = Category::firstOrCreate(['name' => 'Adidas Running', 'parent_id' => $adidasCat->id]);
        $adidasOri    = Category::firstOrCreate(['name' => 'Adidas Originals','parent_id' => $adidasCat->id]);

        // MTB > Hardtail / Full Suspension
        $hardtail     = Category::firstOrCreate(['name' => 'Hardtail',       'parent_id' => $mtb->id]);
        $fullSusp     = Category::firstOrCreate(['name' => 'Full Suspension','parent_id' => $mtb->id]);

        // Carretera > Carbono / Aluminio
        $carbono      = Category::firstOrCreate(['name' => 'Carbono',        'parent_id' => $carretera->id]);
        $aluminio     = Category::firstOrCreate(['name' => 'Aluminio',       'parent_id' => $carretera->id]);

        // PlayStation > PS5 / PS4
        $ps5cat       = Category::firstOrCreate(['name' => 'PS5',            'parent_id' => $playstation->id]);
        $ps4cat       = Category::firstOrCreate(['name' => 'PS4',            'parent_id' => $playstation->id]);

        // Nintendo > Switch / Accesorios Switch
        $switchCat    = Category::firstOrCreate(['name' => 'Nintendo Switch','parent_id' => $nintendo->id]);
        $switchAcc    = Category::firstOrCreate(['name' => 'Accesorios Switch','parent_id' => $nintendo->id]);

        // Guitarras > Eléctrica / Acústica / Bajo
        $guitElect    = Category::firstOrCreate(['name' => 'Eléctrica',      'parent_id' => $guitarras->id]);
        $guitAcus     = Category::firstOrCreate(['name' => 'Acústica',       'parent_id' => $guitarras->id]);
        $bajo         = Category::firstOrCreate(['name' => 'Bajo',           'parent_id' => $guitarras->id]);

        // Portátiles Gaming > FPS / RPG Setup
        $portFPS      = Category::firstOrCreate(['name' => 'Para FPS',       'parent_id' => $portGaming->id]);
        $portRPG      = Category::firstOrCreate(['name' => 'Para RPG',       'parent_id' => $portGaming->id]);

        // Muebles Oficina > Escritorios / Sillas
        $escritorios  = Category::firstOrCreate(['name' => 'Escritorios',    'parent_id' => $mueblesOfic->id]);
        $sillas       = Category::firstOrCreate(['name' => 'Sillas',         'parent_id' => $mueblesOfic->id]);

        // Electrodomésticos Cocina > Café / Aire / Freidora
        $edCafe       = Category::firstOrCreate(['name' => 'Café',           'parent_id' => $edCocina->id]);
        $edFreidora   = Category::firstOrCreate(['name' => 'Freidoras Aire', 'parent_id' => $edCocina->id]);

        // Pesas > Mancuernas / Barras / Kettlebells
        $mancuernas   = Category::firstOrCreate(['name' => 'Mancuernas',     'parent_id' => $pesas->id]);
        $kettlebells  = Category::firstOrCreate(['name' => 'Kettlebells',    'parent_id' => $pesas->id]);

        // Novela > Ciencia Ficción / Thriller / Fantasía
        $scifi        = Category::firstOrCreate(['name' => 'Ciencia Ficción','parent_id' => $novela->id]);
        $thriller     = Category::firstOrCreate(['name' => 'Thriller',       'parent_id' => $novela->id]);
        $fantasia     = Category::firstOrCreate(['name' => 'Fantasía',       'parent_id' => $novela->id]);

        // No Ficción > Autoayuda / Negocios / Historia
        $autoayuda    = Category::firstOrCreate(['name' => 'Autoayuda',      'parent_id' => $noFiccion->id]);
        $negocios     = Category::firstOrCreate(['name' => 'Negocios',       'parent_id' => $noFiccion->id]);


        // ═══════════════════════════════════════════════════════════════
        // PRODUCTOS — NIVEL 2 (50 productos)
        // ═══════════════════════════════════════════════════════════════
        $nivel2 = [
            ['nombre' => 'iPhone 14 Pro 256GB',          'descripcion' => 'iPhone 14 Pro Space Black. Batería al 91%, incluye cargador y funda. Sin arañazos.',                         'precio' => 749.00,  'estado' => 'activo',   'category_id' => $moviles->id],
            ['nombre' => 'Samsung Galaxy S23 128GB',     'descripcion' => 'Galaxy S23 Phantom Black. 8 meses de uso, perfecto estado. Caja original incluida.',                         'precio' => 520.00,  'estado' => 'activo',   'category_id' => $moviles->id],
            ['nombre' => 'Xiaomi Redmi Note 12',         'descripcion' => 'Redmi Note 12 4GB/128GB. Pantalla AMOLED 120Hz. Como nuevo.',                                                'precio' => 175.00,  'estado' => 'activo',   'category_id' => $moviles->id],
            ['nombre' => 'MacBook Air M2 8GB 256GB',     'descripcion' => 'MacBook Air M2 2022 Midnight. 97 ciclos de batería. Adaptador USB-C y funda incluidos.',                     'precio' => 980.00,  'estado' => 'activo',   'category_id' => $portatiles->id],
            ['nombre' => 'Dell XPS 13 i7 16GB',          'descripcion' => 'Dell XPS 13 9310, i7-1185G7, 16GB RAM, 512GB SSD. Pantalla 4K táctil. Impecable.',                          'precio' => 750.00,  'estado' => 'activo',   'category_id' => $portatiles->id],
            ['nombre' => 'LG OLED 55" C2',               'descripcion' => 'LG OLED55C24LA. WebOS, Dolby Vision, HDMI 2.1. 2 años sin burn-in. Mando original.',                        'precio' => 900.00,  'estado' => 'activo',   'category_id' => $televisores->id],
            ['nombre' => 'Samsung QLED 65" Q70B',        'descripcion' => 'Samsung QLED 65" 4K 120Hz. Tizen OS. Con soporte de pared incluido.',                                        'precio' => 650.00,  'estado' => 'activo',   'category_id' => $televisores->id],
            ['nombre' => 'Sony WH-1000XM5',              'descripcion' => 'Auriculares Sony negros. Cancelación de ruido, 30h batería. Funda incluida.',                                'precio' => 220.00,  'estado' => 'activo',   'category_id' => $auriculares->id],
            ['nombre' => 'AirPods Pro 2ª gen',           'descripcion' => 'AirPods Pro 2G con estuche MagSafe. Batería al 95%. Caja original.',                                         'precio' => 195.00,  'estado' => 'activo',   'category_id' => $auriculares->id],
            ['nombre' => 'Chaqueta North Face talla L',  'descripcion' => 'North Face Resolve 2 azul marino talla L. Impermeable. Dos temporadas.',                                     'precio' => 75.00,   'estado' => 'activo',   'category_id' => $ropaHombre->id],
            ['nombre' => 'Camisa Levi\'s cuadros M',     'descripcion' => 'Camisa de franela Levi\'s cuadros grises talla M. Sin manchas.',                                             'precio' => 22.00,   'estado' => 'activo',   'category_id' => $ropaHombre->id],
            ['nombre' => 'Pantalón Zara slim fit 32',    'descripcion' => 'Pantalón chino slim fit Zara Man kaki talla 32. Puesto dos veces.',                                           'precio' => 18.00,   'estado' => 'activo',   'category_id' => $ropaHombre->id],
            ['nombre' => 'Vestido Zara midi floral S',   'descripcion' => 'Vestido midi Zara floral talla S. Temporada 2024. Solo puesto una vez.',                                     'precio' => 25.00,   'estado' => 'activo',   'category_id' => $ropaMujer->id],
            ['nombre' => 'Abrigo Mango lana camel M',   'descripcion' => 'Abrigo de lana Mango camel talla M. Muy abrigador. Perfecto estado.',                                         'precio' => 55.00,   'estado' => 'activo',   'category_id' => $ropaMujer->id],
            ['nombre' => 'Nike Air Max 90 talla 42',    'descripcion' => 'Nike Air Max 90 blancas talla 42. Usadas 4-5 veces, casi nuevas.',                                             'precio' => 85.00,   'estado' => 'activo',   'category_id' => $zapatillas->id],
            ['nombre' => 'Adidas Ultraboost 22 t.43',   'descripcion' => 'Adidas Ultraboost 22 negras talla 43. Para correr. Suela con desgaste normal.',                               'precio' => 90.00,   'estado' => 'activo',   'category_id' => $zapatillas->id],
            ['nombre' => 'Mochila Herschel 25L',        'descripcion' => 'Mochila Herschel Little America 25L negra. Compartimento portátil 15". 1 año.',                               'precio' => 45.00,   'estado' => 'activo',   'category_id' => $complementos->id],
            ['nombre' => 'Gafas Ray-Ban Aviator',       'descripcion' => 'Ray-Ban Aviator Classic doradas cristal verde G-15. Estuche y paño originales.',                              'precio' => 80.00,   'estado' => 'activo',   'category_id' => $complementos->id],
            ['nombre' => 'Escritorio IKEA Linnmon 150', 'descripcion' => 'Mesa IKEA Linnmon blanca 150x75cm con patas Adils. Mínimos arañazos.',                                         'precio' => 40.00,   'estado' => 'activo',   'category_id' => $muebles->id],
            ['nombre' => 'Silla de oficina ergonómica', 'descripcion' => 'Silla ergonómica negra con reposabrazos regulables y apoyo lumbar. 2 años.',                                  'precio' => 95.00,   'estado' => 'activo',   'category_id' => $muebles->id],
            ['nombre' => 'Estantería Billy IKEA 80cm',  'descripcion' => 'Estantería Billy IKEA 80x202cm blanca. 6 estantes ajustables. Desmontable.',                                  'precio' => 35.00,   'estado' => 'activo',   'category_id' => $muebles->id],
            ['nombre' => 'Aspiradora Dyson V11',        'descripcion' => 'Dyson V11 Torque Drive. Batería al 88%. Todos los accesorios originales.',                                     'precio' => 280.00,  'estado' => 'activo',   'category_id' => $electrodom->id],
            ['nombre' => 'Freidora Ninja AF100',        'descripcion' => 'Freidora de aire Ninja AF100EU 3,8L. 4 modos de cocción. Usada pocas veces.',                                 'precio' => 65.00,   'estado' => 'activo',   'category_id' => $electrodom->id],
            ['nombre' => 'Lámpara de pie arco nórdica', 'descripcion' => 'Lámpara de pie estilo nórdico negro mate 175cm. Bombilla E27 incluida.',                                      'precio' => 55.00,   'estado' => 'activo',   'category_id' => $decoracion->id],
            ['nombre' => 'Cuadro abstracto 60x90cm',   'descripcion' => 'Cuadro sobre lienzo pintado a mano, tonos azul y dorado. Marco madera natural.',                               'precio' => 40.00,   'estado' => 'activo',   'category_id' => $decoracion->id],
            ['nombre' => 'Trek Marlin 5 talla M 2022', 'descripcion' => 'Trek Marlin 5 talla M. Frenos hidráulicos Tektro, horquilla SR Suntour 100mm. 800km.',                         'precio' => 580.00,  'estado' => 'activo',   'category_id' => $ciclismo->id],
            ['nombre' => 'Casco ciclismo Specialized',  'descripcion' => 'Specialized Align II talla M/L MIPS. Negro/rojo. Muy pocas salidas.',                                         'precio' => 55.00,   'estado' => 'activo',   'category_id' => $ciclismo->id],
            ['nombre' => 'Brooks Ghost 15 talla 44',   'descripcion' => 'Brooks Ghost 15 azules talla 44. Amortiguación neutra. ~200km. Buen estado.',                                  'precio' => 75.00,   'estado' => 'activo',   'category_id' => $running->id],
            ['nombre' => 'Garmin Forerunner 245',       'descripcion' => 'Garmin Forerunner 245 negro. GPS, frecuencia cardiaca, VO2max. Con cargador.',                                'precio' => 140.00,  'estado' => 'activo',   'category_id' => $running->id],
            ['nombre' => 'Kettlebell 16kg hierro',      'descripcion' => 'Kettlebell de 16kg hierro fundido con base de goma. Sin óxido ni golpes.',                                    'precio' => 28.00,   'estado' => 'activo',   'category_id' => $fitness->id],
            ['nombre' => 'Esterilla yoga Manduka PRO',  'descripcion' => 'Esterilla Manduka PRO negra 180x66cm 6mm. Antideslizante. Lavada.',                                           'precio' => 70.00,   'estado' => 'activo',   'category_id' => $fitness->id],
            ['nombre' => 'Silla bebé Maxi-Cosi Pebble', 'descripcion' => 'Maxi-Cosi Pebble Pro i-Size grupo 0+ Essential Black. Sin accidente. Con base Familyfix.',                   'precio' => 180.00,  'estado' => 'activo',   'category_id' => $coches->id],
            ['nombre' => 'Navegador TomTom GO 6200',   'descripcion' => 'TomTom GO 6200 pantalla 6". Mapas Europa de por vida. WiFi. Fijación incluida.',                               'precio' => 80.00,   'estado' => 'activo',   'category_id' => $coches->id],
            ['nombre' => 'Casco moto Shoei NXR2 XL',   'descripcion' => 'Shoei NXR2 talla XL negro brillante. 3 años de uso esporádico. Sin caídas. Pinlock.',                         'precio' => 280.00,  'estado' => 'activo',   'category_id' => $motos->id],
            ['nombre' => 'Chaqueta moto Alpinestars L', 'descripcion' => 'Alpinestars Andes V3 talla L. Protecciones CE nivel 1. Membrana impermeable.',                                'precio' => 150.00,  'estado' => 'activo',   'category_id' => $motos->id],
            ['nombre' => 'Patinete eléctrico Xiaomi 3', 'descripcion' => 'Xiaomi Electric Scooter 3. 25km/h, 30km autonomía. Frenos disco. 6 meses de uso.',                           'precio' => 290.00,  'estado' => 'activo',   'category_id' => $bicicletas->id],
            ['nombre' => 'Atomic Habits - James Clear',  'descripcion' => 'Atomic Habits en español, editorial Planeta. Tapa blanda 320p. Algunos subrayados a lápiz.',                'precio' => 9.00,    'estado' => 'activo',   'category_id' => $libroscat->id],
            ['nombre' => 'Harry Potter colección 7 tomos','descripcion' => 'Colección completa HP en español, bolsillo Salamandra. Buen estado, lomos marcados.',                       'precio' => 45.00,   'estado' => 'activo',   'category_id' => $libroscat->id],
            ['nombre' => 'PS5 Slim Digital Edition',    'descripcion' => 'PS5 Slim digital con 2 mandos DualSense y 5 juegos. 3 meses de uso. Perfecto.',                               'precio' => 420.00,  'estado' => 'activo',   'category_id' => $videojuegos->id],
            ['nombre' => 'Nintendo Switch OLED blanca', 'descripcion' => 'Switch OLED blanca. Dock, cables y Zelda TOTK + Mario Kart 8. Sin rayones.',                                 'precio' => 280.00,  'estado' => 'activo',   'category_id' => $videojuegos->id],
            ['nombre' => 'Guitarra Yamaha F310',        'descripcion' => 'Yamaha F310 natural. Cuerdas nuevas. Funda acolchada incluida.',                                              'precio' => 95.00,   'estado' => 'activo',   'category_id' => $musica->id],
            ['nombre' => 'Google Pixel 7a 128GB',       'descripcion' => 'Pixel 7a negro 128GB. Cámara excepcional. Desbloqueado para cualquier operador.',                            'precio' => 380.00,  'estado' => 'activo',   'category_id' => $moviles->id],
            ['nombre' => 'Lenovo ThinkPad L14 AMD',     'descripcion' => 'ThinkPad L14 Ryzen 5, 8GB RAM, 256GB SSD. Teclado retroiluminado. 8h reales.',                               'precio' => 410.00,  'estado' => 'activo',   'category_id' => $portatiles->id],
            ['nombre' => 'Sony Bravia 43" X80K',        'descripcion' => 'Sony KD-43X80K 4K HDR Google TV. Sin arañazos ni defectos en pantalla.',                                     'precio' => 320.00,  'estado' => 'activo',   'category_id' => $televisores->id],
            ['nombre' => 'Bose QuietComfort 45',        'descripcion' => 'Bose QC45 blancos. Sonido premium, comodidad excepcional. Usados esporádicamente.',                           'precio' => 180.00,  'estado' => 'reservado','category_id' => $auriculares->id],
            ['nombre' => 'Blusa H&M seda artificial S', 'descripcion' => 'Blusa efecto seda H&M blanco roto talla S. Sin defectos. Ideal para evento.',                                'precio' => 12.00,   'estado' => 'activo',   'category_id' => $ropaMujer->id],
            ['nombre' => 'Converse All Star Chuck 41',  'descripcion' => 'Converse Chuck Taylor negras talla 41. Clásico. Algunos arañazos de uso.',                                   'precio' => 35.00,   'estado' => 'activo',   'category_id' => $zapatillas->id],
            ['nombre' => 'Cafetera De\'Longhi Magnifica','descripcion' => 'De\'Longhi Magnifica Start superautomática. Molinillo integrado. Limpieza reciente.',                        'precio' => 310.00,  'estado' => 'reservado','category_id' => $electrodom->id],
            ['nombre' => 'Maillot Castelli Gabba L',    'descripcion' => 'Maillot Castelli Gabba talla L cortavientos manga larga. Lavado, perfecto estado.',                           'precio' => 65.00,   'estado' => 'activo',   'category_id' => $ciclismo->id],
            ['nombre' => 'Trilogía El Problema 3 Cuerpos','descripcion' => 'Trilogía Liu Cixin en español bolsillo. Como nuevos, leídos con cuidado.',                                  'precio' => 22.00,   'estado' => 'activo',   'category_id' => $libroscat->id],
        ];

        // ═══════════════════════════════════════════════════════════════
        // PRODUCTOS — NIVEL 3 (50 productos)
        // ═══════════════════════════════════════════════════════════════
        $nivel3 = [
            // Apple (móviles nivel 3)
            ['nombre' => 'iPhone 13 128GB Azul',        'descripcion' => 'iPhone 13 azul 128GB. Batería al 88%, funda y protector. Sin golpes ni arañazos.',                           'precio' => 590.00,  'estado' => 'activo',   'category_id' => $apple->id],
            ['nombre' => 'iPhone 12 64GB Negro',        'descripcion' => 'iPhone 12 negro 64GB. Face ID perfecto. Ligera marca en esquina trasera. Con cargador.',                     'precio' => 380.00,  'estado' => 'activo',   'category_id' => $apple->id],
            ['nombre' => 'iPhone SE 3ª gen 128GB',      'descripcion' => 'iPhone SE 2022 blanco 128GB. Batería 90%. Pantalla inmaculada. Caja original.',                              'precio' => 320.00,  'estado' => 'activo',   'category_id' => $apple->id],

            // Samsung (móviles nivel 3)
            ['nombre' => 'Samsung Galaxy S22 256GB',    'descripcion' => 'Galaxy S22 Phantom White 256GB. Cámara 50MP. 1 año de uso, en perfecto estado.',                             'precio' => 460.00,  'estado' => 'activo',   'category_id' => $samsung->id],
            ['nombre' => 'Samsung Galaxy A54 128GB',    'descripcion' => 'Galaxy A54 5G negro 128GB. Pantalla SuperAMOLED. Sin arañazos. Con funda.',                                  'precio' => 260.00,  'estado' => 'activo',   'category_id' => $samsung->id],
            ['nombre' => 'Samsung Galaxy S21 FE 128GB', 'descripcion' => 'Galaxy S21 FE lavanda 128GB. Muy buen estado. Actualizado a Android 14.',                                    'precio' => 300.00,  'estado' => 'activo',   'category_id' => $samsung->id],

            // Xiaomi (móviles nivel 3)
            ['nombre' => 'Xiaomi 13 256GB Negro',       'descripcion' => 'Xiaomi 13 negro 256GB. Cámara Leica. Carga 67W. Como nuevo, 3 meses de uso.',                                'precio' => 520.00,  'estado' => 'activo',   'category_id' => $xiaomi->id],
            ['nombre' => 'Xiaomi Poco X5 Pro 5G',       'descripcion' => 'Poco X5 Pro 5G azul 256GB. Pantalla 120Hz AMOLED. Muy buen rendimiento para su precio.',                    'precio' => 220.00,  'estado' => 'activo',   'category_id' => $xiaomi->id],

            // Google (móviles nivel 3)
            ['nombre' => 'Google Pixel 6a 128GB',       'descripcion' => 'Pixel 6a negro 128GB. Chip Tensor. Batería al 85%. Caja y cargador incluidos.',                              'precio' => 260.00,  'estado' => 'activo',   'category_id' => $google->id],

            // OnePlus (móviles nivel 3)
            ['nombre' => 'OnePlus 10 Pro 256GB',        'descripcion' => 'OnePlus 10 Pro verde 256GB. Carga 80W. Pantalla LTPO2. 1 año de uso.',                                       'precio' => 390.00,  'estado' => 'activo',   'category_id' => $oneplus->id],

            // MacBooks (portátiles nivel 3)
            ['nombre' => 'MacBook Pro M1 Pro 14"',      'descripcion' => 'MacBook Pro 14" M1 Pro 16GB/512GB Space Gray. 120Hz ProMotion. 80 ciclos. Impecable.',                       'precio' => 1500.00, 'estado' => 'activo',   'category_id' => $macbooks->id],
            ['nombre' => 'MacBook Air M1 8GB 256GB',    'descripcion' => 'MacBook Air M1 gold 8GB/256GB. 150 ciclos de batería. Pantalla y teclado perfectos.',                        'precio' => 720.00,  'estado' => 'activo',   'category_id' => $macbooks->id],

            // Portátiles Gaming (nivel 3)
            ['nombre' => 'ASUS ROG Strix G15 RTX3070', 'descripcion' => 'ROG Strix G15 Ryzen 9 5900HX, RTX 3070, 16GB, 1TB SSD. 165Hz. Perfecto para gaming.',                       'precio' => 1200.00, 'estado' => 'activo',   'category_id' => $portGaming->id],
            ['nombre' => 'MSI Katana GF76 RTX3060',    'descripcion' => 'MSI Katana GF76 i7-11800H RTX3060 16GB 512GB. 144Hz FHD. Buen estado.',                                      'precio' => 780.00,  'estado' => 'activo',   'category_id' => $portGaming->id],

            // Ultrabook (nivel 3)
            ['nombre' => 'Samsung Galaxy Book3 Pro',   'descripcion' => 'Galaxy Book3 Pro i7-1360P 16GB 512GB. 15.6" AMOLED. Peso 1.56kg. 4 meses de uso.',                           'precio' => 880.00,  'estado' => 'activo',   'category_id' => $ultrabook->id],

            // Auriculares Inalámbricos (nivel 3)
            ['nombre' => 'Jabra Evolve2 55 ANC',       'descripcion' => 'Jabra Evolve2 55 con ANC. Ideal para teletrabajo. Conexión multipoint. Como nuevos.',                         'precio' => 180.00,  'estado' => 'activo',   'category_id' => $aurWireless->id],
            ['nombre' => 'Sennheiser Momentum 4',      'descripcion' => 'Sennheiser Momentum 4 Wireless negros. 60h batería. ANC adaptativo. Estuche incluido.',                       'precio' => 210.00,  'estado' => 'activo',   'category_id' => $aurWireless->id],

            // Auriculares Gaming (nivel 3)
            ['nombre' => 'SteelSeries Arctis Nova Pro', 'descripcion' => 'Arctis Nova Pro con base de carga. ANC, batería intercambiable. Para PS5 y PC.',                             'precio' => 200.00,  'estado' => 'activo',   'category_id' => $aurGaming->id],
            ['nombre' => 'Razer BlackShark V2 Pro',    'descripcion' => 'Razer BlackShark V2 Pro inalámbrico. THX 7.1. Micrófono extraíble. Para PC.',                                 'precio' => 130.00,  'estado' => 'activo',   'category_id' => $aurGaming->id],

            // Nike zapatillas (nivel 3)
            ['nombre' => 'Nike Pegasus 40 talla 43',   'descripcion' => 'Nike Pegasus 40 negras talla 43. Amortiguación React. ~300km. Suela con desgaste.',                           'precio' => 80.00,   'estado' => 'activo',   'category_id' => $nikeCat->id],
            ['nombre' => 'Nike Air Force 1 blancas 42','descripcion' => 'Nike Air Force 1 07 blancas talla 42. Usadas 10 veces, muy buen estado.',                                     'precio' => 70.00,   'estado' => 'activo',   'category_id' => $nikeCat->id],

            // Adidas zapatillas (nivel 3)
            ['nombre' => 'Adidas Samba OG talla 41',  'descripcion' => 'Adidas Samba OG blancos talla 41. Edición clásica. Compradas hace 3 meses.',                                   'precio' => 75.00,   'estado' => 'activo',   'category_id' => $adidasCat->id],
            ['nombre' => 'Adidas Stan Smith talla 40', 'descripcion' => 'Adidas Stan Smith blancos con lengüeta verde talla 40. Muy buen estado.',                                     'precio' => 50.00,   'estado' => 'activo',   'category_id' => $adidasCat->id],

            // New Balance (nivel 3)
            ['nombre' => 'New Balance 574 talla 42',  'descripcion' => 'New Balance 574 gris talla 42. Retro y cómoda. Usadas 6 meses, buen estado.',                                  'precio' => 60.00,   'estado' => 'activo',   'category_id' => $nbCat->id],

            // Ropa Hombre - Camisetas (nivel 3)
            ['nombre' => 'Pack 5 camisetas básicas M', 'descripcion' => 'Pack 5 camisetas algodón talla M: blanca, negra, gris, azul marino y verde. H&M. Como nuevas.',               'precio' => 20.00,   'estado' => 'activo',   'category_id' => $rhCamisetas->id],
            ['nombre' => 'Camiseta Tommy Hilfiger M',  'descripcion' => 'Camiseta Tommy Hilfiger blanca logo bordado talla M. Solo lavada, sin uso.',                                   'precio' => 25.00,   'estado' => 'activo',   'category_id' => $rhCamisetas->id],

            // Ropa Hombre - Chaquetas (nivel 3)
            ['nombre' => 'Chaqueta vaquera Levi\'s M','descripcion' => 'Chaqueta denim Levi\'s talla M. Azul clásico. Varios años de uso pero muy buen estado.',                       'precio' => 35.00,   'estado' => 'activo',   'category_id' => $rhChaquetas->id],

            // Ropa Hombre - Pantalones (nivel 3)
            ['nombre' => 'Jeans Levi\'s 501 W32 L32', 'descripcion' => 'Levi\'s 501 Original azul W32/L32. Clásico. Usados 1 año, tela en perfecto estado.',                          'precio' => 30.00,   'estado' => 'activo',   'category_id' => $rhPantalones->id],

            // Ropa Mujer - Vestidos (nivel 3)
            ['nombre' => 'Vestido largo boho M',       'descripcion' => 'Vestido largo estilo bohemio estampado flores talla M. Massimo Dutti. 2 temporadas.',                         'precio' => 35.00,   'estado' => 'activo',   'category_id' => $rmVestidos->id],

            // Ropa Mujer - Abrigos (nivel 3)
            ['nombre' => 'Abrigo Bershka oversize S',  'descripcion' => 'Abrigo oversize gris claro Bershka talla S. Una temporada. Como nuevo.',                                      'precio' => 30.00,   'estado' => 'activo',   'category_id' => $rmAbrigos->id],

            // Muebles Oficina (nivel 3)
            ['nombre' => 'Mesa esquinera IKEA blanca', 'descripcion' => 'Mesa esquinera Micke IKEA 100x142cm blanca. Cajón con llave. Fácil montaje.',                                 'precio' => 60.00,   'estado' => 'activo',   'category_id' => $mueblesOfic->id],

            // Muebles Salón (nivel 3)
            ['nombre' => 'Mesa de centro madera roble','descripcion' => 'Mesa de centro rectangular roble natural 120x60cm. Sin arañazos. Muy buen estado.',                           'precio' => 90.00,   'estado' => 'activo',   'category_id' => $mueblesSalon->id],

            // Muebles Dormitorio (nivel 3)
            ['nombre' => 'Cabecero tapizado 150cm',    'descripcion' => 'Cabecero de cama 150cm tapizado en terciopelo gris. Anclaje a pared. Como nuevo.',                            'precio' => 70.00,   'estado' => 'activo',   'category_id' => $mueblesDorm->id],

            // Electrodomésticos Cocina (nivel 3)
            ['nombre' => 'Thermomix TM6',              'descripcion' => 'Thermomix TM6. Impecable, báscula integrada, navegación por recetas. Con mariposa y accesorios.',             'precio' => 800.00,  'estado' => 'activo',   'category_id' => $edCocina->id],
            ['nombre' => 'Robot cocina Monsieur Cuisine','descripcion' => 'Monsieur Cuisine Smart Lidl 1200W. Pantalla táctil 7". Como nuevo, solo 2 usos.',                           'precio' => 180.00,  'estado' => 'activo',   'category_id' => $edCocina->id],

            // Electrodomésticos Limpieza (nivel 3)
            ['nombre' => 'Robot aspirador Roomba i5',  'descripcion' => 'iRobot Roomba i5+ con base de vaciado automático. 2 años. Filtros y cepillos nuevos.',                        'precio' => 280.00,  'estado' => 'activo',   'category_id' => $edLimpieza->id],

            // MTB (nivel 3)
            ['nombre' => 'Scott Aspect 950 talla L',   'descripcion' => 'Scott Aspect 950 talla L 29". Frenos hidráulicos Shimano. 2 temporadas, 1200km.',                             'precio' => 650.00,  'estado' => 'activo',   'category_id' => $mtb->id],

            // Carretera (nivel 3)
            ['nombre' => 'Bicicleta carretera Canyon Endurace','descripcion' => 'Canyon Endurace AL 7 talla M. Shimano 105, frenos disco. 2000km. Muy buen estado.',                  'precio' => 1100.00, 'estado' => 'activo',   'category_id' => $carretera->id],

            // PlayStation (nivel 3)
            ['nombre' => 'DualSense PS5 blanco',       'descripcion' => 'Mando DualSense PS5 blanco. Gatillos adaptativos y vibración háptica. Pocas horas.',                          'precio' => 55.00,   'estado' => 'activo',   'category_id' => $playstation->id],
            ['nombre' => 'PS4 Pro 1TB negro',          'descripcion' => 'PS4 Pro 1TB negra. Con 2 mandos y 8 juegos. Funciona perfectamente, disco silencioso.',                       'precio' => 200.00,  'estado' => 'activo',   'category_id' => $playstation->id],

            // Nintendo (nivel 3)
            ['nombre' => 'Nintendo Switch Lite amarilla','descripcion' => 'Switch Lite amarilla. 3 años de uso. Pantalla sin arañazos. Con funda y 4 juegos.',                         'precio' => 150.00,  'estado' => 'activo',   'category_id' => $nintendo->id],

            // Xbox (nivel 3)
            ['nombre' => 'Xbox Series S 512GB blanca', 'descripcion' => 'Xbox Series S blanca 512GB. 2 mandos. Game Pass no incluido. Perfecto estado.',                               'precio' => 220.00,  'estado' => 'activo',   'category_id' => $xbox->id],

            // Guitarras (nivel 3)
            ['nombre' => 'Guitarra acústica Fender CD-60S','descripcion' => 'Fender CD-60S natural. Tapa de abeto macizo. Funda incluida. Para principiante/intermedio.',              'precio' => 120.00,  'estado' => 'activo',   'category_id' => $guitarras->id],

            // Pianos y Teclados (nivel 3)
            ['nombre' => 'Piano digital Casio CT-S300','descripcion' => 'Casio CT-S300 61 teclas. Adaptador incluido. Sin arañazos. Ideal para aprender.',                             'precio' => 65.00,   'estado' => 'activo',   'category_id' => $pianos->id],

            // Cascos moto (nivel 3)
            ['nombre' => 'Casco moto HJC RPHA 11 M',  'descripcion' => 'HJC RPHA 11 talla M Pinlock 70 incluido. Homologado ECE 22.06. Sin caídas. Excelente aerodinámica.',          'precio' => 230.00,  'estado' => 'activo',   'category_id' => $cascosMoto->id],

            // Ropa moto (nivel 3)
            ['nombre' => 'Pantalón moto Revit Cayenne','descripcion' => 'Revit Cayenne Pro talla 32 negro. Protecciones nivel 2 rodilla/cadera. Impermeable.',                         'precio' => 120.00,  'estado' => 'activo',   'category_id' => $ropaMoto->id],

            // Bicicletas Eléctricas (nivel 3)
            ['nombre' => 'Bici eléctrica Orbea Gain M20','descripcion' => 'Orbea Gain M20 talla M. Motor Shimano Steps E5000. Batería 504Wh. 800km.',                                  'precio' => 1800.00, 'estado' => 'activo',   'category_id' => $biciElect->id],

            // Zapatillas Running (nivel 3)
            ['nombre' => 'ASICS Gel-Nimbus 25 t.42',  'descripcion' => 'ASICS Gel-Nimbus 25 negro talla 42. Máxima amortiguación. ~400km. Suela con desgaste visible.',                'precio' => 90.00,   'estado' => 'activo',   'category_id' => $zapRunning->id],

            // Relojes GPS (nivel 3)
            ['nombre' => 'Polar Vantage M2',           'descripcion' => 'Polar Vantage M2 negro. GPS preciso, frecuencia cardiaca, métricas avanzadas. Batería perfecta.',             'precio' => 160.00,  'estado' => 'activo',   'category_id' => $relojesRun->id],

            // Pesas (nivel 3)
            ['nombre' => 'Mancuernas 10kg el par',     'descripcion' => 'Par de mancuernas de neopreno 10kg. Superficie antideslizante. Sin óxido. Poco uso.',                         'precio' => 35.00,   'estado' => 'activo',   'category_id' => $pesas->id],

            // Yoga (nivel 3)
            ['nombre' => 'Set yoga Lululemon completo','descripcion' => 'Set yoga Lululemon: esterilla 5mm, bloque, correa y bolsa. Como nuevo.',                                       'precio' => 85.00,   'estado' => 'activo',   'category_id' => $yoga->id],

            // Novela (nivel 3)
            ['nombre' => 'Dune - Frank Herbert',       'descripcion' => 'Dune edición de lujo tapa dura Penguin. Como nuevo, leído una vez con mucho cuidado.',                        'precio' => 22.00,   'estado' => 'activo',   'category_id' => $novela->id],

            // No Ficción (nivel 3)
            ['nombre' => 'El Arte de la Guerra - Sun Tzu','descripcion' => 'El Arte de la Guerra edición bilingüe chino-español. Tapa dura. Como nuevo.',                              'precio' => 14.00,   'estado' => 'activo',   'category_id' => $noFiccion->id],
        ];

        // ═══════════════════════════════════════════════════════════════
        // PRODUCTOS — NIVEL 4 (50 productos)
        // ═══════════════════════════════════════════════════════════════
        $nivel4 = [
            // iPhone 14 (nivel 4)
            ['nombre' => 'iPhone 14 Plus 256GB morado', 'descripcion' => 'iPhone 14 Plus Deep Purple 256GB. Pantalla 6.7". Batería al 93%. Caja original y funda.',                   'precio' => 710.00,  'estado' => 'activo',   'category_id' => $iphone14->id],
            ['nombre' => 'iPhone 14 128GB negro medianoche','descripcion' => 'iPhone 14 Midnight 128GB. Sin golpes. Batería al 89%. Con MagSafe y cargador 20W.',                    'precio' => 620.00,  'estado' => 'activo',   'category_id' => $iphone14->id],
            ['nombre' => 'iPhone 14 Pro Max 512GB plata','descripcion' => 'iPhone 14 Pro Max Silver 512GB. Isla Dinámica. Batería al 87%. Caja e incluye AppleCare hasta julio.',     'precio' => 980.00,  'estado' => 'activo',   'category_id' => $iphone14->id],

            // iPhone 15 (nivel 4)
            ['nombre' => 'iPhone 15 128GB rosa',       'descripcion' => 'iPhone 15 pink 128GB. USB-C. Solo 2 meses de uso. Batería al 98%. Caja y funda originales.',                'precio' => 820.00,  'estado' => 'activo',   'category_id' => $iphone15->id],
            ['nombre' => 'iPhone 15 Pro 256GB titanio negro','descripcion' => 'iPhone 15 Pro Black Titanium 256GB. Cámara 48MP, zoom 5x. 1 mes de uso. Impecable.',                  'precio' => 1050.00, 'estado' => 'activo',   'category_id' => $iphone15->id],

            // iPad (nivel 4)
            ['nombre' => 'iPad 10ª gen 64GB WiFi azul','descripcion' => 'iPad 10ª generación 64GB WiFi azul. USB-C. Pantalla Liquid Retina. Con funda y pencil.',                    'precio' => 420.00,  'estado' => 'activo',   'category_id' => $ipad->id],
            ['nombre' => 'iPad Pro 11" M2 256GB WiFi', 'descripcion' => 'iPad Pro 11" M2 2022 256GB WiFi Space Gray. ProMotion 120Hz. Apple Pencil 2 incluido.',                      'precio' => 850.00,  'estado' => 'activo',   'category_id' => $ipad->id],
            ['nombre' => 'iPad Air 5ª gen 64GB azul',  'descripcion' => 'iPad Air 5 azul cielo 64GB WiFi. Chip M1. Poco uso. Teclado Magic Keyboard incluido.',                      'precio' => 580.00,  'estado' => 'activo',   'category_id' => $ipad->id],

            // Accesorios Mac (nivel 4)
            ['nombre' => 'Magic Keyboard Touch ID ES',  'descripcion' => 'Apple Magic Keyboard con Touch ID distribución española. Plateado. Como nuevo.',                            'precio' => 95.00,   'estado' => 'activo',   'category_id' => $macAcc->id],
            ['nombre' => 'Apple Magic Mouse blanco',    'descripcion' => 'Apple Magic Mouse 2 blanco. Superficie Multi-Touch. Batería perfecta. Con cable.',                          'precio' => 60.00,   'estado' => 'activo',   'category_id' => $macAcc->id],

            // Galaxy S (nivel 4)
            ['nombre' => 'Samsung Galaxy S24 Ultra 256GB','descripcion' => 'Galaxy S24 Ultra Titanium Black 256GB. S Pen integrado. 200MP. 1 mes de uso.',                            'precio' => 1100.00, 'estado' => 'activo',   'category_id' => $galaxyS->id],
            ['nombre' => 'Samsung Galaxy S23+ 512GB',   'descripcion' => 'Galaxy S23+ Phantom Black 512GB. Carga inalámbrica. Pantalla sin arañazos. 8 meses.',                      'precio' => 680.00,  'estado' => 'activo',   'category_id' => $galaxyS->id],

            // Galaxy A (nivel 4)
            ['nombre' => 'Samsung Galaxy A34 5G 128GB','descripcion' => 'Galaxy A34 5G violeta 128GB. Pantalla Super AMOLED 120Hz. Batería 5000mAh. Como nuevo.',                    'precio' => 230.00,  'estado' => 'activo',   'category_id' => $galaxyA->id],
            ['nombre' => 'Samsung Galaxy A14 64GB',    'descripcion' => 'Galaxy A14 negro 64GB. Ideal para uso básico. Batería 5000mAh. En buen estado.',                             'precio' => 120.00,  'estado' => 'activo',   'category_id' => $galaxyA->id],

            // Nike Running (nivel 4)
            ['nombre' => 'Nike Vaporfly 2 t.42 naranja','descripcion' => 'Nike Vaporfly Next% 2 naranja talla 42. Carbono. ~120km. Ideales para competición.',                        'precio' => 180.00,  'estado' => 'activo',   'category_id' => $nikeRun->id],
            ['nombre' => 'Nike Invincible 3 t.44',     'descripcion' => 'Nike Invincible Run 3 blancas talla 44. Máxima amortiguación. Muy pocas salidas.',                           'precio' => 130.00,  'estado' => 'activo',   'category_id' => $nikeRun->id],

            // Nike Lifestyle (nivel 4)
            ['nombre' => 'Nike Dunk Low Panda t.41',   'descripcion' => 'Nike Dunk Low blancas/negras (Panda) talla 41. Usadas 5 veces. Estado muy bueno.',                          'precio' => 110.00,  'estado' => 'activo',   'category_id' => $nikeLif->id],
            ['nombre' => 'Nike Cortez negro/blanco t.40','descripcion' => 'Nike Cortez negro/blanco talla 40. Retro. Compradas hace 4 meses. Casi nuevas.',                           'precio' => 65.00,   'estado' => 'activo',   'category_id' => $nikeLif->id],

            // Nike Fútbol (nivel 4)
            ['nombre' => 'Nike Mercurial Superfly 9 t.43','descripcion' => 'Nike Mercurial Superfly 9 Elite FG talla 43 negro/dorado. 4 partidos. Tacos en perfecto estado.',         'precio' => 140.00,  'estado' => 'activo',   'category_id' => $nikeFut->id],

            // Adidas Running (nivel 4)
            ['nombre' => 'Adidas Adizero Boston 12 t.43','descripcion' => 'Adidas Adizero Boston 12 talla 43 negro. ~200km. Ligeras y rápidas. Para competición.',                    'precio' => 100.00,  'estado' => 'activo',   'category_id' => $adidasRun->id],

            // Adidas Originals (nivel 4)
            ['nombre' => 'Adidas Gazelle Indoor t.41', 'descripcion' => 'Adidas Gazelle Indoor cream/verde talla 41. Edición limitada. Usadas 3 veces.',                              'precio' => 90.00,   'estado' => 'activo',   'category_id' => $adidasOri->id],
            ['nombre' => 'Adidas Campus 00s t.42',     'descripcion' => 'Adidas Campus 00s gris talla 42. Trend actual. Compradas hace 2 meses. Como nuevas.',                        'precio' => 80.00,   'estado' => 'activo',   'category_id' => $adidasOri->id],

            // MTB Hardtail (nivel 4)
            ['nombre' => 'Trek Marlin 7 talla L 2023', 'descripcion' => 'Trek Marlin 7 2023 talla L 29". 9 velocidades, frenos hidráulicos. 600km. Impecable.',                       'precio' => 820.00,  'estado' => 'activo',   'category_id' => $hardtail->id],
            ['nombre' => 'Specialized Rockhopper Comp','descripcion' => 'Specialized Rockhopper Comp 29 talla M. Horquilla RockShox Judy. 800km. Bien mantenida.',                    'precio' => 700.00,  'estado' => 'activo',   'category_id' => $hardtail->id],

            // MTB Full Suspension (nivel 4)
            ['nombre' => 'Giant Trance X 29 talla M',  'descripcion' => 'Giant Trance X 29 2022 talla M. Full suspension 120/120mm. Shimano SLX. 1500km.',                           'precio' => 2000.00, 'estado' => 'activo',   'category_id' => $fullSusp->id],

            // Carretera Carbono (nivel 4)
            ['nombre' => 'Trek Domane SL 6 talla 56',  'descripcion' => 'Trek Domane SL 6 2021 talla 56. Carbono, Shimano Ultegra Di2. 4000km. Revisión reciente.',                  'precio' => 3200.00, 'estado' => 'activo',   'category_id' => $carbono->id],

            // Carretera Aluminio (nivel 4)
            ['nombre' => 'Orbea Avant H40 talla 54',   'descripcion' => 'Orbea Avant H40 2022 aluminio talla 54. Shimano 105. Frenos disco. 2500km. Buen estado.',                   'precio' => 950.00,  'estado' => 'activo',   'category_id' => $aluminio->id],

            // PS5 (nivel 4)
            ['nombre' => 'PS5 + Spider-Man 2 + Mando', 'descripcion' => 'PS5 estándar con lector + Spider-Man 2 físico + mando DualSense extra rojo. 4 meses.',                      'precio' => 530.00,  'estado' => 'activo',   'category_id' => $ps5cat->id],
            ['nombre' => 'DualSense Edge PS5',          'descripcion' => 'Mando DualSense Edge blanco. Gatillos y sticks intercambiables. Perfiles personalizados.',                  'precio' => 150.00,  'estado' => 'activo',   'category_id' => $ps5cat->id],

            // PS4 (nivel 4)
            ['nombre' => 'PS4 Slim 1TB + 15 juegos',   'descripcion' => 'PS4 Slim 1TB negra + 15 juegos (GTA V, God of War, Uncharted 4...). 2 mandos. Todo ok.',                    'precio' => 180.00,  'estado' => 'activo',   'category_id' => $ps4cat->id],

            // Nintendo Switch (nivel 4)
            ['nombre' => 'Nintendo Switch V2 + Zelda BOTW','descripcion' => 'Switch V2 + dock + 2 Joy-Con + Zelda Breath of the Wild. 2 años. Pantalla impecable.',                  'precio' => 200.00,  'estado' => 'activo',   'category_id' => $switchCat->id],
            ['nombre' => 'Switch OLED + Mario Odyssey', 'descripcion' => 'Nintendo Switch OLED + Super Mario Odyssey físico + funda. 6 meses de uso. Sin arañazos.',                  'precio' => 290.00,  'estado' => 'activo',   'category_id' => $switchCat->id],

            // Switch Accesorios (nivel 4)
            ['nombre' => 'Joy-Con par azul/rojo neón',  'descripcion' => 'Par Joy-Con azul/rojo neón. Drift corregido por Nintendo. Funcionan perfectamente.',                        'precio' => 55.00,   'estado' => 'activo',   'category_id' => $switchAcc->id],

            // Guitarras Eléctricas (nivel 4)
            ['nombre' => 'Fender Stratocaster Mexicana','descripcion' => 'Fender Player Stratocaster 3-Color Sunburst. Pastillas Alnico 5. Funda y correa incluidas.',               'precio' => 550.00,  'estado' => 'activo',   'category_id' => $guitElect->id],
            ['nombre' => 'Gibson SG Standard 2020',    'descripcion' => 'Gibson SG Standard Heritage Cherry 2020. Pastillas 490R/490T. 2 años de uso. Excelente sonido.',             'precio' => 900.00,  'estado' => 'activo',   'category_id' => $guitElect->id],

            // Guitarras Acústicas (nivel 4)
            ['nombre' => 'Taylor 114ce Grand Auditorium','descripcion' => 'Taylor 114ce tapa abeto/cuerpo sapeli. Cutaway. Pastilla Expression System. Estuche rígido.',              'precio' => 680.00,  'estado' => 'activo',   'category_id' => $guitAcus->id],

            // Bajo (nivel 4)
            ['nombre' => 'Fender Precision Bass MX',   'descripcion' => 'Fender Player Precision Bass negro. Pastilla Split-Single. Con funda y correa. 3 años.',                    'precio' => 480.00,  'estado' => 'activo',   'category_id' => $bajo->id],

            // Portátiles Gaming FPS (nivel 4)
            ['nombre' => 'ASUS ROG Zephyrus G14 2023', 'descripcion' => 'ROG Zephyrus G14 2023 Ryzen 9 7940HS RTX4060 16GB 512GB. 165Hz 1440p. 2 meses.',                            'precio' => 1350.00, 'estado' => 'activo',   'category_id' => $portFPS->id],

            // Portátiles Gaming RPG (nivel 4)
            ['nombre' => 'Razer Blade 15 RTX3080Ti',   'descripcion' => 'Razer Blade 15 i9-12900H RTX3080Ti 32GB 1TB SSD QHD 240Hz. Para AAA. 6 meses.',                             'precio' => 2200.00, 'estado' => 'activo',   'category_id' => $portRPG->id],

            // Escritorios (nivel 4)
            ['nombre' => 'Escritorio gaming RGB 160cm','descripcion' => 'Escritorio gaming con tira LED RGB integrada 160x70cm. Superficie carbon fiber. Con canaleta.',              'precio' => 110.00,  'estado' => 'activo',   'category_id' => $escritorios->id],
            ['nombre' => 'Escritorio elevable eléctrico','descripcion' => 'Escritorio regulable en altura eléctrico 140x70cm. Motor silencioso. Memoria 4 alturas.',                  'precio' => 280.00,  'estado' => 'activo',   'category_id' => $escritorios->id],

            // Sillas (nivel 4)
            ['nombre' => 'Silla gaming Secretlab Titan','descripcion' => 'Secretlab Titan XL 2022 negra. Cuero PU. Reposacabezas y lumbar magnéticos. 1 año.',                        'precio' => 280.00,  'estado' => 'activo',   'category_id' => $sillas->id],

            // Café (nivel 4)
            ['nombre' => 'Nespresso Vertuo Next blanca','descripcion' => 'Nespresso Vertuo Next blanca. Aeroccino3 incluido. 2 años de uso. Limpieza descalcificación hecha.',        'precio' => 90.00,   'estado' => 'activo',   'category_id' => $edCafe->id],

            // Freidoras Aire (nivel 4)
            ['nombre' => 'Cosori Pro LE 4.7L',         'descripcion' => 'Cosori Pro LE 4.7L freidora aire. 9 funciones. Cesta con recubrimiento antiadherente. Como nueva.',         'precio' => 70.00,   'estado' => 'activo',   'category_id' => $edFreidora->id],
            ['nombre' => 'Tefal Easy Fry Grill 4.2L',  'descripcion' => 'Tefal Easy Fry & Grill XXL 4.2L con bandeja grill. Pantalla digital. Sin arañazos.',                        'precio' => 75.00,   'estado' => 'activo',   'category_id' => $edFreidora->id],

            // Mancuernas (nivel 4)
            ['nombre' => 'Mancuernas ajustables 5-25kg','descripcion' => 'Set mancuernas ajustables Bowflex 5-25kg. Ajuste rápido. Con base. Sin daños. 1 año.',                      'precio' => 180.00,  'estado' => 'activo',   'category_id' => $mancuernas->id],

            // Kettlebells (nivel 4)
            ['nombre' => 'Set kettlebells 8-12-16kg',  'descripcion' => 'Set 3 kettlebells hierro fundido 8, 12 y 16kg. Base recubierta de goma. Sin óxido.',                         'precio' => 65.00,   'estado' => 'activo',   'category_id' => $kettlebells->id],

            // Ciencia Ficción (nivel 4)
            ['nombre' => 'Fundación - Isaac Asimov',   'descripcion' => 'Ciclo de Fundación completo (7 tomos) en español. Edición Debolsillo. Buen estado.',                         'precio' => 35.00,   'estado' => 'activo',   'category_id' => $scifi->id],
            ['nombre' => 'Neuromante - William Gibson', 'descripcion' => 'Neuromante edición Minotauro tapa blanda. Cyberpunk clásico. Leído con cuidado.',                            'precio' => 10.00,   'estado' => 'activo',   'category_id' => $scifi->id],

            // Thriller (nivel 4)
            ['nombre' => 'La Chica del Tren - P. Hawkins','descripcion' => 'La Chica del Tren tapa blanda Planeta. Sin subrayados. Muy buen estado.',                                 'precio' => 8.00,    'estado' => 'activo',   'category_id' => $thriller->id],

            // Fantasía (nivel 4)
            ['nombre' => 'El Nombre del Viento - Rothfuss','descripcion' => 'El Nombre del Viento edición especial tapa dura Debolsillo. Como nuevo.',                                'precio' => 18.00,   'estado' => 'activo',   'category_id' => $fantasia->id],

            // Autoayuda (nivel 4)
            ['nombre' => 'Los 7 Hábitos - Stephen Covey','descripcion' => 'Los 7 Hábitos de la Gente Altamente Efectiva. Edición aniversario tapa dura. Pocas anotaciones.',          'precio' => 15.00,   'estado' => 'activo',   'category_id' => $autoayuda->id],

            // Negocios (nivel 4)
            ['nombre' => 'Cero a Uno - Peter Thiel',   'descripcion' => 'De Cero a Uno (Zero to One) en español. Planeta. Como nuevo, leído una vez.',                                'precio' => 12.00,   'estado' => 'activo',   'category_id' => $negocios->id],
            ['nombre' => 'El Juego Infinito - Simon Sinek','descripcion' => 'El Juego Infinito en español. Empresa Activa. Tapa blanda. Sin subrayados.',                              'precio' => 13.00,   'estado' => 'activo',   'category_id' => $negocios->id],
        ];

        // ═══════════════════════════════════════════════════════════════
        // IMÁGENES — una imagen por categoría, reutilizada en productos
        // ═══════════════════════════════════════════════════════════════
        // Keyword por categoría → loremflickr busca fotos reales de esa temática
        $imageKeywords = [
            $moviles->id      => 'smartphone',
            $portatiles->id   => 'laptop',
            $televisores->id  => 'television',
            $auriculares->id  => 'headphones',
            $ropaHombre->id   => 'menswear',
            $ropaMujer->id    => 'womenswear',
            $zapatillas->id   => 'sneakers',
            $complementos->id => 'handbag',
            $muebles->id      => 'furniture',
            $electrodom->id   => 'appliance',
            $decoracion->id   => 'homedecor',
            $ciclismo->id     => 'cycling',
            $running->id      => 'running',
            $fitness->id      => 'gym',
            $coches->id       => 'car',
            $motos->id        => 'motorcycle',
            $bicicletas->id   => 'scooter',
            $libroscat->id    => 'books',
            $videojuegos->id  => 'videogames',
            $musica->id       => 'instrument',
            $apple->id        => 'iphone',
            $samsung->id      => 'samsung',
            $xiaomi->id       => 'smartphone',
            $google->id       => 'pixel,phone',
            $oneplus->id      => 'smartphone',
            $macbooks->id     => 'macbook',
            $portGaming->id   => 'gaming,laptop',
            $ultrabook->id    => 'laptop',
            $aurWireless->id  => 'wireless,headphones',
            $aurGaming->id    => 'gaming,headset',
            $nikeCat->id      => 'nike',
            $adidasCat->id    => 'adidas',
            $nbCat->id        => 'sneakers',
            $rhCamisetas->id  => 'tshirt',
            $rhChaquetas->id  => 'jacket',
            $rhPantalones->id => 'jeans',
            $rmVestidos->id   => 'dress',
            $rmAbrigos->id    => 'coat',
            $mueblesOfic->id  => 'desk',
            $mueblesSalon->id => 'sofa',
            $mueblesDorm->id  => 'bedroom',
            $edCocina->id     => 'kitchen',
            $edLimpieza->id   => 'vacuum',
            $mtb->id          => 'mountain,bike',
            $carretera->id    => 'road,bicycle',
            $playstation->id  => 'playstation',
            $nintendo->id     => 'nintendo',
            $xbox->id         => 'xbox',
            $guitarras->id    => 'guitar',
            $pianos->id       => 'piano',
            $cascosMoto->id   => 'helmet',
            $ropaMoto->id     => 'motorcycle,jacket',
            $biciElect->id    => 'electric,bicycle',
            $zapRunning->id   => 'running,shoes',
            $relojesRun->id   => 'smartwatch',
            $pesas->id        => 'dumbbell',
            $yoga->id         => 'yoga',
            $novela->id       => 'novel,book',
            $noFiccion->id    => 'reading,book',
            $iphone14->id     => 'iphone',
            $iphone15->id     => 'iphone',
            $ipad->id         => 'ipad',
            $macAcc->id       => 'apple,keyboard',
            $galaxyS->id      => 'samsung,galaxy',
            $galaxyA->id      => 'samsung,phone',
            $nikeRun->id      => 'nike,running',
            $nikeLif->id      => 'nike,shoes',
            $nikeFut->id      => 'football,boots',
            $adidasRun->id    => 'adidas,running',
            $adidasOri->id    => 'adidas,shoes',
            $hardtail->id     => 'mountain,bike',
            $fullSusp->id     => 'mountain,bike',
            $carbono->id      => 'road,bicycle',
            $aluminio->id     => 'road,bicycle',
            $ps5cat->id       => 'playstation,5',
            $ps4cat->id       => 'playstation,4',
            $switchCat->id    => 'nintendo,switch',
            $switchAcc->id    => 'nintendo,controller',
            $guitElect->id    => 'electric,guitar',
            $guitAcus->id     => 'acoustic,guitar',
            $bajo->id         => 'bass,guitar',
            $portFPS->id      => 'gaming,laptop',
            $portRPG->id      => 'gaming,laptop',
            $escritorios->id  => 'desk,workspace',
            $sillas->id       => 'gaming,chair',
            $edCafe->id       => 'coffee,machine',
            $edFreidora->id   => 'airfryer',
            $mancuernas->id   => 'dumbbell',
            $kettlebells->id  => 'kettlebell',
            $scifi->id        => 'science,fiction',
            $thriller->id     => 'thriller,book',
            $fantasia->id     => 'fantasy,book',
            $autoayuda->id    => 'motivation,book',
            $negocios->id     => 'business,book',
        ];

        $imageDir = storage_path('app/public/productos/demo');
        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0755, true);
        }

        $imageCache = [];
        $uniqueKeywords = array_unique(array_values($imageKeywords));
        $this->command->info('Descargando ' . count($uniqueKeywords) . ' imágenes de demo...');
        foreach ($uniqueKeywords as $keyword) {
            $filename = 'demo-' . preg_replace('/[^a-z0-9]/', '-', $keyword) . '.jpg';
            $filepath = "{$imageDir}/{$filename}";
            if (!file_exists($filepath)) {
                $contents = @file_get_contents("https://loremflickr.com/600/400/{$keyword}");
                if ($contents) {
                    file_put_contents($filepath, $contents);
                }
            }
            if (file_exists($filepath)) {
                $imageCache[$keyword] = "productos/demo/{$filename}";
            }
        }

        $total = 0;
        $i = 0;
        $createdProducts = [];
        foreach ([$nivel2, $nivel3, $nivel4] as $grupo) {
            foreach ($grupo as $data) {
                $product = Product::create(array_merge($data, ['user_id' => $users[$i % count($users)]->id]));
                $createdProducts[] = $product;

                $keyword = $imageKeywords[$data['category_id']] ?? null;
                if ($keyword && isset($imageCache[$keyword])) {
                    ProductImage::create(['product_id' => $product->id, 'path' => $imageCache[$keyword]]);
                }

                $i++;
                $total++;
            }
        }

        // Likes: cada usuario da like a ~30 productos aleatorios que no sean suyos
        foreach ($users as $u) {
            $candidates = array_values(array_filter($createdProducts, fn($p) => $p->user_id !== $u->id));
            $keys = array_rand($candidates, min(30, count($candidates)));
            foreach ((array) $keys as $key) {
                $candidates[$key]->likedByUsers()->syncWithoutDetaching([$u->id]);
            }
        }

        // ═══════════════════════════════════════════════════════════════
        // CONVERSACIONES
        // ═══════════════════════════════════════════════════════════════
        $conversations = [
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola! Sigue disponible?'],
                    ['from' => 'seller', 'body' => 'Sí, sigue disponible! Tienes alguna pregunta?'],
                    ['from' => 'buyer',  'body' => 'Perfecto. Tiene algún golpe o arañazo que no salga en las fotos?'],
                    ['from' => 'seller', 'body' => 'No, está tal como se ve. Lo he cuidado mucho desde el primer día.'],
                    ['from' => 'buyer',  'body' => 'Genial. Harías algo en el precio? Podría llegar a 20€ menos.'],
                    ['from' => 'seller', 'body' => 'Por 10€ menos te lo dejo, así no pierdo mucho. Qué te parece?'],
                    ['from' => 'buyer',  'body' => 'Trato hecho! Cómo quedamos para verlo?'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Buenos días, me interesa mucho. Puedes hacer envío?'],
                    ['from' => 'seller', 'body' => 'Hola! Sí, sin problema. Por Correos o MRW, a tu elección. Los gastos van por tu cuenta.'],
                    ['from' => 'buyer',  'body' => 'Cuánto sería el envío aproximadamente?'],
                    ['from' => 'seller', 'body' => 'Unos 5-6€ dependiendo del peso. Te puedo hacer un paquete seguro con seguimiento.'],
                    ['from' => 'buyer',  'body' => 'Perfecto, me quedo con él. Cómo lo pagamos?'],
                    ['from' => 'seller', 'body' => 'Bizum o transferencia. Me mandas la dirección y te digo el total exacto.'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola, cuánto tiempo llevas usándolo?'],
                    ['from' => 'seller', 'body' => 'Hola! Unos 8 meses aproximadamente. Lo compré nuevo y lo he tratado muy bien.'],
                    ['from' => 'buyer',  'body' => 'Y la batería qué tal aguanta?'],
                    ['from' => 'seller', 'body' => 'Muy bien, aguanta un día entero sin problema con uso normal.'],
                    ['from' => 'buyer',  'body' => 'Ok, me lo pienso y te digo. Cuánto tiempo lo tienes publicado?'],
                    ['from' => 'seller', 'body' => 'Poco, lo subí esta semana. Hay bastante interés así que no creo que dure mucho.'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola! Aceptas intercambio?'],
                    ['from' => 'seller', 'body' => 'Depende de qué ofreces, cuéntame.'],
                    ['from' => 'buyer',  'body' => 'Tengo unas zapatillas Nike casi nuevas talla 42, o puedo compensar con dinero también.'],
                    ['from' => 'seller', 'body' => 'Gracias pero prefiero venta directa, necesito el dinero para otra cosa.'],
                    ['from' => 'buyer',  'body' => 'Entendido, sin problema. Puedo ir a verlo esta tarde?'],
                    ['from' => 'seller', 'body' => 'Claro, dime a qué hora y quedamos.'],
                    ['from' => 'buyer',  'body' => 'Sobre las 18h te va bien?'],
                    ['from' => 'seller', 'body' => 'Perfecto, te mando la dirección por aquí.'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Buenas! Está en perfecto funcionamiento?'],
                    ['from' => 'seller', 'body' => 'Sí, funciona al 100%. Lo puedes probar antes de llevártelo si quieres.'],
                    ['from' => 'buyer',  'body' => 'Genial, eso me da más confianza. Tienes la caja original?'],
                    ['from' => 'seller', 'body' => 'Sí, tengo caja, manual y todos los accesorios que venían de fábrica.'],
                    ['from' => 'buyer',  'body' => 'Me lo quedo. Puedes reservármelo hasta el finde?'],
                    ['from' => 'seller', 'body' => 'Te lo reservo hasta el sábado, sin problema.'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola, es el precio negociable?'],
                    ['from' => 'seller', 'body' => 'Un poco sí, cuánto ofrecías?'],
                    ['from' => 'buyer',  'body' => 'Podría llegar a 15€ menos del precio que pones.'],
                    ['from' => 'seller', 'body' => 'Eso es bastante, en 8€ menos te lo dejo y ya no bajo más.'],
                    ['from' => 'buyer',  'body' => 'Vale, cerramos en ese precio. Cuándo podemos quedar?'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola! Hay algún defecto que no aparezca en las fotos?'],
                    ['from' => 'seller', 'body' => 'Hola! No, las fotos son bastante fieles a la realidad. Está en muy buen estado.'],
                    ['from' => 'buyer',  'body' => 'Perfecto. Tienes factura de compra?'],
                    ['from' => 'seller', 'body' => 'Sí tengo el ticket de compra, aunque ya no está en garantía.'],
                    ['from' => 'buyer',  'body' => 'No importa, era por tener el histórico. Me lo llevo!'],
                    ['from' => 'seller', 'body' => 'Genial! Quedamos cuando quieras.'],
                ],
            ],
            [
                'messages' => [
                    ['from' => 'buyer',  'body' => 'Hola, sigue en venta?'],
                    ['from' => 'seller', 'body' => 'Sí! Acabo de revisarlo y todo perfecto.'],
                    ['from' => 'buyer',  'body' => 'Podrías hacer más fotos del estado real? Especialmente por detrás.'],
                    ['from' => 'seller', 'body' => 'Claro, te las mando ahora por aquí.'],
                    ['from' => 'buyer',  'body' => 'Muchas gracias! Con esas fotos me convence, lo quiero.'],
                ],
            ],
        ];

        // Asignamos cada conversación a un producto distinto con buyer ≠ seller
        $usedProductIds = [];
        $convCount = 0;
        foreach ($conversations as $conv) {
            // Buscar un producto que no tenga ya una conversación
            foreach ($createdProducts as $product) {
                if (in_array($product->id, $usedProductIds)) continue;

                $seller = $product->user_id;
                $buyer  = null;
                foreach ($users as $u) {
                    if ($u->id !== $seller) {
                        $buyer = $u->id;
                        break;
                    }
                }
                if (!$buyer) continue;
                $usedProductIds[] = $product->id;

                $now = now()->subMinutes(count($conv['messages']) * 5);
                foreach ($conv['messages'] as $msg) {
                    $senderId = $msg['from'] === 'buyer' ? $buyer : $seller;
                    Message::create([
                        'product_id'    => $product->id,
                        'thread_user_id'=> $buyer,
                        'sender_id'     => $senderId,
                        'body'          => $msg['body'],
                        'read_at'       => $msg['from'] === 'buyer' ? now() : null,
                        'created_at'    => $now,
                    ]);
                    $now->addMinutes(5);
                }
                $convCount++;
                break;
            }
        }

        $this->command->info("✓ {$convCount} conversaciones de demo creadas.");
        $this->command->info("✓ {$total} productos de demo creados correctamente.");
        $this->command->info('  — Nivel 2 (subcategoría):       ' . count($nivel2) . ' productos');
        $this->command->info('  — Nivel 3 (marca/tipo):         ' . count($nivel3) . ' productos');
        $this->command->info('  — Nivel 4 (modelo/especialidad):' . count($nivel4) . ' productos');
    }
}
