<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Ultracel</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=1">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Readex Pro', sans-serif; 
            background-color: #111827 !important;
        }
        
        /* Forzar inputs al lado oscuro */
        input[type="email"], input[type="password"] {
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

    <div class="max-w-4xl w-full flex flex-col md:flex-row bg-gray-900 rounded-3xl shadow-2xl overflow-hidden border border-gray-700">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-gray-800 to-gray-900 border-r border-gray-700 p-12 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10 hover:opacity-80 transition outline-none">
                    <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="text-3xl font-extrabold text-white tracking-wider">ULTRA<span class="text-blue-500">CEL</span></span>
                </a>
                
                <h2 class="text-4xl font-bold text-white mb-6 leading-tight">
                    Bienvenido de vuelta al mando.
                </h2>
                <p class="text-gray-400 text-lg leading-relaxed font-medium">
                    Tu taller te espera. Ingresa a tu panel gerencial para revisar tus analíticas, gestionar licencias y administrar a tu equipo de técnicos.
                </p>
            </div>
            
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-blue-600 opacity-10 rounded-full blur-[80px] pointer-events-none"></div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-purple-600 opacity-10 rounded-full blur-[60px] pointer-events-none"></div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-14 flex flex-col justify-center bg-gray-900 relative z-20">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-bold text-white mb-2">Iniciar Sesión</h3>
                <p class="text-gray-400 text-sm font-medium">Ingresa tus credenciales de acceso.</p>
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

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-bold text-gray-300 mb-2">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="admin@taller.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-300 mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 transition-colors shadow-inner" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-gray-600 bg-gray-800 text-blue-500 shadow-sm focus:ring-blue-500 w-4 h-4">
                        <span class="ml-2 text-sm text-gray-400 font-semibold">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-blue-500 hover:text-blue-400 transition-colors" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white font-bold text-lg py-3.5 rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-500 transition-all transform hover:-translate-y-1 border border-blue-500">
                    Ingresar al Panel
                </button>

                <p class="text-center text-sm text-gray-400 mt-8 font-medium border-t border-gray-800 pt-6">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-blue-500 font-bold hover:text-blue-400 hover:underline transition-colors">Registra tu taller aquí</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>