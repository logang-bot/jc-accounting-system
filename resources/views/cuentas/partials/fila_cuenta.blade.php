<div class="{{ $cuenta->children->isNotEmpty() ? 'hs-accordion active' : '' }} {{ getLevelClasses($cuenta->nivel) }}"
    data-row-id={{ $cuenta->id_cuenta }}>

    <div class="grid grid-cols-7 items-center cursor-pointer hover:bg-gray-100 py-2 w-full"
        id="cuenta-{{ $cuenta->id_cuenta }}">
        <div class="font-mono px-4">
            {{ $cuenta->codigo_cuenta }}
        </div>
        <div class="px-4">{{ $cuenta->nombre_cuenta }}</div>
        <div class="px-4">{{ $cuenta->tipo_cuenta }}</div>
        <div class="px-4">Nivel {{ $cuenta->nivel }}</div>
        <div class="px-4">
            @if ($cuenta->es_movimiento)
                <span class="inline-block bg-green-600 text-white font-semibold px-2 py-1 rounded">Si</span>
            @else
                <span class="inline-block bg-gray-500 text-white font-semibold px-2 py-1 rounded">No</span>
            @endif
        </div>
        <div class="px-4">
            @if ($cuenta->es_movimiento && in_array($cuenta->nivel, [4, 5]))
                <span class="font-medium px-2 py-1 rounded bg-blue-100 text-blue-700">
                    {{ $cuenta->moneda_principal }}
                </span>
            @else
                <span class="text-gray-400">—</span>
            @endif
        </div>
        <div class="flex gap-2 px-4">
            @if ($cuenta->children->isNotEmpty())
                <button type="button"
                    class="hover:bg-blue-700 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-blue-500 items-center hover:text-white"
                    aria-expanded="true" aria-controls="cuenta-accordion-{{ $cuenta->id_cuenta }}">
                    Expandir
                    <x-carbon-chevron-down class="w-4 h-4 ms-auto" />
                </button>
            @endif

            @if ($cuenta->nivel === 4 || $cuenta->nivel === 5)
                <a href="{{ route('show.cuentas.edit', ['id' => $cuenta->id_cuenta]) }}"
                    class="hover:bg-yellow-500 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-yellow-500 items-center hover:text-white">
                    <x-carbon-edit class="w-4 h-4 ms-auto" />
                    Editar
                </a>
            @endif

            @if ($cuenta->children->isEmpty())
                <button type="button"
                    class="hover:bg-red-600 px-3 py-1 rounded hs-accordion-toggle cursor-pointer flex flex-row gap-1 text-red-500 items-center hover:text-white"
                    data-cuenta-delete-id="{{ $cuenta->id_cuenta }}" aria-controls="cuentas-delete"
                    data-hs-overlay="#cuentas-delete">
                    <x-carbon-trash-can class="w-4 h-4 ms-auto" />
                    Eliminar
                </button>
            @endif
        </div>
    </div>
    @if ($cuenta->children->isNotEmpty())
        <div id="cuenta-accordion-{{ $cuenta->id_cuenta }}" class="hs-accordion-content border-t border-gray-200"
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
