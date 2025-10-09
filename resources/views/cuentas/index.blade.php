@extends('layouts.admin')

@section('content')
    <div class="bg-white h-screen">
        <div class="w-full mx-auto">
            <div class="flex flex-col gap-6">
                <!-- Header -->
                <div class="flex justify-between items-center bg-blue-600 px-10 py-5">
                    <h3 class="text-white text-2xl font-semibold">Plan de Cuentas</h3>
                </div>

                <!-- Table -->
                @include('cuentas.partials.planCuentas')
            </div>
        </div>
    </div>
@endsection
