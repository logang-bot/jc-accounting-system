@extends('layouts.admin')

@section('content')
    <div class="h-screen bg-white transition-all duration-200">
        <!-- Background section -->
        <div class="bg-blue-600 pt-10 pb-[84px]"></div>

        <!-- Container with negative margin -->
        <div class="max-w-7xl mx-auto -mt-20 px-6">
            <div class="w-full">
                <div class="flex flex-col lg:flex-row justify-between items-center">
                    <!-- Welcome text -->
                    <div class="mb-4 lg:mb-0">
                        <h3 class="text-white text-2xl font-semibold mb-0">Welcome {{ Auth::user()->name }}</h3>
                    </div>
                    <!-- Button -->
                    <div>
                        <a href="#"
                            class="bg-white text-blue-600 font-medium px-4 py-2 rounded-md hover:bg-gray-100 transition">
                            Create New Project
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
