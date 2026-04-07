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
                            <a href="#manual-usuario" class="px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                                Manual de Usuario
                            </a>
                        </li>
                        <li>
                            <a href="#manual-instalacion" class="px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Manual de Instalación
                            </a>
                        </li>
                        <li>
                            <a href="#arquitectura" class="px-4 py-2 text-sm font-medium text-gray-300 rounded-lg hover:bg-blue-600 hover:text-white transition flex items-center gap-2">
                                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                                Arquitectura y API
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="w-full md:w-3/4 space-y-10">
                
                <div id="manual-usuario" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 p-8 hover:border-gray-500 transition mb-10">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-700">
                        <div class="w-12 h-12 bg-blue-900/50 border border-blue-500 rounded-xl flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Manual de Usuario</h3>
                    </div>
                    
                    <div class="space-y-6 text-gray-400 leading-relaxed">
                        <p>Bienvenido al sistema <strong class="text-white">Ultracel</strong>. Este software está diseñado para agilizar la gestión de tu taller de reparación de dispositivos móviles, conectando tu mostrador web con el taller técnico de forma fluida.</p>
                        
                        <h4 class="text-lg font-bold text-blue-400 mt-4">1. Dashboard y Métricas</h4>
                        <p>En el panel principal podrás observar en tiempo real la cantidad de reparaciones pendientes, los ingresos del mes y tu estado de licencia. El modelo matemático (Transformada de Laplace) te indica la eficiencia de tu taller basado en la relación de entregas y pendientes.</p>
                        
                        <h4 class="text-lg font-bold text-blue-400 mt-4">2. Gestión de Empleados</h4>
                        <p>Desde el administrador de equipo puedes registrar a nuevos vendedores o técnicos. <br> <strong class="text-white">Vendedor:</strong> Tendrá acceso al Punto de Venta (POS) en web. <br> <strong class="text-white">Técnico:</strong> Necesitará el correo y contraseña para ingresar en la App de Escritorio o la App Móvil.</p>
                    </div>
                </div>

                <div id="manual-instalacion" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 p-8 hover:border-gray-500 transition mb-10">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-700">
                        <div class="w-12 h-12 bg-green-900/50 border border-green-500 rounded-xl flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Manual de Instalación</h3>
                    </div>
                    
                    <div class="space-y-6 text-gray-400 leading-relaxed">
                        <h4 class="text-lg font-bold text-green-400">Instalación en Windows (App de Escritorio)</h4>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Dirígete al Dashboard y da clic en <strong class="text-white">Descargar para Windows</strong>.</li>
                            <li>Ejecuta el archivo <code class="bg-gray-900 text-green-400 px-2 py-1 rounded font-mono border border-gray-700">Ultracel.exe</code> en la computadora de tu taller.</li>
                            <li>En la pantalla de activación, pega el <strong class="text-white">Token de Conexión</strong> que aparece en tu Panel Web.</li>
                            <li>El sistema generará tu licencia validada criptográficamente y podrás iniciar sesión.</li>
                        </ol>

                        <h4 class="text-lg font-bold text-green-400 mt-6">Instalación en Android (App Móvil)</h4>
                        <ol class="list-decimal list-inside space-y-2 ml-4">
                            <li>Descarga el archivo <code class="bg-gray-900 text-green-400 px-2 py-1 rounded font-mono border border-gray-700">.apk</code> desde el botón del Dashboard a tu teléfono.</li>
                            <li>Al instalar, si tu teléfono pide permisos, selecciona "Instalar de todas formas".</li>
                            <li>Inicia sesión directamente con el correo y contraseña del empleado asignado.</li>
                        </ol>
                    </div>
                </div>

                <div id="arquitectura" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 p-8 hover:border-gray-500 transition mb-12">
                    <div class="flex items-center gap-4 mb-6 pb-4 border-b border-gray-700">
                        <div class="w-12 h-12 bg-purple-900/50 border border-purple-500 rounded-xl flex items-center justify-center shadow-inner">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Arquitectura y API</h3>
                    </div>
                    
                    <div class="space-y-6 text-gray-400 leading-relaxed">
                        <p>El núcleo del sistema está soportado por un backend robusto en <strong>Laravel (PHP)</strong>, implementando una arquitectura Multi-Tenant para segregar los datos de cada taller.</p>
                        
                        <h4 class="text-lg font-bold text-purple-400 mt-4">API RESTful Segura</h4>
                        <p>Las aplicaciones satélite (Escritorio en Python y Móvil en React Native) se comunican a través de endpoints protegidos. El acceso requiere validación de <code class="bg-gray-900 text-purple-400 px-2 py-1 rounded font-mono border border-gray-700">taller_id</code> en cada petición para prevenir accesos cruzados.</p>
                        
                        <div class="bg-gray-900 border border-gray-700 p-4 rounded-xl mt-4 font-mono text-sm text-green-400">
                            POST /api/reparaciones/pendientes<br>
                            POST /api/diagnostico/guardar<br>
                            POST /api/material/crear
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>