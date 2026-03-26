<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ultracel | El Ecosistema para tu Taller</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Readex Pro', sans-serif; }
    </style>
</head>
<body class="text-azul-oscuro bg-blanco-azulado antialiased relative min-h-screen flex flex-col">

    <div class="fixed inset-0 z-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
        <img src="{{ asset('images/logo.png') }}" alt="Ultracel" class="w-2/3 md:w-1/3 grayscale">
    </div>

    <div class="relative z-10 flex-grow">
        <header class="bg-blanco-azulado/95 backdrop-blur-sm shadow-sm fixed w-full top-0 z-50 border-b border-azul-muy-claro">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ultracel" class="h-10">
                    <span class="text-2xl font-bold text-azul-oscuro tracking-tight">Ultracel</span>
                </div>
                
                <nav class="hidden md:flex gap-8">
                    <a href="#servicios" class="text-azul-medio hover:text-azul-oscuro font-medium transition-colors">Servicios</a>
                    <a href="#nosotros" class="text-azul-medio hover:text-azul-oscuro font-medium transition-colors">Nosotros</a>
                    <a href="#planes" class="text-azul-medio hover:text-azul-oscuro font-medium transition-colors">Planes</a>
                </nav>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-azul-oscuro text-white text-sm font-semibold rounded-lg hover:bg-azul-medio transition shadow-sm">
                                Mi Panel de Control
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-sm font-semibold text-azul-oscuro hover:bg-azul-muy-claro/50 rounded-lg transition">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-azul-medio text-white text-sm font-semibold rounded-lg hover:bg-azul-oscuro transition shadow-sm">
                                    Crear Cuenta
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </header>

        <section class="pt-40 pb-20 px-6 text-center">
            <div class="max-w-4xl mx-auto">
                <span class="inline-block bg-azul-muy-claro/50 text-azul-oscuro font-semibold px-4 py-1 rounded-full text-sm mb-6 border border-azul-claro/50">
                    🚀 Potenciando talleres en todo México
                </span>
                
                <h1 class="text-5xl md:text-6xl font-bold tracking-tight mb-6 leading-tight">
                    El sistema definitivo para <br>
                    <span class="text-azul-medio">administrar tu taller.</span>
                </h1>
                
                <p class="text-lg md:text-xl text-azul-medio/90 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Controla tus reparaciones, gestiona tu inventario y fideliza a tus clientes con nuestro ecosistema 3 en 1.
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-azul-oscuro text-white font-semibold text-lg py-3 px-8 rounded-xl shadow-lg hover:bg-azul-medio transition transform hover:-translate-y-1">
                            Ir a mi cuenta
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="bg-transparent border-2 border-azul-oscuro text-azul-oscuro font-semibold text-lg py-3 px-8 rounded-xl hover:bg-azul-muy-claro/30 transition">
                            Iniciar Sesión
                        </a>
                        
                    @endauth
                </div>
            </div>
        </section>

        <section id="servicios" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-azul-oscuro">Un Ecosistema Completo a tu Disposición</h2>
                    <p class="mt-4 text-azul-medio">Todo lo que necesitas, sincronizado en tiempo real.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-blanco-azulado border border-azul-muy-claro rounded-2xl p-8 hover:shadow-xl transition duration-300 relative">
                        <div class="w-14 h-14 bg-azul-oscuro text-white rounded-xl flex items-center justify-center text-2xl mb-6 shadow-md">💻</div>
                        <h3 class="text-xl font-bold mb-3">Software de Escritorio</h3>
                        <p class="text-azul-medio text-sm leading-relaxed mb-4">El corazón de tu mostrador. Punto de venta, creación rápida de tickets de reparación y control total de tu inventario de refacciones.</p>
                        <span class="inline-block bg-azul-oscuro text-white text-xs font-bold px-3 py-1 rounded-full">Licencia Base</span>
                    </div>

                    <div class="bg-blanco-azulado border border-azul-muy-claro rounded-2xl p-8 hover:shadow-xl transition duration-300 relative">
                        <div class="w-14 h-14 bg-azul-medio text-white rounded-xl flex items-center justify-center text-2xl mb-6 shadow-md">🌐</div>
                        <h3 class="text-xl font-bold mb-3">Panel Web Gerencial</h3>
                        <p class="text-azul-medio text-sm leading-relaxed mb-4">Supervisa tu negocio desde cualquier lugar. Analíticas de ganancias, administración de técnicos y gestión de sucursales en la nube.</p>
                        <span class="inline-block bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Incluido Gratis</span>
                    </div>

                    <div class="bg-blanco-azulado border border-azul-muy-claro rounded-2xl p-8 hover:shadow-xl transition duration-300 relative">
                        <div class="w-14 h-14 bg-azul-claro text-azul-oscuro rounded-xl flex items-center justify-center text-2xl mb-6 shadow-md">📱</div>
                        <h3 class="text-xl font-bold mb-3">App Móvil para Clientes</h3>
                        <p class="text-azul-medio text-sm leading-relaxed mb-4">Dale estatus a tu taller. Tus clientes podrán revisar el estado de su equipo, leer diagnósticos y recibir notificaciones en su celular.</p>
                        <span class="inline-block bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">Incluido Gratis</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="nosotros" class="py-20 bg-azul-muy-claro/20 border-y border-azul-muy-claro/50">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-bold text-azul-oscuro">Desarrollado por técnicos, para técnicos</h2>
                    <p class="mt-4 text-azul-medio max-w-2xl mx-auto">No somos una corporación genérica. Nacimos en un mostrador de reparación y sabemos exactamente qué dolores de cabeza quitarte.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-azul-muy-claro">
                        <h3 class="text-xl font-bold text-azul-oscuro mb-4 flex items-center gap-2">🎯 Nuestra Misión</h3>
                        <p class="text-azul-medio text-sm leading-relaxed">Empoderar a los talleres de reparación dotándolos de software accesible, intuitivo y potente que democratice la administración profesional tecnológica.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-azul-muy-claro">
                        <h3 class="text-xl font-bold text-azul-oscuro mb-4 flex items-center gap-2">👁️ Nuestra Visión</h3>
                        <p class="text-azul-medio text-sm leading-relaxed">Convertirnos en el estándar de la industria y el ecosistema tecnológico de máxima confianza para los negocios de reparación a nivel internacional.</p>
                    </div>
                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-azul-muy-claro">
                        <h3 class="text-xl font-bold text-azul-oscuro mb-4 flex items-center gap-2">💎 Nuestros Valores</h3>
                        <p class="text-azul-medio text-sm leading-relaxed">Innovación constante, transparencia absoluta en nuestros servicios, empatía con las necesidades del técnico y calidad total en cada línea de código.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="planes" class="py-24 bg-blanco-azulado border-t border-azul-muy-claro">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-azul-oscuro mb-4">Planes diseñados para crecer contigo</h2>
                    <p class="text-xl text-azul-medio">Comienza con <span class="font-bold text-azul-oscuro">15 días de prueba gratuita</span> en cualquier plan. Elige tu ahorro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                    <div class="bg-white rounded-3xl p-8 border border-azul-muy-claro shadow-sm hover:shadow-xl transition duration-300 relative">
                        <h3 class="text-xl font-bold text-azul-oscuro mb-2">Mensual</h3>
                        <p class="text-azul-medio text-sm mb-6">Flexibilidad total para tu negocio.</p>
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-azul-oscuro">$150</span>
                            <span class="text-azul-medio font-medium">/mes</span>
                        </div>
                        <ul class="space-y-4 text-sm text-azul-oscuro font-medium mb-8">
                            <li class="flex items-center gap-3"><span class="bg-green-100 text-green-600 rounded-full p-1 text-xs">✔️</span> 15 días de prueba gratis</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> App de Escritorio</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Panel Web Gerencial</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> App Móvil de Clientes</li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white border-2 border-azul-oscuro text-azul-oscuro font-bold py-3 rounded-xl hover:bg-azul-muy-claro transition">Probar 15 días gratis</a>
                    </div>

                    <div class="bg-azul-oscuro rounded-3xl p-8 shadow-2xl transform md:-translate-y-6 relative border-4 border-azul-medio z-10">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow-lg whitespace-nowrap animate-pulse">
                            MÁS POPULAR / MEJOR VALOR
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 mt-2">Anual</h3>
                        <p class="text-azul-claro text-sm mb-6">Ahorra $600 pesos al año.</p>
                        <div class="mb-2">
                            <span class="text-5xl font-extrabold text-white">$100</span>
                            <span class="text-azul-claro font-medium">/mes</span>
                        </div>
                        <p class="text-azul-muy-claro text-xs mb-6 font-medium">Facturado anualmente ($1,200)</p>
                        <ul class="space-y-4 text-sm text-blanco-azulado font-medium mb-8">
                            <li class="flex items-center gap-3"><span class="bg-green-500 text-white rounded-full p-1 text-xs">✔️</span> 15 días de prueba gratis</li>
                            <li class="flex items-center gap-3"><span class="bg-azul-medio text-white rounded-full p-1 text-xs">✔️</span> App de Escritorio</li>
                            <li class="flex items-center gap-3"><span class="bg-azul-medio text-white rounded-full p-1 text-xs">✔️</span> Panel Web Gerencial</li>
                            <li class="flex items-center gap-3"><span class="bg-azul-medio text-white rounded-full p-1 text-xs">✔️</span> App Móvil de Clientes</li>
                            <li class="flex items-center gap-3"><span class="bg-yellow-400 text-white rounded-full p-1 text-xs">⭐</span> Soporte prioritario</li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white text-azul-oscuro font-bold py-4 rounded-xl hover:bg-azul-muy-claro transition transform hover:scale-105 shadow-lg">Comenzar Prueba Gratis</a>
                    </div>

                    <div class="bg-white rounded-3xl p-8 border border-azul-muy-claro shadow-sm hover:shadow-xl transition duration-300 relative">
                        <h3 class="text-xl font-bold text-azul-oscuro mb-2">Semestral</h3>
                        <p class="text-azul-medio text-sm mb-6">El equilibrio perfecto.</p>
                        <div class="mb-2">
                            <span class="text-4xl font-extrabold text-azul-oscuro">$125</span>
                            <span class="text-azul-medio font-medium">/mes</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-6 font-medium">Facturado cada 6 meses ($750)</p>
                        <ul class="space-y-4 text-sm text-azul-oscuro font-medium mb-8">
                            <li class="flex items-center gap-3"><span class="bg-green-100 text-green-600 rounded-full p-1 text-xs">✔️</span> 15 días de prueba gratis</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> App de Escritorio</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Panel Web Gerencial</li>
                            <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> App Móvil de Clientes</li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-white border-2 border-azul-oscuro text-azul-oscuro font-bold py-3 rounded-xl hover:bg-azul-muy-claro transition">Probar 15 días gratis</a>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <footer class="bg-gray-900 text-gray-300 pt-16 pb-8 border-t-8 border-azul-medio relative z-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ultracel" class="h-8 grayscale brightness-200">
                    <span class="text-2xl font-bold text-white tracking-tight">Ultracel</span>
                </div>
                <p class="text-sm text-gray-400 mb-4 leading-relaxed">El estándar tecnológico para talleres de reparación de dispositivos móviles y de cómputo.</p>
                <div class="text-sm text-gray-400 space-y-2">
                    <p>📍 Xonacatlán, Estado de México</p>
                    <p>📧 contacto@ultracel.lat</p>
                </div>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Mapa de Sitio</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition">Inicio</a></li>
                    <li><a href="#servicios" class="hover:text-white transition">El Ecosistema 3 en 1</a></li>
                    <li><a href="#nosotros" class="hover:text-white transition">Misión y Filosofía</a></li>
                    <li><a href="#planes" class="hover:text-white transition">Planes y Precios</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Accesos</h4>
                <ul class="space-y-3 text-sm">
                    @auth
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-white transition">Ir a Mi Panel</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Iniciar Sesión</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Registrar un Taller</a></li>
                    @endauth
                    <li><a href="#" class="hover:text-white transition">Descargar App de Escritorio</a></li>
                    <li><a href="#" class="hover:text-white transition">Descargar App Móvil</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-white font-bold mb-6 uppercase tracking-wider text-sm">Legal</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-white transition">Términos y Condiciones</a></li>
                    <li><a href="#" class="hover:text-white transition">Aviso de Privacidad</a></li>
                    <li><a href="#" class="hover:text-white transition">Acuerdo de Nivel de Servicio</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-500 px-6">
            <p>&copy; {{ date('Y') }} Ultracel Software. Todos los derechos reservados.</p>
            <p class="mt-2 md:mt-0">Desarrollado con pasión en México 🇲🇽</p>
        </div>
    </footer>

</body>
</html>