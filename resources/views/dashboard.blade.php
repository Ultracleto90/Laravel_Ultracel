<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ultracel APP | Dashboard de Analíticas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6">
            
            <div class="w-full md:w-1/4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex items-center justify-center mb-8">
                    <span class="text-2xl font-extrabold text-blue-900">ULTRA-CEL</span>
                </div>
                
                <ul class="space-y-4">
                    <li>
                        <a href="#" class="flex items-center text-blue-700 font-bold bg-blue-50 px-4 py-2 rounded-md">
                            <span class="mr-3">📊</span> Analíticas
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-600 hover:text-blue-600 hover:bg-gray-50 px-4 py-2 rounded-md transition-colors">
                            <span class="mr-3">👥</span> Admin. Usuarios
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-600 hover:text-blue-600 hover:bg-gray-50 px-4 py-2 rounded-md transition-colors">
                            <span class="mr-3">⬇️</span> Descargar Software
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center text-gray-600 hover:text-blue-600 hover:bg-gray-50 px-4 py-2 rounded-md transition-colors">
                            <span class="mr-3">❓</span> Panel de Ayuda
                        </a>
                    </li>
                </ul>
            </div>

            <div class="w-full md:w-3/4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Usuarios Locales</div>
                        <div class="mt-2 text-3xl font-bold text-gray-800">124</div>
                        <div class="mt-1 text-sm text-gray-400">Registrados en la sucursal</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Procesos Activos</div>
                        <div class="mt-2 text-3xl font-bold text-gray-800">38</div>
                        <div class="mt-1 text-sm text-gray-400">Reparaciones en curso</div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wider">Estado Suscripción</div>
                        <div class="mt-2 text-xl font-bold text-green-600 flex items-center">
                            <span class="w-3 h-3 bg-green-500 rounded-full mr-2 animate-pulse"></span>
                            Activa (Plan Pro)
                        </div>
                        <div class="mt-1 text-sm text-gray-400">Renovación en 15 días</div>
                    </div>

                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-bold text-gray-800 border-b pb-4 mb-4">Resumen de Ganancias Semanales</h3>
                    <div class="h-64 flex items-center justify-center bg-gray-50 border-2 border-dashed border-gray-200 rounded-md">
                        <span class="text-gray-400">Aquí irá la gráfica de Chart.js alimentada por la BD</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>