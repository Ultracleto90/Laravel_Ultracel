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
        body { font-family: 'Readex Pro', sans-serif; }
    </style>
</head>
<body class="bg-blanco-azulado text-azul-oscuro antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-5xl w-full flex flex-col md:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden border border-azul-muy-claro">
        
        <div class="hidden md:flex md:w-5/12 bg-gradient-to-br from-azul-oscuro to-azul-medio p-10 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10 hover:opacity-80 transition">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ultracel" class="h-10 brightness-200 grayscale">
                    <span class="text-3xl font-bold text-white tracking-tight">Ultracel</span>
                </a>
                
                <h2 class="text-3xl font-bold text-white mb-4 leading-tight">
                    Digitaliza tu taller hoy mismo.
                </h2>
                <p class="text-azul-claro text-sm leading-relaxed font-medium mb-8">
                    Crea tu cuenta y obtén acceso inmediato al ecosistema 3 en 1. Sin contratos forzosos y sin ingresar tarjeta de crédito.
                </p>

                <ul class="space-y-4 text-white text-sm font-medium">
                    <li class="flex items-center gap-3">
                        <span class="bg-green-500 text-white rounded-full p-1.5 text-xs shadow-sm">🎁</span> 
                        15 días de prueba 100% gratuita
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="bg-white/20 rounded-full p-1.5 text-xs">💻</span> 
                        Software de Escritorio para Mostrador
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="bg-white/20 rounded-full p-1.5 text-xs">🌐</span> 
                        Panel Web Gerencial de Analíticas
                    </li>
                    <li class="flex items-center gap-3">
                        <span class="bg-white/20 rounded-full p-1.5 text-xs">📱</span> 
                        App Móvil exclusiva para tus Clientes
                    </li>
                </ul>
            </div>
            
            <div class="absolute -bottom-32 -left-32 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute top-20 -right-20 w-52 h-52 bg-azul-claro opacity-20 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-center bg-white relative z-20">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-bold text-azul-oscuro mb-2">Crea tu cuenta</h3>
                <p class="text-azul-medio text-sm font-medium">Solo te tomará un minuto configurar tu nuevo negocio.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm">
                    <ul class="list-disc list-inside text-sm text-red-600 font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                
                <div>
                    <label for="name" class="block text-sm font-bold text-azul-oscuro mb-1">Tu Nombre (Administrador)</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                        class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="Ej. Emiliano">
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-azul-oscuro mb-1">Correo Electrónico del Taller</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                        class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="contacto@mitaller.com">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="block text-sm font-bold text-azul-oscuro mb-1">Contraseña</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="Mínimo 8 caracteres">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-azul-oscuro mb-1">Confirmar Contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                            class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="Repite tu contraseña">
                    </div>
                </div>

                <button type="submit" class="w-full bg-azul-oscuro text-white font-bold text-lg py-3.5 mt-4 rounded-xl shadow-lg hover:bg-azul-medio transition-all transform hover:-translate-y-1 flex justify-center items-center gap-2">
                    Comenzar Mis 15 Días Gratis <span class="text-xl">🚀</span>
                </button>
                
                <p class="text-xs text-center text-azul-medio font-medium mt-3">
                    Al registrarte, aceptas nuestros <a href="#" class="text-azul-oscuro hover:underline">Términos de Servicio</a> y <a href="#" class="text-azul-oscuro hover:underline">Aviso de Privacidad</a>.
                </p>

                <p class="text-center text-sm text-azul-medio mt-6 font-medium border-t border-azul-muy-claro/50 pt-5">
                    ¿Ya tienes un taller registrado? 
                    <a href="{{ route('login') }}" class="text-azul-oscuro font-bold hover:underline transition-colors">Inicia sesión aquí</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>