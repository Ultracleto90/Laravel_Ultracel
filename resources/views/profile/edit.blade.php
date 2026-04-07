<x-app-layout>
    <style>
        /* Fondos generales */
        main { background-color: #111827 !important; }
        
        /* Forzar los textos oscuros de Breeze a blancos y grises claros */
        .text-gray-900, .text-gray-800 { color: #F3F4F6 !important; }
        .text-gray-600 { color: #9CA3AF !important; }
        
        /* Etiquetas de los inputs (Labels) */
        label.block.font-medium.text-sm.text-gray-700 { color: #D1D5DB !important; font-weight: bold !important; }
        
        /* Inputs de texto y contraseñas */
        input[type="text"], input[type="email"], input[type="password"] { 
            background-color: #111827 !important; 
            border: 1px solid #374151 !important; 
            color: #FFFFFF !important; 
            border-radius: 0.75rem !important;
            padding: 0.5rem 1rem !important;
        }
        input:focus {
            border-color: #3B82F6 !important;
            box-shadow: 0 0 0 1px #3B82F6 !important;
        }

        /* Botón de Guardar (Negro por defecto -> Azul Neón) */
        button.bg-gray-800 { 
            background-color: #2563EB !important; 
            color: white !important;
            border: none !important; 
            border-radius: 0.75rem !important;
            padding: 0.5rem 1.5rem !important;
            font-weight: bold !important;
            transition: background-color 0.3s;
        }
        button.bg-gray-800:hover { background-color: #1D4ED8 !important; }

        /* Botón de Eliminar Cuenta (Rojo oscuro -> Rojo vibrante) */
        button.bg-red-600 {
            background-color: #DC2626 !important;
            border-radius: 0.75rem !important;
            font-weight: bold !important;
        }
        button.bg-red-600:hover { background-color: #B91C1C !important; }

        /* Modal (Si hacen clic en Eliminar Cuenta) */
        .bg-white { background-color: #1F2937 !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            {{ __('Perfil de Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="p-6 sm:p-8 bg-gray-800 shadow-lg sm:rounded-3xl border border-gray-700 hover:border-gray-600 transition">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 shadow-lg sm:rounded-3xl border border-gray-700 hover:border-gray-600 transition">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-gray-800 shadow-lg sm:rounded-3xl border border-red-900/30 hover:border-red-500/50 transition">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>