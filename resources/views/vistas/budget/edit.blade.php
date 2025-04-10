<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="py-12">
                <form action="{{ route('budgets.update', ['budgetId' => $budget->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <!-- Datos de la OC -->
                    <div class="mb-3">
                        <label for="client" class="form-label">Empresa</label>
                        <select class="form-control" id="client" name="client" required>
                            <option value="{{ $budget->client_id }}" selected>{{ $budget->client->name }}</option>
                            @foreach ($clients as $client)
                            <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="clientUser" class="form-label">Usuario de Empresa</label>
                        <select class="form-control" id="clientUser" name="client_user_id" disabled>
                            <option value="{{ $budget->client_user_id }}" selected>{{ $budget->clientUser->name }}</option>
                        </select>
                    </div>

                    <!-- Datos de la OC -->
                    <div class="mb-3">
                        <label for="moneda" class="form-label">Moneda</label>
                        <select class="form-control" id="moneda" name="moneda" required>
                            <option value="{{ $budget->moneda }}" selected>{{ $budget->moneda }}</option>
                            <option value="MXN">MXN</option>
                            <option value="USD">USD</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="delivery_time" class="form-label">Tiempo de Entrega</label>
                        <input type="number" value="{{ $budget->delivery_time }}" class="form-control" id="delivery_time" name="delivery_time" required>
                    </div>

                    <div class="mb-3" id="ocInputContainer">
                        <label for="ocNumber" class="form-label">Número de Orden de Compra (OC)</label>
                        <input type="text" value="{{ $budget->oc_number }}" class="form-control" id="ocNumber" name="oc_number">
                    </div>

                    <div class="modal-footer">
                        <a href="{{ route('budgets.show', ['budgetId' => $budget->id]) }}" class="btn btn-secondary mb-3" style="margin-right: 15px;">Regresar</a>
                        <button type="submit" class="btn btn-success mb-3">Guardar</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Event listener para detectar cuando cambia el cliente seleccionado
            document.getElementById('client').addEventListener('change', function() {
                var clientId = this.value;
                var clientUserSelect = document.getElementById('clientUser');

                if (clientId) {
                    // Hacer una petición para obtener los usuarios del cliente
                    fetch(`/getClientUsers/${clientId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Limpiar las opciones actuales
                            clientUserSelect.innerHTML = '<option value="">Selecciona un usuario de cliente</option>';

                            // Habilitar el campo de selección
                            clientUserSelect.disabled = false;

                            // Llenar el select con los usuarios de cliente
                            data.forEach(function(clientUser) {
                                var option = document.createElement('option');
                                option.value = clientUser.id;
                                option.textContent = clientUser.name;
                                clientUserSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching client users:', error);
                            clientUserSelect.disabled = true; // Deshabilitar en caso de error
                        });
                } else {
                    // Si no hay cliente seleccionado, deshabilitar el campo de usuario de cliente
                    clientUserSelect.innerHTML = '<option value="">Selecciona un usuario de cliente</option>';
                    clientUserSelect.disabled = true;
                }
            });
        </script>
</x-app-layout>