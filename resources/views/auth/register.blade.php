<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrar Taller | Ultracel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Readex Pro', sans-serif; 
            background-color: #111827 !important; /* Gris muy oscuro */
            color: #D1D5DB !important; 
        }
        
        /* Forzar inputs al lado oscuro */
        input[type="text"], input[type="email"], input[type="password"] {
            background-color: #1F2937 !important;
            border: 1px solid #374151 !important;
            color: #FFFFFF !important;
            border-radius: 0.75rem !important;
        }
        input:focus { 
            border-color: #3B82F6 !important; 
            box-shadow: 0 0 0 1px #3B82F6 !important; 
            outline: none !important;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex items-center justify-center p-4 selection:bg-blue-500 selection:text-white">

    <div class="max-w-5xl w-full flex flex-col md:flex-row bg-gray-900 rounded-3xl shadow-2xl overflow-hidden border border-gray-700">
        
        <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-gray-800 to-gray-900 border-r border-gray-700 p-10 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10 hover:opacity-80 transition outline-none">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ultracel" class="h-10 brightness-200 grayscale">
                    <span class="text-3xl font-extrabold text-white tracking-wider">ULTRA<span class="text-blue-500">CEL</span></span>
                </a>
                
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight">
                    Digitaliza tu taller hoy mismo.
                </h2>
                <p class="text-gray-400 text-sm leading-relaxed font-medium mb-8">
                    Crea tu cuenta y obtén acceso inmediato al ecosistema 3 en 1. Sin contratos forzosos y sin ingresar tarjeta de crédito.
                </p>

                <ul class="space-y-5 text-gray-300 text-sm font-medium">
                    <li class="flex items-center gap-3">
                        <div class="bg-green-900/50 border border-green-500 rounded-full p-1.5 shadow-inner">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        15 días de prueba 100% gratuita
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-blue-900/50 border border-blue-500 rounded-full p-1.5 shadow-inner">
                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        Software de Escritorio para Mostrador
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-purple-900/50 border border-purple-500 rounded-full p-1.5 shadow-inner">
                            <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                        </div>
                        Panel Web Gerencial de Analíticas
                    </li>
                    <li class="flex items-center gap-3">
                        <div class="bg-green-900/50 border border-green-500 rounded-full p-1.5 shadow-inner">
                            <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        App Móvil exclusiva para tus Clientes
                    </li>
                </ul>
            </div>
            
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-blue-600 opacity-10 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="absolute top-20 -right-20 w-52 h-52 bg-purple-600 opacity-10 rounded-full blur-[60px] pointer-events-none"></div>
        </div>

        <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-gray-900 relative z-20">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-bold text-white mb-2">Crea tu cuenta</h3>
                <p class="text-gray-400 text-sm font-medium">Solo te tomará un minuto configurar tu nuevo negocio.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-900/30 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <ul class="list-disc list-inside text-sm text-red-400 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-300 mb-1">Tu Nombre (Responsable del Taller)</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="Ej. Emiliano">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-300 mb-1">Correo Electrónico del Taller</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="contacto@mitaller.com">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-bold text-gray-300 mb-1">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="Mínimo 8 caracteres">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-gray-300 mb-1">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="Repite tu contraseña">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 mt-4 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2 border border-blue-500">
                    Comenzar Mis 15 Días Gratis
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </button>
                
                <p class="text-xs text-center text-gray-500 font-medium mt-3">
                    Al registrarte, aceptas nuestros <a href="#" class="text-blue-400 hover:text-blue-300 hover:underline transition">Términos de Servicio</a> y <a href="#" class="text-blue-400 hover:text-blue-300 hover:underline transition">Aviso de Privacidad</a>.
                </p>

                <p class="text-center text-sm text-gray-400 mt-6 font-medium border-t border-gray-800 pt-5">
                    ¿Ya tienes un taller registrado? 
                    <a href="{{ route('login') }}" class="text-blue-500 font-bold hover:text-blue-400 hover:underline transition-colors">Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>