@php
    $accordionId = 'cuenta-accordion-' . $cuenta->id_cuenta . '-' . $nivel;
@endphp

<div class="hs-accordion" id="cuenta-wrapper-{{ $cuenta->id_cuenta }}-{{ $nivel }}">
    <div class="grid grid-cols-6 items-center text-sm cursor-pointer hover:bg-gray-100 px-4 py-2"
        onclick="seleccionarCuenta({{ $cuenta->id_cuenta }}, '{{ $cuenta->codigo_cuenta }}', '{{ $cuenta->nombre_cuenta }}', '{{ $cuenta->tipo_cuenta }}', '{{ $cuenta->nivel }}')">
        <div class="font-mono" style="padding-left: {{ $nivel * 16 }}px;">
            {{ $cuenta->codigo_cuenta }}
        </div>
        <div>{{ $cuenta->nombre_cuenta }}</div>
        <div>{{ $cuenta->tipo_cuenta }}</div>
        <div>Nivel {{ $cuenta->nivel }}</div>
        <div>
            @if ($cuenta->es_movimiento)
                <span
                    class="inline-block bg-green-600 text-white text-xs font-semibold px-2 py-1 rounded">Movimiento</span>
            @else
                <span
                    class="inline-block bg-gray-500 text-white text-xs font-semibold px-2 py-1 rounded">Agrupador</span>
            @endif
        </div>
        <div class="flex gap-2">
            <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded"
                onclick="event.stopPropagation(); abrirModalEditar(this)" data-id="{{ $cuenta->id_cuenta }}"
                data-codigo="{{ $cuenta->codigo_cuenta }}" data-nombre="{{ $cuenta->nombre_cuenta }}"
                data-tipo="{{ $cuenta->tipo_cuenta }}" data-nivel="{{ $cuenta->nivel }}">
                Editar
            </button>
            @if ($cuenta->children->isNotEmpty())
                <button type="button" class="bg-blue-500 text-white px-3 py-1 rounded hs-accordion-toggle"
                    data-hs-collapse="#{{ $accordionId }}" onclick="event.stopPropagation()">
                    Expandir
                </button>
            @endif
        </div>
    </div>

    @if ($cuenta->children->isNotEmpty())
        <div id="{{ $accordionId }}" class="pl-4 border-t border-gray-200">
            @foreach ($cuenta->children as $child)
                @include('cuentas.partials.fila_cuenta', [
                    'cuenta' => $child,
                    'nivel' => $nivel + 1,
                ])
            @endforeach
        </div>
    @endif
</div>
