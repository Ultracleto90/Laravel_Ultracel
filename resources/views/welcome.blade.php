<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ultracel | El Ecosistema para tu Taller</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=1">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body { 
            font-family: 'Readex Pro', sans-serif; 
            background-color: #111827 !important; /* Gris muy oscuro, casi negro */
            color: #D1D5DB !important; 
            margin: 0;
            padding: 0;
            scroll-behavior: smooth;
        }
        /* Para que las secciones no tomen fondos blancos por accidente */
        section, nav, header, footer { background-color: transparent; }
        .bg-white { background-color: #1F2937 !important; border-color: #374151 !important;}
        .text-gray-900 { color: #F3F4F6 !important; }
    </style>
</head>
<body class="antialiased relative min-h-screen flex flex-col selection:bg-blue-500 selection:text-white">

    <div class="fixed inset-0 z-0 flex items-center justify-center pointer-events-none">
        <div class="w-[800px] h-[800px] bg-blue-600/10 rounded-full blur-[100px] opacity-40"></div>
    </div>

    <div class="relative z-10 flex-grow">
        
        <header class="bg-[#111827]/90 backdrop-blur-md shadow-sm fixed w-full top-0 z-50 border-b border-gray-800">
            <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
                
                <div class="flex items-center gap-3">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-2xl font-extrabold text-white tracking-wider">ULTRA<span class="text-blue-500">CEL</span></span>
                </div>
                
                <nav class="hidden md:flex gap-8">
                    <a href="#servicios" class="text-gray-300 hover:text-white font-medium transition-colors">Servicios</a>
                    <a href="#nosotros" class="text-gray-300 hover:text-white font-medium transition-colors">Nosotros</a>
                    <a href="#planes" class="text-gray-300 hover:text-white font-medium transition-colors">Planes</a>
                </nav>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-500 transition shadow-lg shadow-blue-500/30">
                                Mi Panel de Control
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="hidden sm:block px-4 py-2 text-sm font-bold text-gray-300 hover:text-white transition">
                                Iniciar Sesión
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-500 transition shadow-lg shadow-blue-500/30">
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
                <span class="inline-flex items-center gap-2 bg-gray-800 text-gray-200 font-semibold px-4 py-1.5 rounded-full text-sm mb-6 border border-gray-700 shadow-inner">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Potenciando talleres en todo México
                </span>
                
                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight mb-6 leading-tight text-white">
                    El sistema definitivo para <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-500">administrar tu taller.</span>
                </h1>
                
                <p class="text-lg md:text-xl text-gray-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                    Controla tus reparaciones, gestiona tu inventario y fideliza a tus clientes con nuestro ecosistema de software 3 en 1.
                </p>
                
                <div class="flex flex-wrap justify-center gap-4 items-center">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="bg-blue-600 text-white font-bold text-lg py-3.5 px-8 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition transform hover:-translate-y-1">
                            Ir a mi cuenta
                        </a>
                    @else
                        <a href="{{ route('register') }}" class="bg-blue-600 text-white font-bold text-lg py-3.5 px-8 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition transform hover:-translate-y-1 flex items-center gap-2">
                            Comenzar Prueba Gratis
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                        <a href="{{ route('login') }}" class="bg-gray-800 border border-gray-600 text-white font-bold text-lg py-3.5 px-8 rounded-xl hover:bg-gray-700 transition">
                            Iniciar Sesión
                        </a>
                    @endauth
                </div>
            </div>
        </section>

        <section id="servicios" class="py-24 bg-[#1F2937]/30 border-y border-gray-800 relative">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-extrabold text-white">Un Ecosistema Completo a tu Disposición</h2>
                    <p class="mt-4 text-gray-400 font-medium">Todo lo que necesitas, sincronizado en tiempo real.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-800 border border-gray-700 rounded-3xl p-8 hover:border-gray-600 transition duration-300 shadow-lg group">
                        <div class="w-14 h-14 bg-blue-900/50 border border-blue-500 text-blue-400 rounded-xl flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Software de Escritorio</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">El corazón de tu mostrador. Punto de venta, creación rápida de tickets de reparación y control total de tu inventario de refacciones.</p>
                        <span class="inline-block bg-gray-900 border border-gray-700 text-gray-300 text-xs font-bold px-3 py-1.5 rounded-full">Licencia Base</span>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-3xl p-8 hover:border-gray-600 transition duration-300 shadow-lg group">
                        <div class="w-14 h-14 bg-purple-900/50 border border-purple-500 text-purple-400 rounded-xl flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">Panel Web Gerencial</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">Supervisa tu negocio desde cualquier lugar. Analíticas de ganancias, administración de técnicos y gestión de sucursales en la nube.</p>
                        <span class="inline-block bg-green-900/50 border border-green-700 text-green-400 text-xs font-bold px-3 py-1.5 rounded-full">Incluido Gratis</span>
                    </div>

                    <div class="bg-gray-800 border border-gray-700 rounded-3xl p-8 hover:border-gray-600 transition duration-300 shadow-lg group">
                        <div class="w-14 h-14 bg-green-900/50 border border-green-500 text-green-400 rounded-xl flex items-center justify-center mb-6 shadow-inner group-hover:scale-110 transition">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-3">App Móvil (Técnicos)</h3>
                        <p class="text-gray-400 text-sm leading-relaxed mb-6">Movilidad total. Tus técnicos pueden diagnosticar equipos, actualizar estados y solicitar material al administrador directamente desde su bolsillo.</p>
                        <span class="inline-block bg-green-900/50 border border-green-700 text-green-400 text-xs font-bold px-3 py-1.5 rounded-full">Incluido Gratis</span>
                    </div>
                </div>
            </div>
        </section>

        <section id="nosotros" class="py-24 bg-[#111827]">
            <div class="max-w-7xl mx-auto px-6">
                <div class="text-center mb-16">
                    <h2 class="text-3xl font-extrabold text-white">Desarrollado por técnicos, para técnicos</h2>
                    <p class="mt-4 text-gray-400 max-w-2xl mx-auto font-medium">No somos una corporación genérica. Nacimos en un mostrador de reparación y sabemos exactamente qué dolores de cabeza quitarte.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="bg-gray-800 p-8 rounded-3xl shadow-lg border border-gray-700 hover:border-gray-600 transition">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Nuestra Misión
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Empoderar a los talleres de reparación dotándolos de software accesible, intuitivo y potente que democratice la administración profesional tecnológica.</p>
                    </div>
                    <div class="bg-gray-800 p-8 rounded-3xl shadow-lg border border-gray-700 hover:border-gray-600 transition">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            Nuestra Visión
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Convertirnos en el estándar de la industria y el ecosistema tecnológico de máxima confianza para los negocios de reparación a nivel internacional.</p>
                    </div>
                    <div class="bg-gray-800 p-8 rounded-3xl shadow-lg border border-gray-700 hover:border-gray-600 transition">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Nuestros Valores
                        </h3>
                        <p class="text-gray-400 text-sm leading-relaxed">Innovación constante, transparencia absoluta en nuestros servicios, empatía con las necesidades del técnico y calidad total en cada línea de código.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="planes" class="py-24 bg-[#1F2937]/30 border-t border-gray-800 relative">
            <div class="max-w-7xl mx-auto px-6 relative z-10">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-extrabold text-white mb-4">Planes diseñados para crecer contigo</h2>
                    <p class="text-xl text-gray-400">Comienza con <span class="font-bold text-white">15 días de prueba gratuita</span> en cualquier plan. Elige tu ahorro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                    
                    <div class="bg-gray-800 rounded-3xl p-8 border border-gray-700 shadow-lg hover:border-gray-600 transition duration-300">
                        <h3 class="text-xl font-bold text-white mb-2">Mensual</h3>
                        <p class="text-gray-400 text-sm mb-6">Flexibilidad total para tu negocio.</p>
                        <div class="mb-6">
                            <span class="text-4xl font-extrabold text-white">$150</span>
                            <span class="text-gray-500 font-medium">/mes</span>
                        </div>
                        <ul class="space-y-4 text-sm text-gray-300 font-medium mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                15 días de prueba gratis
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App de Escritorio
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Panel Web Gerencial
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App Móvil de Clientes
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-gray-900 border border-gray-600 text-white font-bold py-3 rounded-xl hover:bg-gray-700 transition">Probar 15 días gratis</a>
                    </div>

                    <div class="bg-gray-900 rounded-3xl p-8 shadow-2xl transform md:-translate-y-6 relative border-2 border-blue-500 z-20">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-blue-600 text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-lg shadow-blue-500/50 tracking-widest uppercase">
                            Más Popular / Mejor Valor
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2 mt-2">Anual</h3>
                        <p class="text-blue-400 text-sm mb-6 font-medium">Ahorra $600 pesos al año.</p>
                        <div class="mb-2">
                            <span class="text-5xl font-extrabold text-white">$100</span>
                            <span class="text-gray-400 font-medium">/mes</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-6 font-medium">Facturado anualmente ($1,200)</p>
                        <ul class="space-y-4 text-sm text-gray-200 font-medium mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                15 días de prueba gratis
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App de Escritorio
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Panel Web Gerencial
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App Móvil de Clientes
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                Soporte prioritario
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-500 transition shadow-lg shadow-blue-500/30">Comenzar Prueba Gratis</a>
                    </div>

                    <div class="bg-gray-800 rounded-3xl p-8 border border-gray-700 shadow-lg hover:border-gray-600 transition duration-300">
                        <h3 class="text-xl font-bold text-white mb-2">Semestral</h3>
                        <p class="text-gray-400 text-sm mb-6">El equilibrio perfecto.</p>
                        <div class="mb-2">
                            <span class="text-4xl font-extrabold text-white">$125</span>
                            <span class="text-gray-500 font-medium">/mes</span>
                        </div>
                        <p class="text-gray-500 text-xs mb-6 font-medium">Facturado cada 6 meses ($750)</p>
                        <ul class="space-y-4 text-sm text-gray-300 font-medium mb-8">
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                15 días de prueba gratis
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App de Escritorio
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Panel Web Gerencial
                            </li>
                            <li class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                App Móvil de Clientes
                            </li>
                        </ul>
                        <a href="{{ route('register') }}" class="block w-full text-center bg-gray-900 border border-gray-600 text-white font-bold py-3 rounded-xl hover:bg-gray-700 transition">Probar 15 días gratis</a>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <footer class="bg-[#0B1120] text-gray-400 pt-16 pb-8 border-t border-gray-800 relative z-10">
        <div class="max-w-7xl mx-auto px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
            
            <div>
                <div class="flex items-center gap-3 mb-6">
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-2xl font-extrabold text-white tracking-wider">ULTRA<span class="text-blue-500">CEL</span></span>
                </div>
                <p class="text-sm text-gray-500 mb-4 leading-relaxed">El estándar tecnológico para talleres de reparación de dispositivos móviles y de cómputo.</p>
                <div class="text-sm text-gray-500 space-y-2 flex flex-col">
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Xonacatlán, Estado de México
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        contacto@ultracel.lat
                    </span>
                </div>
            </div>
            
            <div>
                <h4 class="text-gray-200 font-bold mb-6 uppercase tracking-wider text-sm">Mapa de Sitio</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-blue-400 transition">Inicio</a></li>
                    <li><a href="#servicios" class="hover:text-blue-400 transition">El Ecosistema 3 en 1</a></li>
                    <li><a href="#nosotros" class="hover:text-blue-400 transition">Misión y Filosofía</a></li>
                    <li><a href="#planes" class="hover:text-blue-400 transition">Planes y Precios</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-gray-200 font-bold mb-6 uppercase tracking-wider text-sm">Accesos</h4>
                <ul class="space-y-3 text-sm">
                    @auth
                        <li><a href="{{ url('/dashboard') }}" class="hover:text-blue-400 transition">Ir a Mi Panel</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-blue-400 transition">Iniciar Sesión</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-blue-400 transition">Registrar un Taller</a></li>
                    @endauth
                    <li><a href="#" class="hover:text-blue-400 transition">Descargar App de Escritorio</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Descargar App Móvil</a></li>
                </ul>
            </div>
            
            <div>
                <h4 class="text-gray-200 font-bold mb-6 uppercase tracking-wider text-sm">Legal</h4>
                <ul class="space-y-3 text-sm">
                    <li><a href="#" class="hover:text-blue-400 transition">Términos y Condiciones</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Aviso de Privacidad</a></li>
                    <li><a href="#" class="hover:text-blue-400 transition">Acuerdo de Nivel de Servicio</a></li>
                </ul>
            </div>
        </div>
        
        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs text-gray-600 px-6">
            <p>&copy; {{ date('Y') }} Ultracel Software. Todos los derechos reservados.</p>
            <p class="mt-2 md:mt-0">Desarrollado con pasión en México</p>
        </div>
    </footer>

</body>
</html>