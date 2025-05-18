<div class="{{ $cuenta->children->isNotEmpty() ? 'hs-accordion' : '' }}">

    <div class="grid grid-cols-6 items-center text-sm cursor-pointer hover:bg-gray-100 px-2 py-2 w-full"
        id="cuenta-{{ $cuenta->id_cuenta }}"
        onclick="seleccionarCuenta({{ $cuenta->id_cuenta }}, '{{ $cuenta->codigo_cuenta }}', '{{ $cuenta->nombre_cuenta }}', '{{ $cuenta->tipo_cuenta }}', '{{ $cuenta->nivel }}')">
        <div class="font-mono">
            {{ $cuenta->codigo_cuenta }}
        </div>
        <div>{{ $cuenta->nombre_cuenta }}</div>
        <div>{{ $cuenta->tipo_cuenta }}</div>
        <div>Nivel {{ $cuenta->nivel }}</div>
        <div>
            @if ($cuenta->es_movimiento)
                <span class="inline-block bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">Si</span>
            @else
                <span class="inline-block bg-gray-500 text-white text-xs font-semibold px-2 py-1 rounded">No</span>
            @endif
        </div>
        <div class="flex gap-2">
            @if ($cuenta->es_movimiento)
                {{-- <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                    onclick="event.stopPropagation(); abrirModalEditar(this)" data-id="{{ $cuenta->id_cuenta }}"
                    data-codigo="{{ $cuenta->codigo_cuenta }}" data-nombre="{{ $cuenta->nombre_cuenta }}"
                    data-tipo="{{ $cuenta->tipo_cuenta }}" data-nivel="{{ $cuenta->nivel }}">
                    Editar
                </button> --}}
                <a href="{{ route('show.cuentas.edit', ['id' => $cuenta->id_cuenta]) }}"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded">
                    Editar
                </a>
            @endif

            @if ($cuenta->children->isNotEmpty())
                <button type="button"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded hs-accordion-toggle"
                    aria-expanded="true" aria-controls="cuenta-accordion-{{ $cuenta->id_cuenta }}">
                    Expandir
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
