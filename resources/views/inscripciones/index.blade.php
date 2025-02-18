<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Inscripciones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Tabla de inscripciones -->
                <table class="w-full table-auto bg-white dark:bg-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-600">
                            <th class="px-4 py-2">Evento</th>
                            <th class="px-4 py-2">Usuario</th>
                            <th class="px-4 py-2">Fecha de Inscripción</th>
                            <th class="px-4 py-2">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($inscripciones as $inscripcion)
                            <tr class="border-t border-gray-300 dark:border-gray-600" id="inscripcion-{{ $inscripcion->id }}">
                                <td class="px-4 py-2">{{ $inscripcion->evento->nombre }}</td>
                                <td class="px-4 py-2">{{ $inscripcion->user->name }}</td> <!-- Cambié 'usuario' a 'user' -->
                                <td class="px-4 py-2">{{ $inscripcion->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2">
                                    @if($inscripcion->evento->cupo_maximo <= $inscripcion->evento->inscripciones()->count())
                                        <span class="text-red-500">Cupo completo</span>
                                    @else
                                        <span class="text-green-500">Inscripción válida</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>