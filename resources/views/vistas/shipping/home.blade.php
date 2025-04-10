<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Embarques / Ordenes de Trabajo
        </h2>

    </x-slot>

    <div class="container">
        <div class="py-5">

            <div class="row justify-content-center">
                <!-- Módulo 1 -->
                <div class="col-12 col-sm-6">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index', ['estado' => 'ABIERTA']) }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Ordenes pendientes: {{$contador}}
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
                        <a href="{{ route('shipping.entregas') }}" class="btn btn-primary">
                            Entregas Archivos
                        </a>

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
                                <th data-field="orderNumber" data-sortable="true">Empresa</th>
                                <th data-field="supplier" data-sortable="true">Usuario</th>
                                <th data-field="sales" data-sortable="true">Vendedor</th>
                                <th data-field="oc" data-sortable="true">OC</th>
                                <th data-field="descripcion" data-sortable="true">Descripcion</th>
                                <th data-field="cantidad" data-sortable="true">Cantidad</th>
                                <th data-field="status" data-sortable="true">Estado</th>
                                <th data-field="acciones" data-sortable="true">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($ordenes as $orden)
                            <tr>
                                <td>OT-{{$orden->budget->id}}_{{$orden->id}}</td>
                                <td>{{$orden->budget->client->name}}</td>
                                <td>{{$orden->budget->clientUser->name}}</td>
                                <td>{{$orden->budget->user->name}}</td>
                                <td>{{$orden->budget->oc_number}}</td>
                                <td>{{$orden->descripcion}}</td>
                                <td>{{$orden->cantidad}}</td>
                                <td>{{$orden->estado}}</td>
                                <td>
                                    <!-- Botón para abrir el modal -->
                                    <button type="button"
                                        class="btn btn-success mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#historialModal"
                                        onclick="cargarHistorial({{ $orden->id }})">
                                        Historial
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="historialModal" tabindex="-1" aria-labelledby="historialModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="historialModalLabel">Historial de Entregas</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                                </div>
                                                <div class="modal-body">
                                                    <table class="table table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th>Salida</th>
                                                                <th>Cantidad</th>
                                                                <th>Tipo</th>
                                                                <th>Fecha</th>
                                                                <th>Entrega</th>
                                                                <th>Recibe</th>


                                                            </tr>
                                                        </thead>
                                                        <tbody id="historialBody">
                                                            <!-- Los datos se cargarán aquí con AJAX -->
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#facturaOTModal{{$orden->id}}">
                                        Factura
                                    </button>
                                    <div class="modal fade" id="facturaOTModal{{$orden->id}}" tabindex="-1" aria-labelledby="asignarTecnicoLabel{{$orden->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-body">
                                                    <p><strong>SAL - {{$next_id}}</strong></p>
                                                    <p>Descripción:{{$orden->descripcion}}</p>
                                                    <form action="{{ route('shipping.ot.salida_factura', $orden->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="cantidad" class="form-label"><strong>Cantidad Enviada:</strong></label>
                                                            <input type="number" class="form-control" name="cantidad" id="cantidad" required min="1">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tipo_documento" class="form-label"><strong>Tipo de salida:</strong></label>
                                                            <input type="text" class="form-control" name="tipo_documento" id="tipo_documento" value="FACTURA" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="razon_social" class="form-label"><strong>Razon Social:</strong></label>
                                                            <select class="form-control" name="razon_social" id="razon_social" required>
                                                                <option value="MAQUINADOS BADILSA S.A DE C.V">MAQUINADOS BADILSA S.A DE C.V</option>
                                                                <option value="RICARDO JAVIER BADILLO AMAYA">RICARDO JAVIER BADILLO AMAYA</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="persona_entrega" class="form-label"><strong>Entrega:</strong></label>
                                                            <input type="text" class="form-control" name="persona_entrega" id="persona_entrega" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="persona_recibe" class="form-label"><strong>Recibe:</strong></label>
                                                            <input type="text" class="form-control" name="persona_recibe" id="persona_recibe" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="numero_documento" class="form-label"><strong>Número de Factura:</strong></label>
                                                            <input type="text" class="form-control" name="numero_documento" id="numero_documento" required>
                                                        </div>

                                                        <div class="mb-3 form-check">
                                                            <input type="checkbox" class="form-check-input" id="ultima_entrega" name="ultima_entrega" value="1">
                                                            <label class="form-check-label" for="ultima_entrega">Última entrega</label>
                                                        </div>

                                                        <button type="submit" class="btn btn-success mt-3">Liberar</button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#remisionOTModal{{$orden->id}}">
                                        Remision
                                    </button>
                                    <div class="modal fade" id="remisionOTModal{{$orden->id}}" tabindex="-1" aria-labelledby="asignarTecnicoLabel{{$orden->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="asignarTecnicoLabel{{$orden->id}}">
                                                        <p><strong>SAL - {{$next_id}}</strong></p>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p><strong>Descripción:</strong> {{$orden->descripcion}}</p>
                                                    <form action="{{ route('shipping.ot.salida_remision', $orden->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="cantidad" class="form-label"><strong>Cantidad Enviada:</strong></label>
                                                            <input type="number" class="form-control" name="cantidad" id="cantidad" required min="1">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="tipo_documento" class="form-label"><strong>Tipo de salida:</strong></label>
                                                            <input type="text" class="form-control" name="tipo_documento" id="tipo_documento" value="REMISION" readonly>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="razon_social" class="form-label"><strong>Razon Social:</strong></label>
                                                            <select class="form-control" name="razon_social" id="razon_social" required>
                                                                <option value="MAQUINADOS BADILSA S.A DE C.V">MAQUINADOS BADILSA S.A DE C.V</option>
                                                                <option value="RICARDO JAVIER BADILLO AMAYA">RICARDO JAVIER BADILLO AMAYA</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="persona_entrega" class="form-label"><strong>Entrega:</strong></label>
                                                            <input type="text" class="form-control" name="persona_entrega" id="persona_entrega" required>
                                                        </div>

                                                        <div class="mb-3">
                                                            <label for="persona_recibe" class="form-label"><strong>Recibe:</strong></label>
                                                            <input type="text" class="form-control" name="persona_recibe" id="persona_recibe" required>
                                                        </div>



                                                        <div class="mb-3 form-check">
                                                            <input type="checkbox" class="form-check-input" id="ultima_entrega" name="ultima_entrega" value="1">
                                                            <label class="form-check-label" for="ultima_entrega">Última entrega</label>
                                                        </div>

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


    <script>
        function cargarHistorial(id) {
            fetch(`/historial/${id}`)
                .then(response => response.json())
                .then(data => {
                    let tbody = document.getElementById("historialBody");
                    tbody.innerHTML = "";

                    if (data.length === 0) {
                        tbody.innerHTML = "<tr><td colspan='4' class='text-center'>No hay entregas registradas</td></tr>";
                    } else {
                        data.forEach(entrega => {
                            let row = `<tr>
                        <td>SAL - ${entrega.id}</td>
                        <td>${entrega.cantidad}</td>
                        <td>${entrega.tipo_documento}</td>
                        <td>${entrega.created_at}</td>
                        <td>${entrega.persona_entrega}</td>
                        <td>${entrega.persona_recibe}</td>

                    </tr>`;
                            tbody.innerHTML += row;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>




    <!-- Modales -->

</x-app-layout>