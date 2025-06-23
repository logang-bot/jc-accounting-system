@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        {{-- <div class="bg-blue-600 pt-10 pb-[84px]"></div> --}}
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Plan de Cuentas</h3>
                </div>

                <!-- Table -->
                <div class="px-6 flex flex-row w-full gap-4">
                    <div class="flex gap-2 flex-col w-[10%]">
                        <a href="{{ route('show.cuentas.create') }}" type="button"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 text-center">
                            Adicionar
                        </a>
                        <button type="button" class="bg-cyan-600 text-white px-4 py-2 rounded hover:bg-cyan-700"
                            aria-controls="cuentas-report" data-hs-overlay="#cuentas-report">
                            Reporte
                        </button>
                    </div>
                    <div class="overflow-y-auto max-h-[560px] w-[90%]">
                        <div class="hs-accordion-group border border-gray-300 divide-y divide-gray-300"
                            data-hs-accordion-always-open>

                            <!-- Header row -->
                            <div class="grid grid-cols-7 bg-gray-800 text-white text-sm font-semibold">
                                <div class="px-4 py-2 border-r">Código</div>
                                <div class="px-4 py-2 border-r">Nombre</div>
                                <div class="px-4 py-2 border-r">Tipo</div>
                                <div class="px-4 py-2 border-r">Nivel</div>
                                <div class="px-4 py-2 border-r">Movimiento</div>
                                <div class="px-4 py-2 border-r">Moneda</div>
                                <div class="px-4 py-2">Acciones</div>
                            </div>

                            <!-- Content rows -->
                            @forelse ($cuentas as $cuenta)
                                <div class="hs-accordion" id="cuenta-{{ $cuenta->id_cuenta }}"
                                    data-row-id={{ $cuenta->id_cuenta }}>
                                    <div class="grid grid-cols-7 items-center text-sm cursor-pointer hover:bg-gray-100">
                                        <div class="font-mono px-4 py-3">{{ $cuenta->codigo_cuenta }}</div>
                                        <div>
                                            {{ str_repeat('— ', min($cuenta->nivel - 1, 4)) . $cuenta->nombre_cuenta }}
                                        </div>
                                        <div>{{ $cuenta->tipo_cuenta }}</div>
                                        <div>Nivel {{ $cuenta->nivel }}</div>
                                        <div>
                                            @if ($cuenta->es_movimiento)
                                                <span
                                                    class="inline-block bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">Sí</span>
                                            @else
                                                <span
                                                    class="inline-block bg-gray-500 text-white text-xs font-semibold px-2 py-1 rounded">No</span>
                                            @endif
                                        </div>
                                        <div>
                                            @if ($cuenta->es_movimiento && in_array($cuenta->nivel, [4, 5]))
                                                <span
                                                    class="text-xs font-medium px-2 py-1 rounded bg-blue-100 text-blue-700">
                                                    {{ $cuenta->moneda_principal }}
                                                </span>
                                            @else
                                                <span class="text-xs text-gray-400">—</span>
                                            @endif
                                        </div>
                                        <div class="flex gap-2">
                                            @if ($cuenta->children->isNotEmpty())
                                                <button type="button"
                                                    class="hover:bg-blue-700 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-blue-500 items-center hover:text-white"
                                                    aria-expanded="true"
                                                    aria-controls="cuenta-accordion-{{ $cuenta->id_cuenta }}">
                                                    Expandir
                                                    <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                                                </button>
                                            @endif

                                            @if ($cuenta->es_movimiento)
                                                <a href="{{ route('show.cuentas.edit', ['id' => $cuenta->id_cuenta]) }}"
                                                    class="hover:bg-yellow-500 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-yellow-500 items-center hover:text-white">
                                                    <x-carbon-edit class="w-4 h-4 ms-auto" />
                                                    Editar
                                                </a>
                                            @endif

                                            @if ($cuenta->children->isEmpty())
                                                <button type="button"
                                                    class="hover:bg-red-600 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-red-500 items-center hover:text-white"
                                                    data-cuenta-delete-id="{{ $cuenta->id_cuenta }}"
                                                    aria-controls="cuentas-delete" data-hs-overlay="#cuentas-delete">
                                                    <x-carbon-trash-can class="w-4 h-4 ms-auto" />
                                                    Eliminar
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($cuenta->children->isNotEmpty())
                                        <div id="cuenta-accordion-{{ $cuenta->id_cuenta }}"
                                            class="hs-accordion-content hidden pl-4 border-t border-gray-200"
                                            aria-labelledby="cuenta-{{ $cuenta->id_cuenta }}">
                                            <div class="hs-accordion-group" data-hs-accordion-always-open>
                                                @foreach ($cuenta->children as $child)
                                                    @include('cuentas.partials.fila_cuenta', [
                                                        'cuenta' => $child,
                                                        'nivel' => $cuenta->nivel + 1,
                                                    ])
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                            @endforelse

                            @if ($cuentas->isEmpty())
                                <div class="text-center text-gray-500 py-4">No hay cuentas registradas</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <x-modal id="cuentas-delete">
                @include('cuentas.delete')
            </x-modal>

            <x-modal id="cuentas-report">
                @include('cuentas.report')
            </x-modal>

        </div>

    </div>
@endsection
