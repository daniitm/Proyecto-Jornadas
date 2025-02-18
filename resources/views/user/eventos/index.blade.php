<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Eventos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="bg-green-500 text-white p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-500 text-white p-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <ul class="space-y-6">
                        @foreach($eventos as $evento)
                            <li class="bg-white shadow-md rounded-lg overflow-hidden">
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold">{{ $evento->nombre }}</h3>
                                    <p class="text-sm text-gray-600">Tipo: {{ ucfirst($evento->tipo) }}</p>
                                    <p class="text-sm text-gray-600">Fecha: {{ $evento->fecha->format('d/m/Y') }}</p>
                                    <p class="text-sm text-gray-600">Hora de inicio: {{ $evento->hora_inicio->format('H:i') }}</p>
                                    <p class="text-sm text-gray-600">Hora de fin: {{ $evento->hora_fin->format('H:i') }}</p>
                                    <p class="text-sm font-medium mt-2">Ponente: {{ $evento->ponente->nombre }}</p>

                                    <!-- Formulario de inscripción sin selector -->
                                    <form action="{{ route('eventos.inscribirse', $evento->id) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                            Inscribirse
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>