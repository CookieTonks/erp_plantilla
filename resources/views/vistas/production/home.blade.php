<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Produccion / Ordenes
        </h2>

    </x-slot>

    <div class="container">
        <div class="py-5">

            <div class="row justify-content-center">
                <!-- Módulo 1 -->
                <div class="col-12 col-sm-6">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                OT pendientes: {{$totales['sin_asignar']}}
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Módulo 3 -->
                <div class="col-12 col-sm-6">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                OT Proceso: {{$totales['en_proceso']}}
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
                        <!-- <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterial">
                            +
                        </button> -->
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
                                <th data-field="ot" data-sortable="true">OT</th>
                                <th data-field="descripcion" data-sortable="true">Descripcion</th>
                                <th data-field="cantidad" data-sortable="true">Cantidad</th>
                                <th data-field="estado" data-sortable="true">Estado</th>
                                <th data-field="tecnico" data-sortable="true">Tecnico</th>
                                <th data-field="material" data-sortable="true">Material</th>
                                <th data-field="acciones" data-sortable="true">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($ordenes as $orden)
                            <tr>
                                <td>OT-{{$orden->budget->id}}_{{$orden->id}}</td>
                                <td>{{$orden->descripcion}}</td>
                                <td>{{$orden->cantidad}}</td>
                                <td>{{$orden->estado}}</td>
                                <td> {{ $orden->tecnicos ? $orden->tecnicos->name : 'Sin asignar' }} </td>
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#itemsModal"
                                        onclick="loadItems({{ $orden->id }})">
                                        Ver Material
                                    </button>
                                </td>
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-primary mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#asignarTecnicoModal{{$orden->id}}">
                                        Técnico
                                    </button>
                                    <div class="modal fade" id="asignarTecnicoModal{{$orden->id}}" tabindex="-1" aria-labelledby="asignarTecnicoLabel{{$orden->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="asignarTecnicoLabel{{$orden->id}}">Asignar Técnico - OT-{{$orden->budget->id}}_{{$orden->id}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('production.tecnico.ot', $orden->id) }}" method="POST">
                                                        @csrf
                                                        <div class="form-group">
                                                            <label for="tecnico{{$orden->id}}">Seleccionar Técnico</label>
                                                            <select class="form-control" id="tecnico{{$orden->id}}" name="tecnico_id" required>
                                                                <option value="" disabled selected>Seleccione un técnico</option>
                                                                @foreach($tecnicos as $tecnico)
                                                                <option value="{{ $tecnico->id }}">{{ $tecnico->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <button type="submit" class="btn btn-success mt-3">Asignar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#liberarOTModal{{$orden->id}}">
                                        Liberar
                                    </button>
                                    <div class="modal fade" id="liberarOTModal{{$orden->id}}" tabindex="-1" aria-labelledby="asignarTecnicoLabel{{$orden->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="asignarTecnicoLabel{{$orden->id}}">Liberar - OT-{{$orden->budget->id}}_{{$orden->id}}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('production.liberacion.ot', $orden->id) }}" method="POST">
                                                        @csrf

                                                        <button type="submit" class="btn btn-success mt-3">Liberar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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

    <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemsModalLabel">Material solicitado</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Medida</th>
                                <th>Estatus</th>
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
        function loadItems(ItemId) {
            // Realiza una solicitud AJAX para obtener los items del presupuesto
            fetch(`/budgets/order/${ItemId}/materials`)
                .then(response => response.json())
                .then(items => {
                    const tbody = document.getElementById('itemsTableBody');
                    tbody.innerHTML = ''; // Limpiar datos anteriores
                    items.forEach(item => {
                        const row = `
                    <tr>
                        <td>${item.descripcion}</td>
                        <td>${item.cantidad}</td>
                        <td>${item.unidad}</td>
                        <td>${item.medida}</td>
                        <td>${item.estatus}</td>

                    </tr>
                `;
                        tbody.innerHTML += row;
                    });
                })
                .catch(error => console.error('Error al cargar los items:', error));
        }
    </script>





    <!-- Modales -->


</x-app-layout>