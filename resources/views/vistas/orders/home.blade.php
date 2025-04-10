<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Orden Trabajo/ Home
        </h2>
        <div class="container">

            <div class="py-5">

                <div class="row justify-content-center">
                    <!-- Módulo 1 -->




                    <!-- Módulo 3 -->
                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                    Cotizaciones proceso: {{$totales['en_proceso']}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
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
                                    <th data-field="id" data-sortable="true">Codigo</th>
                                    <th data-field="orderNumber" data-sortable="true">Empresa</th>
                                    <th data-field="supplier" data-sortable="true">Usuario</th>
                                    <th data-field="sales" data-sortable="true">Vendedor</th>
                                    <th data-field="oc" data-sortable="true">OC</th>
                                    <th data-field="status" data-sortable="true">Estado</th>
                                    <th data-field="acciones" data-sortable="true">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($budgets as $budget)
                                <tr>
                                    <td>{{$budget->codigo}}</td>
                                    <td>{{ $budget->client?->name ?? 'Empresa no asignada' }}</td>
                                    <td> {{ $budget->clientUser?->name ?? 'Usuario no asignado' }} </td>
                                    <td>{{ $budget->user?->name ?? 'Vendedor no asignado' }}</td>
                                    <td>{{$budget->oc_number}}</td>
                                    <td>{{$budget->estado}}</td>
                                    <td>
                                        <a href="{{ route('budgets.show.orders', ['budgetId' => $budget->id]) }}" class="btn btn-success btn-sm">
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


        <!-- Modal de Materiales -->
        <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemsModalLabel">Materiales Asignados</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <div class="modal-body">

                        <div id="materialSummary" class="mb-3">
                            <strong> Partidas material recibido:</strong> <span id="receivedStatus">0/0</span>
                        </div>
                        <!-- Aquí se cargarán los materiales -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th>Cantidad</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Aquí se agregan los materiales mediante JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Función para cargar los materiales en el modal
            function loadItems(budgetId) {
                // Realizar la solicitud a tu API o a una ruta para obtener los materiales
                fetch(`/budgets/${budgetId}/materials`) // Esta URL debe estar en tu archivo de rutas
                    .then(response => response.json())
                    .then(data => {
                        // Limpiar la tabla antes de cargar nuevos datos
                        let tableBody = document.getElementById('itemsTableBody');
                        tableBody.innerHTML = '';


                        const summary = data.summary;
                        document.getElementById('receivedStatus').innerText = `${summary.received}/${summary.total}`;


                        // Recorrer los materiales y agregarlos a la tabla
                        data.materials.forEach(material => {
                            let row = document.createElement('tr');
                            row.innerHTML = `
                    <td>${material.descripcion}</td>
                    <td>${material.cantidad}</td>
                    <td>${material.estatus}</td>
                `;
                            tableBody.appendChild(row);
                        });
                    })
                    .catch(error => {
                        console.error('Error al cargar los materiales:', error);
                    });
            }
        </script>
    </x-slot>
</x-app-layout>