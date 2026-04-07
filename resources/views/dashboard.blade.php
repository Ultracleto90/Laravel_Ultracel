<x-app-layout>
    @php
        $taller_id = \Illuminate\Support\Facades\Auth::user()->taller_id;
        $taller = \App\Models\Taller::find($taller_id);
        
        // --- MÉTRICAS REALES ---
        $usuariosActivosCount = \App\Models\User::where('taller_id', $taller_id)
            ->where('activo', 1)
            ->where('rol', '!=', 'admin') 
            ->count();

        $reparacionesActivasReal = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->where('taller_id', $taller_id)
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->count();
        
        if($reparacionesActivasReal === 0) {
            $reparacionesActivas = "PAGOS Pendiente"; 
            $ingresosMes = "DB Pendiente";       
        } else {
            $reparacionesActivas = $reparacionesActivasReal;
            try {
                $sumaVentas = \Illuminate\Support\Facades\DB::table('ventas')
                    ->where('taller_id', $taller_id)
                    ->whereMonth('fecha_venta', now()->month)
                    ->whereYear('fecha_venta', now()->year)
                    ->sum('monto_total');
                $ingresosMes = '$' . number_format($sumaVentas, 2);
            } catch (\Exception $e) {
                $ingresosMes = "Error DB";
            }
        }
        
        // Filtramos para que los clientes registrados en la app no aparezcan como empleados
        $usuarios = \App\Models\User::where('taller_id', $taller_id)
            ->where('rol', '!=', 'cliente')
            ->get();

        // --- MODELO MATEMÁTICO: TRANSFORMADA DE LAPLACE ---
        $p0 = $reparacionesActivasReal; 
        
        $reparadosHoy = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->where('taller_id', $taller_id)
            ->where('estado', 'entregado')
            ->whereDate('updated_at', now()->toDateString())
            ->count();

        $k = $p0 > 0 ? round($reparadosHoy / $p0, 3) : 0;
        $funcionLaplace = "P(s) = " . $p0 . " / (s + " . $k . ")";
    @endphp

    <style>
        /* Esto oscurece el fondo general y el header que vienen de app.blade.php */
        main { background-color: #111827 !important; }
        header.bg-white { background-color: #1F2937 !important; border-bottom: 1px solid #374151 !important; }
        header.bg-white * { color: #E5E7EB !important; }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight flex items-center gap-3">
                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Hola, {{ \Illuminate\Support\Facades\Auth::user()->name }}
            </h2>
            <span class="bg-gray-800 text-gray-300 border border-gray-600 px-4 py-1.5 rounded-md text-xs font-bold tracking-wider uppercase shadow-sm">
                {{ $taller->nombre_negocio }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-gray-900 min-h-screen text-gray-300">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-700 flex items-center justify-between hover:border-gray-500 transition">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1 tracking-wide">Reparaciones en Taller</p>
                        <h3 class="text-3xl font-extrabold text-white">{{ $reparacionesActivasReal }}</h3>
                        <p class="text-xs text-gray-500 mt-1">No entregadas / canceladas.</p>
                    </div>
                    <div class="w-14 h-14 bg-gray-900 border border-gray-700 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-700 flex items-center justify-between hover:border-gray-500 transition">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1 tracking-wide">Ingresos del Mes</p>
                        <h3 class="text-3xl font-extrabold text-white">{{ $ingresosMes }}</h3>
                        <p class="text-xs text-green-400 font-medium mt-1">Calculado en tiempo real</p>
                    </div>
                    <div class="w-14 h-14 bg-gray-900 border border-gray-700 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-700 flex items-center justify-between hover:border-gray-500 transition">
                    <div>
                        <p class="text-gray-400 text-sm font-semibold mb-1 tracking-wide">Empleados Activos</p>
                        <h3 class="text-3xl font-extrabold text-white">{{ $usuariosActivosCount }}</h3>
                        <p class="text-xs text-gray-500 mt-1">Técnicos y Vendedores.</p>
                    </div>
                    <div class="w-14 h-14 bg-gray-900 border border-gray-700 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-700 flex items-center justify-between hover:border-gray-500 transition">
                    <div>
                        <p class="text-gray-500 text-xs font-bold mb-1 tracking-widest uppercase">Función de Transferencia</p>
                        <h3 class="text-xl font-mono font-extrabold text-green-400 mb-1">{{ $funcionLaplace }}</h3>
                        <p class="text-[10px] text-gray-600 font-mono mt-1">ℒ{dP/dt = -kP} | k={{ $k }}</p>
                    </div>
                    <div class="flex-shrink-0 w-14 h-14 bg-black border border-green-500/30 rounded-xl flex items-center justify-center shadow-inner">
                        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                </div>

            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-gray-800 rounded-3xl shadow-lg border border-gray-700 overflow-hidden hover:border-gray-500 transition">
                    <div class="bg-gray-900 px-8 py-5 flex justify-between items-center border-b border-gray-700">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Estado de Licencia
                        </h3>
                        
                        @if($taller->estado_licencia === 'prueba')
                            <span class="bg-yellow-900/50 text-yellow-400 border border-yellow-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide animate-pulse">En Prueba</span>
                        @elseif($taller->estado_licencia === 'activa')
                            <span class="bg-green-900/50 text-green-400 border border-green-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Activa</span>
                        @else
                            <span class="bg-red-900/50 text-red-400 border border-red-700 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Vencida</span>
                        @endif
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-semibold text-gray-400 mb-1">Vencimiento de Licencia:</p>
                            <p class="text-2xl font-bold text-white mb-4">{{ \Carbon\Carbon::parse($taller->fecha_vencimiento_licencia)->format('d \d\e M, Y') }}</p>
                            
                            <p class="text-sm text-gray-500 mb-6">Asegúrate de renovar antes de esta fecha para no perder el acceso a tu software.</p>
                            
                            <a href="{{ route('taller.suscripcion') }}" class="inline-block text-center bg-blue-600 hover:bg-blue-500 text-white font-bold py-2.5 px-6 rounded-xl transition shadow-md w-full md:w-auto border border-blue-500">
                                Renovar / Cambiar Plan
                            </a>
                        </div>
                        
                        <div class="bg-gray-900 p-5 rounded-2xl border border-gray-700 shadow-inner">
                            <p class="text-sm font-bold text-gray-300 mb-2 flex items-center gap-2">
                                <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                Token de Conexión (App Python)
                            </p>
                            <p class="text-xs text-gray-500 mb-3">Ingresa esta clave en tu Software de Escritorio Ultracel.</p>
                            
                            <div class="flex items-center gap-2">
                                <code class="bg-black border border-gray-800 px-4 py-3 rounded-lg text-green-400 font-mono font-bold w-full text-center shadow-inner tracking-widest text-lg">
                                    {{ $taller->token_licencia ?? 'GENERANDO...' }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-900 to-gray-900 rounded-3xl shadow-lg p-8 text-white flex flex-col justify-center relative overflow-hidden border border-gray-700 hover:border-gray-500 transition">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-gray-800 border border-gray-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold mb-2">Descarga el Software</h3>
                        <p class="text-gray-300 text-sm font-medium mb-6 leading-relaxed">Equipa tu mostrador con la versión de Windows o dale movilidad a tus técnicos con la App Android.</p>
                        
                        <div class="space-y-3">
                            <a href="https://www.mediafire.com/file/fdc1vvebdng3euy/Ultracel_Setup.exe/file" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full bg-white text-gray-900 font-bold py-3 rounded-xl hover:bg-gray-200 transition shadow-lg">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M0 3.449L9.75 2.1v9.451H0m10.949-9.602L24 0v11.4H10.949M0 12.6h9.75v9.451L0 20.699M10.949 12.6H24V24l-12.951-1.801"/></svg>
                                Para Windows
                            </a>
                            
                            <a href="https://www.mediafire.com/file/oc9b6sup193x48u/application-9790f7c9-a38a-4166-a2ce-2865fa05cc84.apk/file" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-2 w-full bg-gray-800 text-white font-bold py-3 rounded-xl hover:bg-gray-700 border border-gray-600 transition shadow-lg">
                                <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4483-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0004.5511-.4482.9997-.9993.9997m-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5515 0 .9997.4482.9997.9993 0 .5511-.4482.9997-.9997.9997m11.4045-6.02l1.9973-3.4592a.416.416 0 00-.1521-.5676.4161.4161 0 00-.5676.1521l-2.0223 3.503C15.5902 8.246 13.8533 7.85 12 7.85s-3.5902.396-5.1367 1.1004L4.841 5.4475a.4161.4161 0 00-.5676-.1521.4158.4158 0 00-.1521.5676l1.9973 3.4592C2.6889 11.1867.3432 14.6589 0 18.761h24c-.3436-4.1021-2.6892-7.5743-6.1185-9.4396"/></svg>
                                Para Android (APK)
                            </a>
                        </div>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-blue-500 opacity-20 rounded-full blur-3xl"></div>
                </div>

            </div>

            @if(session('success'))
                <div class="bg-green-900/30 border-l-4 border-green-500 text-green-400 p-4 rounded shadow-sm font-bold flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-900/30 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <div class="flex items-center gap-3 mb-2 text-red-400 font-bold">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Se encontraron errores:
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-300 font-medium ml-8">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div x-data="{ 
                openModal: false, 
                editModal: false, 
                deleteModal: false, 
                editForm: { id: '', name: '', email: '', rol: '' },
                deleteForm: { id: '', name: '' }
            }" class="bg-gray-800 rounded-3xl shadow-lg border border-gray-700 overflow-hidden relative hover:border-gray-500 transition">
                
                <div class="px-8 py-6 border-b border-gray-700 flex flex-col sm:flex-row justify-between items-center gap-4 bg-gray-800/50">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            Tu Equipo de Trabajo
                        </h3>
                        <p class="text-sm text-gray-400 mt-1">Administra los accesos de tus técnicos y vendedores para el mostrador.</p>
                    </div>
                    <button @click="openModal = true" class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded-xl hover:bg-blue-500 transition shadow-lg flex items-center gap-2 border border-blue-500/50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Nuevo Empleado
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-900 text-gray-400 text-xs font-bold uppercase tracking-wider border-b border-gray-700">
                                <th class="px-8 py-4">Nombre</th>
                                <th class="px-8 py-4">Correo (Login)</th>
                                <th class="px-8 py-4">Rol / Estado</th>
                                <th class="px-8 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            @foreach($usuarios as $user)
                            <tr class="hover:bg-gray-700/30 transition {{ $user->activo ? '' : 'opacity-50' }}">
                                <td class="px-8 py-5 font-bold text-white flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-gray-700 border border-gray-600 text-gray-300 flex items-center justify-center text-xs font-bold uppercase shadow-inner">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="{{ $user->activo ? '' : 'line-through text-gray-500' }}">{{ $user->name }}</span>
                                </td>
                                <td class="px-8 py-5 text-gray-400 font-medium {{ $user->activo ? '' : 'line-through text-gray-600' }}">{{ $user->email }}</td>
                                <td class="px-8 py-5">
                                    @if($user->rol === 'admin')
                                        <span class="bg-purple-900/30 text-purple-400 border border-purple-700 px-3 py-1 rounded-full text-xs font-bold tracking-wide">Administrador</span>
                                    @else
                                        <span class="{{ $user->rol === 'vendedor' ? 'bg-blue-900/30 text-blue-400 border-blue-700' : 'bg-green-900/30 text-green-400 border-green-700' }} border px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                            {{ ucfirst($user->rol) }}
                                        </span>
                                        @if(!$user->activo)
                                            <span class="ml-2 bg-red-900/30 text-red-400 border border-red-700 px-2 py-1 rounded-full text-xs font-bold tracking-wide">Suspendido</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center flex items-center justify-center gap-4">
                                    @if($user->rol !== 'admin')
                                        <button @click="editForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', rol: '{{ $user->rol }}' }; editModal = true" class="text-blue-400 hover:text-blue-300 font-medium text-sm transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Editar
                                        </button>
                                        <form action="{{ route('taller.usuarios.status', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $user->activo ? 'text-yellow-500 hover:text-yellow-400' : 'text-green-500 hover:text-green-400' }} font-medium text-sm transition flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                                {{ $user->activo ? 'Suspender' : 'Reactivar' }}
                                            </button>
                                        </form>
                                        <button @click="deleteForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}' }; deleteModal = true" class="text-red-400 hover:text-red-300 font-medium text-sm transition flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            Eliminar
                                        </button>
                                    @else
                                        <span class="text-gray-500 text-xs font-bold tracking-widest uppercase flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                            Protegido
                                        </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm">
                    <div @click.away="openModal = false" class="bg-gray-800 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-600 transform transition-all">
                        <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-gray-700">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Nuevo Empleado
                            </h3>
                            <button @click="openModal = false" class="text-gray-400 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
                        </div>
                        <form action="{{ route('taller.usuarios.store') }}" method="POST" class="p-6 space-y-5">
                            @csrf
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Correo</label>
                                <input type="email" name="email" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Contraseña</label>
                                <input type="password" name="password" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Rol</label>
                                <select name="rol" x-model="editForm.rol" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="recepcionista">Recepcionista (Mostrador)</option>
                                    <option value="tecnico">Técnico (Reparaciones)</option>
                                </select>
                            </div>
                            <div class="pt-4 flex justify-end gap-3">
                                <button type="button" @click="openModal = false" class="px-5 py-2.5 bg-gray-700 text-gray-300 font-bold rounded-xl hover:bg-gray-600 transition">Cancelar</button>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition shadow-lg">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm">
                    <div @click.away="editModal = false" class="bg-gray-800 rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-gray-600 transform transition-all">
                        <div class="bg-gray-900 px-6 py-4 flex justify-between items-center border-b border-gray-700">
                            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Editar Empleado
                            </h3>
                            <button @click="editModal = false" class="text-gray-400 hover:text-white text-2xl font-bold leading-none transition">&times;</button>
                        </div>
                        <form :action="'/taller/usuarios/' + editForm.id" method="POST" class="p-6 space-y-5">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Nombre</label>
                                <input type="text" name="name" x-model="editForm.name" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Correo</label>
                                <input type="email" name="email" x-model="editForm.email" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Nueva Contraseña</label>
                                <input type="password" name="password" class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" placeholder="Déjalo en blanco si no cambias">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-300 mb-1">Rol</label>
                                <select name="rol" x-model="editForm.rol" required class="w-full px-4 py-2 bg-gray-900 border border-gray-600 rounded-xl text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                    <option value="vendedor">Vendedor (Mostrador)</option>
                                    <option value="tecnico">Técnico (Reparaciones)</option>
                                </select>
                            </div>
                            <div class="pt-4 flex justify-end gap-3">
                                <button type="button" @click="editModal = false" class="px-5 py-2.5 bg-gray-700 text-gray-300 font-bold rounded-xl hover:bg-gray-600 transition">Cancelar</button>
                                <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-500 transition shadow-lg">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="deleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm">
                    <div @click.away="deleteModal = false" class="bg-gray-800 rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-red-900/50 transform transition-all text-center p-8">
                        <div class="w-20 h-20 bg-red-900/30 border border-red-500/50 text-red-500 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white mb-2">¿Estás seguro?</h3>
                        <p class="text-gray-400 mb-8">Estás a punto de eliminar a <span class="font-bold text-red-400" x-text="deleteForm.name"></span>. Esta acción borrará su acceso al instante.</p>
                        
                        <form :action="'/taller/usuarios/' + deleteForm.id" method="POST" class="flex justify-center gap-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="deleteModal = false" class="px-6 py-3 bg-gray-700 text-gray-300 font-bold rounded-xl hover:bg-gray-600 transition">Cancelar</button>
                            <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-500 transition shadow-lg">Sí, Eliminar</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>