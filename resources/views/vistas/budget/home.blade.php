<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Cotizaciones / {{ $estado ? ucfirst(strtolower($estado)) : 'Todas' }}
        </h2>

    </x-slot>

    <div class="container">
        <div class="py-5">

            <div class="row justify-content-center">
                <!-- Módulo 1 -->
                <div class="col-12 col-sm-2">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'ABIERTA']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Cotizaciones abiertas: {{$totales['abiertas']}}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-2">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'ENVIADA']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Cotizaciones enviadas: {{$totales['enviadas']}}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Módulo 2 -->
                <div class="col-12 col-sm-2">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'PENDIENTE']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Cotizaciones pendientes: {{$totales['pendientes']}}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Módulo 3 -->
                <div class="col-12 col-sm-2">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'PROCESO']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Cotizaciones proceso: {{$totales['en_proceso']}}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-2">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'ENTREGADAS']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Cotizaciones cerradas: {{$totales['entregadas']}}
                            </a>
                        </div>
                    </div>
                </div>




            </div>
        </div>


        <div class="py-2">
            <div class="row">
                <div class="table-responsive">
                    <div id="toolbar">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                            +
                        </button>
                    </div>
                    <table id="orders-table"
                        class="table table-striped table-bordered"
                        data-toggle="table"
                        data-search="true"
                        data-pagination="true"
                        data-show-columns="true"
                        data-show-refresh="true"
                        data-page-list="[5, 10, 20, All]"
                        data-toolbar="#toolbar">
                        <thead class="thead-dark">
                            <tr>
                                <th data-field="id" data-sortable="true">ID</th>
                                <th data-field="orderNumber" data-sortable="true">Empresa</th>
                                <th data-field="supplier" data-sortable="true">Usuario</th>
                                <th data-field="sales" data-sortable="true">Vendedor</th>
                                <th data-field="status" data-sortable="true">Estado</th>
                                <th data-field="partidas" data-sortable="true">Partidas</th>
                                <th data-field="creada" data-sortable="true">Creada</th>
                                <th data-field="acciones" data-sortable="true">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($budgets->sortByDesc(fn($b) => $b->estado === 'PENDIENTE' ? 1 : 0) as $budget)
                            <tr class="{{ $budget->estado === 'PENDIENTE' ? 'table-danger' : '' }}">
                                <td>{{$budget->codigo}}</td>
                                <td>{{$budget->client?->name ?? 'Empresa no asignada' }}</td>
                                <td>{{$budget->clientUser?->name ?? 'Usuario no asignado' }}</td>
                                <td>{{$budget->user?->name ?? 'Vendedor no asignado' }}</td>
                                <td>{{$budget->estado}}</td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#itemsModal"
                                        onclick="loadItems({{ $budget->id }})">
                                        Ver Partidas
                                    </button>
                                </td>
                                <td>{{$budget->created_at}}</td>
                                <td>
                                    <a href="{{ route('budgets.show', ['budgetId' => $budget->id]) }}" class="btn btn-success btn-sm">
                                        Opciones
                                    </a>
                                </td>
                            </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>



    <!-- Modales -->

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Formulario de Nueva Cotización</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('budgets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <!-- Datos de la OC -->
                        <div class="mb-3">
                            <label for="client" class="form-label">Cliente</label>
                            <select class="form-control" id="client" name="client" required>
                                <option value="">Selecciona una empresa</option>

                                @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="clientUser" class="form-label">Usuario de Cliente</label>
                            <select class="form-control" id="clientUser" name="clientUser" disabled>
                                <option value="">Selecciona un usuario de cliente</option>
                            </select>
                        </div>

                        <!-- Datos de la OC -->
                        <div class="mb-3">
                            <label for="moneda" class="form-label">Moneda</label>
                            <select class="form-control" id="moneda" name="moneda" required>
                                <option value="MXN">MXN</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="delivery_time" class="form-label">Tiempo de Entrega</label>
                            <input type="number" class="form-control" id="delivery_time" name="delivery_time" required>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered" id="items-table">
                                <thead>
                                    <tr>
                                        <th>Descripción</th>
                                        <th>Cantidad</th>
                                        <th>P/U</th>
                                        <th>PDF</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Fila inicial de ejemplo -->
                                    <tr>
                                        <td>
                                            <input type="text" class="form-control" name="items[0][descripcion]" placeholder="Descripción" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" name="items[0][cantidad]" placeholder="Cantidad" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control" step="0.01" name="items[0][precio_unitario]" placeholder="Precio Unitario" required>
                                        </td>
                                        <td>
                                            <input type="file" class="form-control" name="items[0][pdf]" accept="pdf/*">
                                        </td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-danger btn-sm delete-row">Eliminar</button>
                                        </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- Botón para agregar filas -->
                        <button type="button" id="add-row" class="btn btn-primary mb-3"> + Partida</button>
                        <button type="submit" class="btn btn-success mb-3">Guardar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">Detalles de la cotizacion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Cotizacion</th>
                                <th>Partida</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                                <th>PDF</th>

                            </tr>
                        </thead>
                        <tbody id="itemsTableBody">
                            <!-- Se llenará dinámicamente -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>
        let itemIndex = 1;

        // Agregar fila
        document.getElementById('add-row').addEventListener('click', () => {
            const tableBody = document.querySelector('#items-table tbody');
            const newRow = `
            <tr>
                <td>
                    <input type="text" class="form-control" name="items[${itemIndex}][descripcion]" placeholder="Descripción">
                </td>
                <td>
                    <input type="number" class="form-control" name="items[${itemIndex}][cantidad]" placeholder="Cantidad">
                </td>
                <td>
                    <input type="number" class="form-control" step="0.01" name="items[${itemIndex}][precio_unitario]" placeholder="Precio Unitario">
                </td>
                <td>
                    <input type="file" class="form-control" name="items[0][pdf]" accept="pdf/*">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm delete-row">Eliminar</button>
                </td>
                
                
            </tr>
        `;
            tableBody.insertAdjacentHTML('beforeend', newRow);
            itemIndex++;
        });

        // Eliminar fila
        document.querySelector('#items-table').addEventListener('click', (event) => {
            if (event.target.classList.contains('delete-row')) {
                const row = event.target.closest('tr');
                row.remove();
            }
        });
    </script>

    <script>
        function loadItems(budgetId) {
            // Realiza una solicitud AJAX para obtener los items del presupuesto
            fetch(`/budgets/${budgetId}/items`)
                .then(response => response.json())
                .then(items => {
                    const tbody = document.getElementById('itemsTableBody');
                    tbody.innerHTML = ''; // Limpiar datos anteriores
                    items.forEach(item => {
                        const row = `
                    <tr>
                        <td>COT - ${budgetId}</td>
                        <td>${item.partida}</td>
                        <td>${item.descripcion}</td>
                        <td>${item.cantidad}</td>
                        <td>${item.precio_unitario}</td>
                        <td>${item.subtotal}</td>
                        <td>
                            <a href="/storage/${item.imagen}" target="_blank">Ver PDF</a>
                        </td>
                    </tr>
                `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error al cargar los items:', error));
        }
    </script>


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