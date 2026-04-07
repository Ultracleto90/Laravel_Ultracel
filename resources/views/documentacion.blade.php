<x-app-layout>
    <style>
        main { background-color: #111827 !important; }
        header.bg-white { background-color: #1F2937 !important; border-bottom: 1px solid #374151 !important; }
        header.bg-white * { color: #E5E7EB !important; }
    </style>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight flex items-center gap-3">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            Centro de Documentación Ultracel
        </h2>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen text-gray-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col md:flex-row gap-8">
            
            <div class="w-full md:w-1/4">
                <div class="bg-gray-800 rounded-2xl shadow-lg border border-gray-700 overflow-hidden sticky top-8">
                    <div class="p-5 border-b border-gray-700 bg-gray-900">
                        <h3 class="font-bold text-white tracking-wide uppercase text-sm">Índice</h3>
                    </div>
                    <ul class="p-3 space-y-1">
                        <li>
                            <a href="#manual-usuario" class="block px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition">
                                📘 Manual de Usuario
                            </a>
                        </li>
                        <li>
                            <a href="#manual-instalacion" class="block px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition">
                                🛠️ Manual de Instalación
                            </a>
                        </li>
                        <li>
                            <a href="#arquitectura" class="block px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition">
                                📐 Arquitectura y API
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="w-full md:w-3/4 space-y-10">
                
                <div id="manual-usuario" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 p-8 hover:border-gray-500 transition">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-700">
                        <div class="w-12 h-12 bg-blue-900/50 border border-blue-500 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">📘</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Manual de Usuario</h3>
                    </div>
                    
                    <div class="space-y-6 text-gray-400 leading-relaxed">
                        <p>Bienvenido al sistema <strong>Ultracel</strong>. Este software está diseñado para agilizar la gestión de tu taller de reparación de dispositivos móviles, conectando tu mostrador web con el taller técnico.</p>
                        
                        <h4 class="text-lg font-bold text-blue-400 mt-4">1. Dashboard y Métricas</h4>
                        <p>En el panel principal podrás observar en tiempo real la cantidad de reparaciones pendientes, los ingresos del mes y tu estado de licencia. El modelo matemático (Laplace) te indica la eficiencia de tu taller basado en las entregas diarias.</p>
                        
                        <h4 class="text-lg font-bold text-blue-400 mt-4">2. Gestión de Empleados</h4>
                        <p>Desde el administrador de equipo puedes registrar a nuevos vendedores o técnicos. <br> <strong>Vendedor:</strong> Tendrá acceso al Punto de Venta (POS). <br> <strong>Técnico:</strong> Necesitará el correo y contraseña para ingresar en la App de Escritorio o la App Móvil.</p>
                    </div>
                </div>

                <div id="manual-instalacion" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 p-8 hover:border-gray-500 transition">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-700">
                        <div class="w-12 h-12 bg-green-900/50 border border-green-500 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🛠️</span>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Manual de Instalación</h3>
                    </div>
                    
                    <div class="space-y-6 text-gray-400 leading-relaxed">
                        <h4 class="text-lg font-bold text-green-400">Instalación en Windows (App de Escritorio)</h4>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Dirígete al Dashboard y da clic en <strong>Descargar para Windows</strong>.</li>
                            <li>Ejecuta el archivo <code>Ultracel.exe</code> en la computadora de tu taller.</li>
                            <li>En la pantalla de activación, pega el <strong>Token de Conexión</strong> que aparece en tu Panel Web.</li>
                            <li>El sistema generará tu licencia y podrás iniciar sesión con las credenciales de Técnico.</li>
                        </ol>

                        <h4 class="text-lg font-bold text-green-400 mt-6">Instalación en Android (App Móvil)</h4>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Descarga el archivo <code>.apk</code> desde el botón del Dashboard a tu teléfono.</li>
                            <li>Al instalar, si tu teléfono pide permisos, selecciona "Instalar de todas formas".</li>
                            <li>Inicia sesión directamente con el correo y contraseña del empleado.</li>
                        </ol>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>