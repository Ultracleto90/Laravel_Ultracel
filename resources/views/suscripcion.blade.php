<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-azul-oscuro leading-tight flex items-center gap-2">
            <span>💳</span> Facturación y Licencia
        </h2>
    </x-slot>

    <div class="py-10 bg-blanco-azulado min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="bg-white rounded-3xl p-8 shadow-sm border border-azul-muy-claro flex flex-col md:flex-row items-center justify-between gap-6">
                <div>
                    <h3 class="text-xl font-bold text-azul-oscuro mb-2">Estado actual de tu cuenta</h3>
                    <div class="flex items-center gap-3">
                        @if($taller->estado_licencia === 'prueba')
                            <span class="bg-yellow-400 text-yellow-900 px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wide animate-pulse">Período de Prueba</span>
                            <span class="text-azul-medio font-medium">Te quedan <strong class="text-azul-oscuro">{{ $diasRestantes }} días</strong> gratis.</span>
                        @elseif($taller->estado_licencia === 'activa')
                            <span class="bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wide">Licencia Activa</span>
                            <span class="text-azul-medio font-medium">Vence en <strong class="text-azul-oscuro">{{ $diasRestantes }} días</strong>.</span>
                        @else
                            <span class="bg-red-500 text-white px-4 py-1.5 rounded-full text-sm font-bold uppercase tracking-wide">Licencia Vencida</span>
                            <span class="text-red-500 font-medium">Tu sistema de mostrador está suspendido. Renueva ahora.</span>
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-azul-medio mb-1">Tu ID de Taller:</p>
                    <code class="bg-blanco-azulado border border-azul-muy-claro px-4 py-2 rounded-lg text-azul-oscuro font-mono font-bold">{{ $taller->id }} - {{ $taller->rfc_tax_id ?? 'SIN RFC' }}</code>
                </div>
            </div>

            <div class="text-center py-6">
                <h2 class="text-3xl font-bold text-azul-oscuro mb-2">Elige el plan ideal para tu negocio</h2>
                <p class="text-azul-medio">Todos los planes incluyen el software de escritorio, app móvil y panel web.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center mt-4">
                
                <div class="bg-white rounded-3xl p-8 border border-azul-muy-claro shadow-sm hover:shadow-xl transition relative flex flex-col h-full">
                    <h3 class="text-xl font-bold text-azul-oscuro mb-2">Mensual</h3>
                    <p class="text-azul-medio text-sm mb-6">Flexibilidad mes a mes.</p>
                    <div class="mb-6">
                        <span class="text-4xl font-extrabold text-azul-oscuro">$150</span>
                        <span class="text-azul-medio font-medium">/mes</span>
                    </div>
                    <ul class="space-y-4 text-sm text-azul-oscuro font-medium mb-8 flex-grow">
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> 1 Sucursal incluida</li>
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Usuarios ilimitados</li>
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Soporte estándar</li>
                    </ul>
                    <form action="{{ route('taller.suscripcion.pagar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo_plan" value="mensual">
                        <button type="submit" class="w-full text-center bg-white border-2 border-azul-oscuro text-azul-oscuro font-bold py-3 rounded-xl hover:bg-azul-muy-claro transition">Suscribirse (1 mes)</button>
                    </form>
                </div>

                <div class="bg-azul-oscuro rounded-3xl p-8 shadow-2xl transform md:-translate-y-6 relative border-4 border-azul-medio z-10 flex flex-col h-full">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-1.5 rounded-full text-sm font-bold shadow-lg whitespace-nowrap">
                        MÁS POPULAR / MEJOR VALOR
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2 mt-2">Anual</h3>
                    <p class="text-azul-claro text-sm mb-6">Ahorra $600 pesos al año.</p>
                    <div class="mb-2">
                        <span class="text-5xl font-extrabold text-white">$100</span>
                        <span class="text-azul-claro font-medium">/mes</span>
                    </div>
                    <p class="text-azul-muy-claro text-xs mb-6 font-medium">Pago único de $1,200</p>
                    <ul class="space-y-4 text-sm text-blanco-azulado font-medium mb-8 flex-grow">
                        <li class="flex items-center gap-3"><span class="bg-green-500 text-white rounded-full p-1 text-xs">✔️</span> 1 Sucursal incluida</li>
                        <li class="flex items-center gap-3"><span class="bg-green-500 text-white rounded-full p-1 text-xs">✔️</span> Usuarios ilimitados</li>
                        <li class="flex items-center gap-3"><span class="bg-yellow-400 text-white rounded-full p-1 text-xs">⭐</span> Soporte prioritario 24/7</li>
                    </ul>
                    <form action="{{ route('taller.suscripcion.pagar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo_plan" value="anual">
                        <button type="submit" class="w-full text-center bg-white text-azul-oscuro font-bold py-4 rounded-xl hover:bg-azul-muy-claro transition transform hover:scale-105 shadow-lg">Suscribirse (1 Año)</button>
                    </form>
                </div>

                <div class="bg-white rounded-3xl p-8 border border-azul-muy-claro shadow-sm hover:shadow-xl transition relative flex flex-col h-full">
                    <h3 class="text-xl font-bold text-azul-oscuro mb-2">Semestral</h3>
                    <p class="text-azul-medio text-sm mb-6">El equilibrio perfecto.</p>
                    <div class="mb-2">
                        <span class="text-4xl font-extrabold text-azul-oscuro">$125</span>
                        <span class="text-azul-medio font-medium">/mes</span>
                    </div>
                    <p class="text-gray-500 text-xs mb-6 font-medium">Pago único de $750</p>
                    <ul class="space-y-4 text-sm text-azul-oscuro font-medium mb-8 flex-grow">
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> 1 Sucursal incluida</li>
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Usuarios ilimitados</li>
                        <li class="flex items-center gap-3"><span class="bg-blue-100 text-blue-600 rounded-full p-1 text-xs">✔️</span> Soporte estándar</li>
                    </ul>
                    <form action="{{ route('taller.suscripcion.pagar') }}" method="POST">
                        @csrf
                        <input type="hidden" name="tipo_plan" value="semestral">
                        <button type="submit" class="w-full text-center bg-white border-2 border-azul-oscuro text-azul-oscuro font-bold py-3 rounded-xl hover:bg-azul-muy-claro transition">Suscribirse (6 meses)</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>