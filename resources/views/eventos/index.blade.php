<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Eventos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <!-- Botón para abrir modal de creación -->
                <button onclick="openCreateModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4">
                    Crear Evento
                </button>

                <table class="w-full table-auto bg-white dark:bg-gray-700 border border-gray-300 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-200 dark:bg-gray-600">
                            <th class="px-4 py-2">Nombre</th>
                            <th class="px-4 py-2">Tipo</th>
                            <th class="px-4 py-2">Fecha</th>
                            <th class="px-4 py-2">Hora Inicio</th>
                            <th class="px-4 py-2">Hora Fin</th>
                            <th class="px-4 py-2">Ponente</th>
                            <th class="px-4 py-2">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($eventos as $evento)
                            <tr class="border-t border-gray-300 dark:border-gray-600" id="evento-{{ $evento->id }}">
                                <td class="px-4 py-2">{{ $evento->nombre }}</td>
                                <td class="px-4 py-2">{{ ucfirst($evento->tipo) }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($evento->fecha)->format('d-m-Y') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($evento->hora_inicio)->format('H:i') }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($evento->hora_fin)->format('H:i') }}</td>
                                <td class="px-4 py-2">{{ $evento->ponente->nombre }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                <button onclick="openEditModal({{ json_encode($evento) }})" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-3 rounded">Editar</button>
                                <button onclick="deleteEvento({{ $evento->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded">
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

    <!-- Modal para crear/editar evento -->
    <div id="modal" class="fixed inset-0 hidden bg-gray-900 bg-opacity-50 flex justify-center items-center">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-1/3">
            <h2 id="modal-title" class="text-lg font-bold mb-4">Crear Evento</h2>
            <form id="modal-form" onsubmit="event.preventDefault(); saveEvento();">
                @csrf
                <input type="hidden" id="evento-id">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Nombre</label>
                    <input type="text" id="nombre" name="nombre" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Tipo</label>
                    <select id="tipo" name="tipo" class="w-full border rounded p-2" required>
                        <option value="conferencia">Conferencia</option>
                        <option value="taller">Taller</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Fecha</label>
                    <input type="date" id="fecha" name="fecha" class="w-full border rounded p-2" required
                        min="2025-02-20" max="2025-02-21">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Hora de Inicio</label>
                    <select id="hora_inicio" name="hora_inicio" class="w-full border rounded p-2" required>
                        <option value="09:00">09:00</option>
                        <option value="10:00">10:00</option>
                        <option value="11:00">11:00</option>
                        <option value="12:00">12:00</option>
                        <option value="13:00">13:00</option>
                        <option value="16:00">16:00</option>
                        <option value="17:00">17:00</option>
                        <option value="18:00">18:00</option>
                        <option value="19:00">19:00</option>
                        <option value="20:00">20:00</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Hora de Fin</label>
                    <input type="time" id="hora_fin" name="hora_fin" class="w-full border rounded p-2" readonly required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Cupo Máximo</label>
                    <input type="number" id="cupo_maximo" name="cupo_maximo" class="w-full border rounded p-2" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2">Ponente</label>
                    <select id="ponente_id" name="ponente_id" class="w-full border rounded p-2" required>
                        @foreach($ponentes as $ponente)
                            <option value="{{ $ponente->id }}">{{ $ponente->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                <button type="button" onclick="closeModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancelar</button>
            </form>
        </div>
    </div>

    <script>
        function openCreateModal() {
            document.getElementById('modal-title').textContent = 'Crear Evento';
            document.getElementById('evento-id').value = '';
            document.getElementById('nombre').value = '';
            document.getElementById('tipo').value = 'conferencia'; // Valor por defecto
            document.getElementById('fecha').value = '';
            document.getElementById('hora_inicio').value = '';
            document.getElementById('hora_fin').value = '';
            document.getElementById('cupo_maximo').value = '';
            document.getElementById('ponente_id').value = '';
            document.getElementById('modal').classList.remove('hidden');
        }

        function openEditModal(evento) {
            // Asegúrate de que el objeto evento tiene los valores correctos
            console.log(evento);

            document.getElementById('modal-title').textContent = 'Editar Evento';
            document.getElementById('evento-id').value = evento.id;
            document.getElementById('nombre').value = evento.nombre;
            document.getElementById('tipo').value = evento.tipo;
            document.getElementById('fecha').value = evento.fecha;
            document.getElementById('hora_inicio').value = evento.hora_inicio;
            document.getElementById('hora_fin').value = evento.hora_fin;
            document.getElementById('cupo_maximo').value = evento.cupo_maximo;
            document.getElementById('ponente_id').value = evento.ponente_id;
            
            document.getElementById('modal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modal').classList.add('hidden');
        }

        // Calcula la hora de fin sumando 55 minutos a la hora de inicio
        document.getElementById('hora_inicio').addEventListener('change', function () {
            let horaInicio = this.value;
            if (horaInicio) {
                let horaFin = calculateHoraFin(horaInicio);
                document.getElementById('hora_fin').value = horaFin;
            }
        });

        function calculateHoraFin(horaInicio) {
            let [hours, minutes] = horaInicio.split(':');
            let date = new Date(2025, 0, 1, hours, minutes);  // Cualquier fecha, solo necesitamos la hora
            date.setMinutes(date.getMinutes() + 55);  // Sumamos 55 minutos
            let horaFin = date.toTimeString().split(' ')[0].slice(0, 5);
            return horaFin;
        }

        async function saveEvento() {
            let id = document.getElementById('evento-id').value;
            let url = id ? `/api/eventos/${id}` : '/api/eventos';
            let method = id ? 'PUT' : 'POST';

            let data = {
                nombre: document.getElementById('nombre').value,
                tipo: document.getElementById('tipo').value,
                fecha: document.getElementById('fecha').value,
                hora_inicio: document.getElementById('hora_inicio').value,
                hora_fin: document.getElementById('hora_fin').value,
                cupo_maximo: document.getElementById('cupo_maximo').value,
                ponente_id: document.getElementById('ponente_id').value
            };

            let response = await fetch(url, {
                method: method,
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(data)
            });

            if (response.ok) {
                location.reload();
            } else {
                let errorData = await response.json();
                alert('Error al guardar el evento: ' + errorData.message || 'Revisa los campos ingresados.');
            }
        }

        async function deleteEvento(id) {
            if (!confirm('¿Seguro que deseas eliminar este evento? Esta acción es irreversible.')) return;

            try {
                let response = await fetch(`/api/eventos/${id}`, {
                    method: 'DELETE',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    }
                });

                if (response.ok) {
                    document.getElementById(`evento-${id}`).remove();
                } else {
                    let errorData = await response.json();
                    alert('Error al eliminar: ' + (errorData.message || 'Intenta nuevamente.'));
                }
            } catch (error) {
                console.error('Error eliminando evento:', error);
                alert('Error de conexión. Revisa la consola para más detalles.');
            }
        }
    </script>
</x-app-layout>