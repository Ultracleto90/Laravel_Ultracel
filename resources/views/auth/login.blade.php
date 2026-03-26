<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar Sesión | Ultracel</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Readex Pro', sans-serif; }
    </style>
</head>
<body class="bg-blanco-azulado text-azul-oscuro antialiased min-h-screen flex items-center justify-center p-4">

    <div class="max-w-4xl w-full flex flex-col md:flex-row bg-white rounded-3xl shadow-2xl overflow-hidden border border-azul-muy-claro">
        
        <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-azul-oscuro to-azul-medio p-12 flex-col justify-between relative overflow-hidden">
            <div class="relative z-10">
                <a href="/" class="flex items-center gap-3 mb-10 hover:opacity-80 transition">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Ultracel" class="h-10 brightness-200 grayscale">
                    <span class="text-3xl font-bold text-white tracking-tight">Ultracel</span>
                </a>
                <h2 class="text-4xl font-bold text-white mb-6 leading-tight">
                    Bienvenido de vuelta al mando.
                </h2>
                <p class="text-azul-muy-claro text-lg leading-relaxed font-medium">
                    Tu taller te espera. Ingresa a tu panel gerencial para revisar tus analíticas, gestionar licencias y administrar a tu equipo de técnicos.
                </p>
            </div>
            
            <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-azul-claro opacity-20 rounded-full blur-2xl pointer-events-none"></div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 lg:p-14 flex flex-col justify-center bg-white relative z-20">
            <div class="mb-8 text-center md:text-left">
                <h3 class="text-3xl font-bold text-azul-oscuro mb-2">Iniciar Sesión</h3>
                <p class="text-azul-medio text-sm font-medium">Ingresa tus credenciales de administrador.</p>
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

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-bold text-azul-oscuro mb-2">Correo Electrónico</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="admin@taller.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-azul-oscuro mb-2">Contraseña</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-3 border border-azul-muy-claro rounded-xl focus:ring-2 focus:ring-azul-medio focus:border-azul-medio transition-colors shadow-sm text-azul-oscuro font-medium" placeholder="••••••••">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-azul-muy-claro text-azul-medio shadow-sm focus:ring-azul-medio w-4 h-4">
                        <span class="ml-2 text-sm text-azul-medio font-semibold">Recordarme</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm font-bold text-azul-oscuro hover:text-azul-medio transition-colors" href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full bg-azul-oscuro text-white font-bold text-lg py-3.5 rounded-xl shadow-lg hover:bg-azul-medio transition-all transform hover:-translate-y-1">
                    Ingresar al Panel
                </button>

                <p class="text-center text-sm text-azul-medio mt-8 font-medium border-t border-azul-muy-claro/50 pt-6">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="text-azul-oscuro font-bold hover:underline transition-colors">Registra tu taller aquí</a>
                </p>
            </form>
        </div>
    </div>

</body>
</html>