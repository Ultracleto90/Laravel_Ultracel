<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Planes y Suscripciones') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="text-center mb-12">
                <h3 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Elige el plan ideal para tu taller</h3>
                <p class="mt-4 text-xl text-gray-500">Digitaliza tus reparaciones, controla tus ventas y crece tu negocio.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">
                
                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">
                    <h4 class="text-2xl font-semibold text-gray-900">Emprendedor</h4>
                    <p class="mt-4 text-gray-500">Perfecto para técnicos independientes.</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-gray-900">$299</span>
                        <span class="text-base font-medium text-gray-500">/mes</span>
                    </p>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> 1 Sucursal</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Hasta 3 usuarios</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Soporte por correo</li>
                    </ul>
                    <button class="mt-8 w-full bg-blue-50 text-blue-700 font-bold py-3 rounded-md hover:bg-blue-100 transition">Seleccionar Plan</button>
                </div>

                <div class="bg-blue-900 rounded-lg shadow-2xl p-8 transform md:scale-105 border-4 border-blue-500 relative z-10">
                    <div class="absolute top-0 right-0 bg-blue-500 text-white px-3 py-1 rounded-bl-lg rounded-tr-sm text-sm font-bold tracking-wider">MÁS POPULAR</div>
                    <h4 class="text-2xl font-semibold text-white">Plan Pro</h4>
                    <p class="mt-4 text-blue-200">Para talleres en pleno crecimiento.</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-white">$599</span>
                        <span class="text-base font-medium text-blue-200">/mes</span>
                    </p>
                    <ul class="mt-8 space-y-4 text-blue-100">
                        <li class="flex items-center"><span class="text-blue-400 mr-2">✓</span> Sucursales ilimitadas</li>
                        <li class="flex items-center"><span class="text-blue-400 mr-2">✓</span> Usuarios ilimitados</li>
                        <li class="flex items-center"><span class="text-blue-400 mr-2">✓</span> App de Escritorio Full</li>
                        <li class="flex items-center"><span class="text-blue-400 mr-2">✓</span> Soporte prioritario 24/7</li>
                    </ul>
                    <button class="mt-8 w-full bg-blue-500 text-white font-bold py-3 rounded-md hover:bg-blue-400 transition shadow-lg">Pagar con Mercado Pago</button>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-8 border border-gray-200">
                    <h4 class="text-2xl font-semibold text-gray-900">Empresarial</h4>
                    <p class="mt-4 text-gray-500">Para franquicias y grandes marcas.</p>
                    <p class="mt-8">
                        <span class="text-4xl font-extrabold text-gray-900">$1,499</span>
                        <span class="text-base font-medium text-gray-500">/mes</span>
                    </p>
                    <ul class="mt-8 space-y-4">
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Todo lo del Plan Pro</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Servidor Dedicado</li>
                        <li class="flex items-center"><span class="text-green-500 mr-2">✓</span> Marca Blanca (Tu Logo)</li>
                    </ul>
                    <button class="mt-8 w-full bg-blue-50 text-blue-700 font-bold py-3 rounded-md hover:bg-blue-100 transition">Contactar Ventas</button>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>