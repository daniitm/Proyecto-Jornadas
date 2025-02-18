<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Ponentes
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Botón para abrir modal de creación -->
                <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Crear Ponente
                </button>

                <table class="w-full table-auto bg-white dark:bg-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-600">
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Áreas de Experiencia</th>
                            <th class="px-4 py-2">Enlace Red Social</th>
                            <th class="px-4 py-2">Fotografía</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($ponentes as $ponente)
                            <tr class="border-t border-gray-300 dark:border-gray-600" id="ponente-{{ $ponente->id }}">
                                <td class="px-4 py-2">{{ $ponente->nombre }}</td>
                                <td class="px-4 py-2">{{ $ponente->areas_experiencia }}</td>
                                <td class="px-4 py-2">
                                    @if($ponente->enlace_red_social)
                                        <a href="{{ $ponente->enlace_red_social }}" target="_blank" class="text-blue-500">Ver Red Social</a>
                                    @else
                                        No disponible
                                    @endif
                                </td>
                                <td class="px-4 py-2">
                                    @if($ponente->fotografia)
                                        <img src="{{ $ponente->fotografia }}" alt="Fotografía de {{ $ponente->nombre }}" class="h-16">
                                    @else
                                        No disponible
                                    @endif
                                </td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <button onclick="openEditModal({{ $ponente }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Editar</button>
                                    <button onclick="deletePonente({{ $ponente->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
                                        Eliminar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para crear/editar -->
    <div id="modal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
            <h2 id="modal-title" class="text-lg font-bold mb-4">Crear Ponente</h2>
            <form id="modal-form" onsubmit="event.preventDefault(); savePonente();">
                @csrf
                <input type="hidden" id="ponente-id">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Áreas de Experiencia</label>
                    <input type="text" id="areas_experiencia" name="areas_experiencia" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Enlace Red Social</label>
                    <input type="url" id="enlace_red_social" name="enlace_red_social" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Fotografía (URL)</label>
                    <input type="url" id="fotografia" name="fotografia" class="w-full border rounded p-2" required>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Crear Ponente';
            document.getElementById('ponente-id').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('areas_experiencia').value = '';
            document.getElementById('enlace_red_social').value = '';
            document.getElementById('fotografia').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function openEditModal(ponente) {
            document.getElementById('modal-title').textContent = 'Editar Ponente';
            document.getElementById('ponente-id').value = ponente.id;
            document.getElementById('nombre').value = ponente.nombre;
            document.getElementById('areas_experiencia').value = ponente.areas_experiencia;
            document.getElementById('enlace_red_social').value = ponente.enlace_red_social;
            document.getElementById('fotografia').value = ponente.fotografia;
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        async function savePonente() {
            let id = document.getElementById('ponente-id').value;
            let url = id ? `/api/ponentes/${id}` : '/api/ponentes';
            let method = id ? 'PUT' : 'POST';

            let data = {
                nombre: document.getElementById('nombre').value,
                areas_experiencia: document.getElementById('areas_experiencia').value,
                enlace_red_social: document.getElementById('enlace_red_social').value,
                fotografia: document.getElementById('fotografia').value
            };

            let response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                location.reload();
            } else {
                alert('Error al guardar el ponente');
            }
        }

        async function deletePonente(id) {
            if (!confirm('¿Seguro que deseas eliminar este ponente?')) return;

            try {
                let response = await fetch(`/api/ponentes/${id}`, {
                    method: 'DELETE',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    }
                });

                if (response.ok) {
                    document.getElementById(`ponente-${id}`).remove();
                } else {
                    let errorData = await response.json();
                    alert('Error al eliminar: ' + (errorData.message || 'Intenta nuevamente.'));
                }
            } catch (error) {
                console.error('Error eliminando ponente:', error);
                alert('Error de conexión. Revisa la consola para más detalles.');
            }
        }
    </script>
</x-app-layout>