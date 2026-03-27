<x-app-layout>
    @php
        $taller_id = \Illuminate\Support\Facades\Auth::user()->taller_id;
        $taller = \App\Models\Taller::find($taller_id);
        
        // --- MÉTRICAS REALES ---
        // 1. Usuarios Activos (Solo contamos técnicos y vendedores activos)
        $usuariosActivosCount = \App\Models\User::where('taller_id', $taller_id)
            ->where('activo', 1)
            ->where('rol', '!=', 'admin') 
            ->count();

        // 2. Reparaciones Activas (Datos reales de tu DB)
        $reparacionesActivasReal = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->where('taller_id', $taller_id)
            ->whereNotIn('estado', ['entregado', 'cancelado'])
            ->count();
        
        // --- SIMULACIÓN PARA MOSTRAR LA UI FINA ---
        if($reparacionesActivasReal === 0) {
            $reparacionesActivas = "PAGOS Pendiente"; 
            $ingresosMes = "DB Pendiente";       
        } else {
            $reparacionesActivas = $reparacionesActivasReal;
            // --- INGRESOS REALES DEL MES ---
            try {
                // Sumamos el 'monto_total' de la tabla 'ventas' de ESTE mes y ESTE taller
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
        
        // Mantenemos la lista de usuarios completa para la tabla de abajo
        $usuarios = \App\Models\User::where('taller_id', $taller_id)->get();



        // --- MODELO MATEMÁTICO: TRANSFORMADA DE LAPLACE ---
        // Ecuación diferencial de decaimiento del trabajo: dP/dt = -kP
        // Función de Transferencia en el dominio 's': P(s) = P(0) / (s + k)
        
        $p0 = $reparacionesActivasReal; // P(0): Condición inicial (Pendientes actuales)
        
        // Calculamos cuántos equipos se entregaron HOY para sacar la constante 'k'
        $reparadosHoy = \Illuminate\Support\Facades\DB::table('reparaciones')
            ->where('taller_id', $taller_id)
            ->where('estado', 'entregado')
            ->whereDate('updated_at', now()->toDateString())
            ->count();

        // k = Tasa de flujo de trabajo (Reparados / Pendientes)
        $k = $p0 > 0 ? round($reparadosHoy / $p0, 3) : 0;
        
        // Armamos la cadena visual de la Transformada
        $funcionLaplace = "P(s) = " . $p0 . " / (s + " . $k . ")";
    @endphp

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-azul-oscuro leading-tight">
                👋 Hola, {{ \Illuminate\Support\Facades\Auth::user()->name }}
            </h2>
            <span class="bg-azul-muy-claro text-azul-oscuro px-4 py-1.5 rounded-full text-sm font-bold shadow-sm">
                {{ $taller->nombre_negocio }}
            </span>
        </div>
    </x-slot>

    <div class="py-10 bg-blanco-azulado min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-azul-muy-claro flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-azul-medio text-sm font-bold mb-1">Reparaciones en Taller</p>
                        <h3 class="text-3xl font-extrabold text-azul-oscuro">{{ $reparacionesActivasReal }}</h3>
                        <p class="text-xs text-azul-medio">No entregadas / canceladas.</p>
                    </div>
                    <div class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-3xl shadow-inner">🔧</div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-azul-muy-claro flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-azul-medio text-sm font-bold mb-1">Ingresos del Mes</p>
                        <h3 class="text-3xl font-extrabold text-azul-oscuro">{{ $ingresosMes }}</h3>
                        <p class="text-xs text-green-600 font-bold">Calculado en tiempo real</p>
                    </div>
                    <div class="w-14 h-14 bg-green-100 text-green-600 rounded-xl flex items-center justify-center text-3xl shadow-inner">💰</div>
                </div>

                <div class="bg-white rounded-2xl p-6 shadow-sm border border-azul-muy-claro flex items-center justify-between hover:shadow-md transition">
                    <div>
                        <p class="text-azul-medio text-sm font-bold mb-1">Empleados Activos</p>
                        <h3 class="text-3xl font-extrabold text-azul-oscuro">{{ $usuariosActivosCount }}</h3>
                        <p class="text-xs text-azul-medio">Técnicos y Vendedores habilitados.</p>
                    </div>
                    <div class="w-14 h-14 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center text-3xl shadow-inner">👥</div>
                </div> <div class="bg-gradient-to-br from-gray-900 to-black rounded-2xl p-6 shadow-sm border border-gray-700 flex items-center justify-between hover:shadow-lg transition transform hover:-translate-y-1">
                    <div>
                        <p class="text-gray-400 text-xs font-bold mb-1 tracking-widest uppercase">Función de Transferencia</p>
                        <h3 class="text-xl font-mono font-extrabold text-green-400 mb-1">{{ $funcionLaplace }}</h3>
                        <p class="text-[10px] text-gray-500 font-mono">ℒ{dP/dt = -kP} | k={{ $k }}</p>
                    </div>
                    <div class="flex-shrink-0 w-14 h-14 bg-gray-800 text-green-400 border border-green-500/30 rounded-xl flex items-center justify-center text-3xl shadow-inner">ℒ</div>
                </div>class="flex-shrink-0 w-14 h-14 bg-gray-800 text-green-400 border border-green-500/30 rounded-xl flex items-center justify-center text-3xl shadow-inner">ℒ</div>
                </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-azul-muy-claro overflow-hidden hover:shadow-md transition">
                    <div class="bg-azul-oscuro px-8 py-5 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2"><span>🛡️</span> Estado de Licencia</h3>
                        
                        @if($taller->estado_licencia === 'prueba')
                            <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide animate-pulse">En Prueba</span>
                        @elseif($taller->estado_licencia === 'activa')
                            <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Activa</span>
                        @else
                            <span class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">Vencida</span>
                        @endif
                    </div>
                    
                    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <p class="text-sm font-bold text-azul-medio mb-1">Vencimiento de Licencia:</p>
                            <p class="text-2xl font-bold text-azul-oscuro mb-4">{{ \Carbon\Carbon::parse($taller->fecha_vencimiento_licencia)->format('d \d\e M, Y') }}</p>
                            
                            <p class="text-sm text-azul-medio mb-6">Asegúrate de renovar antes de esta fecha para no perder el acceso a tu software.</p>
                            
                            <a href="{{ route('taller.suscripcion') }}" class="inline-block text-center bg-azul-medio hover:bg-azul-oscuro text-white font-bold py-2.5 px-6 rounded-xl transition shadow-md w-full md:w-auto">
                                Renovar / Cambiar Plan
                            </a>
                        </div>
                        
                        <div class="bg-blanco-azulado p-5 rounded-2xl border border-azul-claro/50">
                            <p class="text-sm font-bold text-azul-oscuro mb-2 flex items-center gap-2">
                                🔑 Token de Conexión (App Python)
                            </p>
                            <p class="text-xs text-azul-medio mb-3">Ingresa esta clave en tu Software de Escritorio Ultracel.</p>
                            
                            <div class="flex items-center gap-2">
                                <code class="bg-white border border-azul-muy-claro px-4 py-3 rounded-lg text-azul-oscuro font-mono font-bold w-full text-center shadow-inner tracking-widest text-lg">
                                    {{ $taller->token_licencia ?? 'GENERANDO...' }}
                                </code>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-azul-medio to-azul-oscuro rounded-3xl shadow-sm p-8 text-white flex flex-col justify-center relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-3xl mb-6 backdrop-blur-sm">🚀</div>
                        <h3 class="text-2xl font-bold mb-2">Descarga la App</h3>
                        <p class="text-azul-claro text-sm font-medium mb-6 leading-relaxed">Instala el Software de Mostrador y usa tu token para sincronizar.</p>
                        <a href="{{ asset('descargas/Ultracel_Setup.exe') }}" download class="block text-center w-full bg-white text-azul-oscuro font-bold py-3 rounded-xl hover:bg-azul-muy-claro transition shadow-lg">
                            Descargar para Windows
                        </a>
                    </div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                </div>

            </div>

            @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded shadow-sm">
                    <ul class="list-disc list-inside text-sm text-red-600 font-bold">
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
            }" class="bg-white rounded-3xl shadow-sm border border-azul-muy-claro overflow-hidden relative hover:shadow-md transition">
                
                <div class="px-8 py-6 border-b border-azul-muy-claro flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xl font-bold text-azul-oscuro">Tu Equipo de Trabajo</h3>
                        <p class="text-sm text-azul-medio">Administra los accesos de tus técnicos y vendedores para el mostrador.</p>
                    </div>
                    <button @click="openModal = true" class="bg-azul-oscuro text-white font-bold py-2.5 px-6 rounded-xl hover:bg-azul-medio transition shadow-md flex items-center gap-2">
                        <span>+</span> Nuevo Empleado
                    </button>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-blanco-azulado text-azul-oscuro text-sm font-bold uppercase tracking-wider">
                                <th class="px-8 py-4">Nombre</th>
                                <th class="px-8 py-4">Correo (Login)</th>
                                <th class="px-8 py-4">Rol / Estado</th>
                                <th class="px-8 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-azul-muy-claro">
                            @foreach($usuarios as $user)
                            <tr class="hover:bg-azul-muy-claro/20 transition {{ $user->activo ? '' : 'opacity-50 bg-gray-50' }}">
                                <td class="px-8 py-5 font-bold text-azul-oscuro flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-azul-claro text-azul-oscuro flex items-center justify-center text-xs font-bold uppercase shadow-sm">
                                        {{ substr($user->name, 0, 2) }}
                                    </div>
                                    <span class="{{ $user->activo ? '' : 'line-through text-gray-500' }}">{{ $user->name }}</span>
                                </td>
                                <td class="px-8 py-5 text-azul-medio font-medium {{ $user->activo ? '' : 'line-through text-gray-400' }}">{{ $user->email }}</td>
                                <td class="px-8 py-5">
                                    @if($user->rol === 'admin')
                                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs font-bold tracking-wide">Administrador</span>
                                    @else
                                        <span class="{{ $user->rol === 'vendedor' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700' }} px-3 py-1 rounded-full text-xs font-bold tracking-wide">
                                            {{ ucfirst($user->rol) }}
                                        </span>
                                        @if(!$user->activo)
                                            <span class="ml-2 bg-red-100 text-red-700 px-2 py-1 rounded-full text-xs font-bold tracking-wide">Suspendido</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-8 py-5 text-center flex items-center justify-center gap-3">
                                    @if($user->rol !== 'admin')
                                        <button @click="editForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}', email: '{{ $user->email }}', rol: '{{ $user->rol }}' }; editModal = true" class="text-blue-500 hover:text-blue-700 font-bold text-sm transition">Editar</button>
                                        <form action="{{ route('taller.usuarios.status', $user->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="{{ $user->activo ? 'text-yellow-500 hover:text-yellow-700' : 'text-green-500 hover:text-green-700' }} font-bold text-sm transition">{{ $user->activo ? 'Suspender' : 'Reactivar' }}</button>
                                        </form>
                                        <button @click="deleteForm = { id: '{{ $user->id }}', name: '{{ addslashes($user->name) }}' }; deleteModal = true" class="text-red-500 hover:text-red-700 font-bold text-sm transition">Eliminar</button>
                                    @else
                                        <span class="text-gray-400 text-xs font-bold">Intocable 👑</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm">
                    <div @click.away="openModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-azul-muy-claro transform transition-all">
                        <div class="bg-azul-oscuro px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white">Nuevo Empleado</h3>
                            <button @click="openModal = false" class="text-azul-claro hover:text-white text-2xl font-bold leading-none">&times;</button>
                        </div>
                        <form action="{{ route('taller.usuarios.store') }}" method="POST" class="p-6 space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Nombre</label>
                                <input type="text" name="name" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Correo</label>
                                <input type="email" name="email" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Contraseña</label>
                                <input type="password" name="password" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Rol</label>
                                <select name="rol" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro bg-white">
                                    <option value="vendedor">Vendedor (Mostrador)</option>
                                    <option value="tecnico">Técnico (Reparaciones)</option>
                                </select>
                            </div>
                            <div class="pt-2 flex justify-end gap-3">
                                <button type="button" @click="openModal = false" class="px-5 py-2 bg-gray-100 font-bold rounded-xl hover:bg-gray-200 text-gray-700">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-azul-oscuro text-white font-bold rounded-xl hover:bg-azul-medio">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="editModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm">
                    <div @click.away="editModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-azul-muy-claro transform transition-all">
                        <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                            <h3 class="text-xl font-bold text-white">Editar Empleado</h3>
                            <button @click="editModal = false" class="text-blue-200 hover:text-white text-2xl font-bold leading-none">&times;</button>
                        </div>
                        <form :action="'/taller/usuarios/' + editForm.id" method="POST" class="p-6 space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Nombre</label>
                                <input type="text" name="name" x-model="editForm.name" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Correo</label>
                                <input type="email" name="email" x-model="editForm.email" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Nueva Contraseña (Opcional)</label>
                                <input type="password" name="password" class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro" placeholder="Déjalo en blanco si no la quieres cambiar">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-azul-oscuro mb-1">Rol</label>
                                <select name="rol" x-model="editForm.rol" required class="w-full px-4 py-2 border rounded-xl focus:ring-2 focus:ring-azul-medio border-azul-muy-claro bg-white">
                                    <option value="vendedor">Vendedor (Mostrador)</option>
                                    <option value="tecnico">Técnico (Reparaciones)</option>
                                </select>
                            </div>
                            <div class="pt-2 flex justify-end gap-3">
                                <button type="button" @click="editModal = false" class="px-5 py-2 bg-gray-100 font-bold rounded-xl hover:bg-gray-200 text-gray-700">Cancelar</button>
                                <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700">Actualizar</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div x-show="deleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-60 backdrop-blur-sm">
                    <div @click.away="deleteModal = false" class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-red-100 transform transition-all text-center p-8">
                        <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center text-4xl mx-auto mb-4">⚠️</div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">¿Estás seguro?</h3>
                        <p class="text-gray-500 mb-8">Estás a punto de eliminar a <span class="font-bold text-red-600" x-text="deleteForm.name"></span> permanentemente. Esta acción no se puede deshacer.</p>
                        
                        <form :action="'/taller/usuarios/' + deleteForm.id" method="POST" class="flex justify-center gap-4">
                            @csrf
                            @method('DELETE')
                            <button type="button" @click="deleteModal = false" class="px-6 py-3 bg-gray-100 font-bold rounded-xl hover:bg-gray-200 text-gray-700">Cancelar</button>
                            <button type="submit" class="px-6 py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 shadow-md">Sí, Eliminar</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>