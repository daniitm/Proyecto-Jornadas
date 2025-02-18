<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ponentes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($ponentes as $ponente)
                            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                                <img src="{{ $ponente->fotografia }}" alt="{{ $ponente->nombre }}" class="w-full h-48 object-cover">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $ponente->nombre }}</h3>
                                    <p class="text-sm text-gray-600">{{ $ponente->areas_experiencia }}</p>
                                    <a href="{{ $ponente->enlace_red_social }}" target="_blank" class="text-blue-500 hover:underline">Red Social</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>