<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ultracel') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}?v=1">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { background-color: #111827 !important; color: #E5E7EB !important; }
            
            /* El cuadro central del formulario */
            .bg-white { 
                background-color: #1F2937 !important; 
                border: 1px solid #374151 !important; 
                box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.3) !important; 
            }
            
            /* Textos secundarios y Labels */
            .text-gray-500, .text-gray-600 { color: #9CA3AF !important; }
            .text-gray-900 { color: #F3F4F6 !important; }
            label { color: #D1D5DB !important; font-weight: 600 !important; }
            
            /* Inputs de texto */
            input[type="text"], input[type="email"], input[type="password"] {
                background-color: #111827 !important;
                border: 1px solid #374151 !important;
                color: #FFFFFF !important;
                border-radius: 0.5rem !important;
            }
            input:focus { border-color: #3B82F6 !important; box-shadow: 0 0 0 1px #3B82F6 !important; }
            
            /* Botones (Negro a Azul Neón) */
            button, .inline-flex.items-center.px-4.py-2.bg-gray-800 {
                background-color: #2563EB !important;
                color: white !important;
                border: none !important;
                transition: all 0.3s;
                font-weight: bold !important;
                letter-spacing: 0.05em !important;
            }
            button:hover, .inline-flex.items-center.px-4.py-2.bg-gray-800:hover { background-color: #1D4ED8 !important; }
            
            /* Enlaces (Olvidaste contraseña, etc) */
            a.underline { color: #60A5FA !important; text-decoration-color: #3B82F6 !important; }
            a.underline:hover { color: #93C5FD !important; }
        </style>
    </head>
    <body class="font-sans text-gray-900 antialiased selection:bg-blue-500 selection:text-white">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-900">
            <div>
                <a href="/" class="flex flex-col items-center gap-2 hover:opacity-80 transition outline-none">
                    <svg class="w-20 h-20 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span class="font-extrabold text-4xl tracking-widest text-white">ULTRA<span class="text-blue-500">CEL</span></span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-6 py-8 bg-gray-800 shadow-2xl overflow-hidden sm:rounded-2xl border border-gray-700">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>