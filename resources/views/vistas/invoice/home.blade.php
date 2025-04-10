<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Facturacion / Home
        </h2>
        <div class="container">
            <div class="py-5">

                <div class="row justify-content-center">
                    <!-- Módulo 1 -->
                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                    Partidas de OC sin factura:
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                    Partidas de OC en proceso:
                                </a>
                            </div>
                        </div>
                    </div>

                </div>


            </div>

            <div class="py-2">
                <div class="row">
                    <!-- Tabla existente -->
                    <div class="col-md-8">
                        <div class="table-responsive">
                            <div id="toolbar"></div>
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
                                        <th data-field="ot" data-sortable="true">OC</th>
                                        <th data-field="orderNumber" data-sortable="true">Empresa</th>
                                        <th data-field="sales" data-sortable="true">Vendedor</th>
                                        <th data-field="cantidad" data-sortable="true">Descripcion</th>
                                        <th data-field="descripcion" data-sortable="true">Cantidad</th>
                                        <th data-field="factura" data-sortable="true">Factura</th>
                                        <th data-field="status" data-sortable="true">Estado</th>
                                        <th data-field="acciones" data-sortable="true">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach($partidas as $partida)
                                    <tr>
                                        <td>{{$partida->budget->oc_number}}</td>
                                        <td>{{$partida->budget->client->name}}</td>
                                        <td>{{$partida->budget->user->name}}</td>
                                        <td>{{$partida->descripcion}}</td>
                                        <td>{{$partida->cantidad}}</td>
                                        <td>{{$partida->invoice->codigo}}</td>
                                        <td>{{$partida->estado}}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#partidaOc-{{ $partida->id }}"
                                                data-material-id="{{ $partida->id }}">
                                                FACTURA
                                            </button>
                                        </td>
                                        <div class="modal fade" id="partidaOc-{{ $partida->id }}" tabindex="-1" aria-labelledby="materialOcLabel-{{ $partida->id }}" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="partidaOcLabel-{{ $partida->id }}">Asignar OC</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <form action="{{ route('factura.partida.oc', ['id' => $partida->id]) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('POST')

                                                            <!-- Campo oculto para el ID del material -->
                                                            <input type="hidden" name="partida_id" value="{{ $partida->id }}">

                                                            <!-- Selector de OC -->
                                                            <div class="mb-3">
                                                                <label for="oc_id-{{ $partida->id }}" class="form-label">OC</label>
                                                                <select class="form-control" id="oc_id-{{ $partida->id }}" name="factura_id" required>
                                                                    @foreach ($facturas as $factura)
                                                                    <option value="{{ $factura->id }}">{{ $factura->codigo }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                                                                <button type="submit" class="btn btn-success">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </tr>

                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Nueva columna para mostrar las OC -->
                    <div class="col-md-4">
                        <div class="card">
                            <!-- Encabezado de la tarjeta -->
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <span>FACTURAS</span>
                                <!-- Botón alineado dentro del header -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#itemsModal" class="btn btn-primary mb-3"> + </button>
                            </div>

                            <!-- Cuerpo de la tarjeta -->
                            <div class="card-body">
                                <ul class="list-group">

                                    @foreach($facturas as $factura)
                                    <li class="list-group-item">
                                        <strong>Factura:</strong> {{ $factura->codigo }}<br>
                                        <strong>Cliente:</strong> {{ $factura->client->name }}<br>
                                        <strong>Estado:</strong> {{ $factura->estatus }}
                                        <br>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ocPartidas"
                                            onclick="loadPartidas({{ $factura->id }})">
                                            Ver Partidas
                                        </button>
                                        <button class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#changeStatusModal"
                                            data-factura-id="{{ $factura->id }}"
                                            data-estatus="{{ $factura->estatus }}"
                                            onclick="openStatusModal(this)">
                                            Cambiar Estatus
                                        </button>



                                    </li>
                                    @endforeach



                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>


        <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="changeStatusModalLabel">Cambiar Estatus de la Factura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para enviar el estatus -->
                        <form id="statusForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="facturaId" name="facturaId">

                            <div class="mb-3">
                                <label for="newStatus" class="form-label">Nuevo Estatus</label>
                                <select class="form-select" id="newStatus" name="estatus" required>
                                    <option value="pendiente">Pendiente</option>
                                    <option value="Cliente">Enviada al cliente</option>
                                    <option value="Portal">En portal</option>
                                    <option value="Pagada">Pagada</option>
                                    <option value="Cancelada">Cancelada</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-success">Guardar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>




        <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="modifyItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyItemModalLabel">Nueva Factura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('invoice.alta')}}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label for="codigo" class="form-label">Codigo</label>
                                <input type="text" class="form-control" id="codigo" name="codigo" placeholder="Codigo">
                            </div>
                            <!-- Datos de la OC -->
                            <div class="mb-3">
                                <label for="client" class="form-label">Cliente</label>

                                <select class="form-control" id="client_id" name="client_id" required>
                                    @foreach ($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" required>{{ $cliente->name }}</option>
                                    @endforeach
                                </select>

                            </div>

                            <div class="mb-3">
                                <label for="razon_social" class="form-label"><strong>Razon Social:</strong></label>
                                <select class="form-control" name="razon_social" id="razon_social" required>
                                    <option value="MAQUINADOS BADILSA S.A DE C.V">MAQUINADOS BADILSA S.A DE C.V</option>
                                    <option value="RICARDO JAVIER BADILLO AMAYA">RICARDO JAVIER BADILLO AMAYA</option>
                                </select>
                            </div>



                            <div class="modal-footer">
                                <a href="" class="btn btn-secondary mb-3" style="margin-right: 15px;">Regresar</a>
                                <button type="submit" class="btn btn-success mb-3">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ocPartidas" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemsModalLabel">Detalles de la Factura</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>oc</th>
                                    <th>Empresa</th>
                                    <th>Vendedor</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Factura</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody id="partidaTableBody">
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var partidaOcModal = document.getElementById('partidaOc');
                partidaOcModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget; // Botón que activó el modal
                    var partidaId = button.getAttribute('data-partida-id'); // Obtén el ID del material desde el atributo data-material-id
                    var partidaIdInput = partidaOcModal.querySelector('#partidaIdInput'); // Campo oculto en el formulario
                    partidaIdInput.value = partidaId; // Asigna el valor al input oculto
                });
            });
        </script>

        <script>
            function loadPartidas(facturaId) {
                fetch(`/factura/invoice_number/${facturaId}/partidas`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(partidas => { // Usar 'partidas' aquí
                        const tbody = document.getElementById('partidaTableBody');
                        tbody.innerHTML = ''; // Limpiar datos anteriores

                        partidas.forEach(partida => {
                            const row = `
                                <tr>
                                    <td>${partida.budget?.oc_number || '-'}</td>  <!-- Esto sí está bien -->
                                    <td>${partida.budget?.client?.name || 'N/A'}</td>  <!-- Corregir aquí -->
                                    <td>${partida.budget?.user?.name || 'N/A'}</td>  <!-- Corregir aquí -->
                                    <td>${partida.descripcion || '-'}</td>
                                    <td>${partida.cantidad || '-'}</td>
                                    <td>${partida.invoice?.codigo || '-'}</td>
                                    <td>${partida.estado || '-'}</td>
                                </tr>`;
                            tbody.innerHTML += row;
                        });

                    })
                    .catch(error => console.error('Error al cargar las partidas:', error));
            }
        </script>

        <script>
            function openStatusModal(button) {
                const facturaId = button.getAttribute('data-factura-id');
                const currentStatus = button.getAttribute('data-estatus');

                // Asignar ID y estatus actual al formulario
                document.getElementById('facturaId').value = facturaId;
                document.getElementById('newStatus').value = currentStatus;

                // Modificar dinámicamente la ruta del formulario
                const form = document.getElementById('statusForm');
                form.action = `/invoice/${facturaId}/estatus`;
            }
        </script>

    </x-slot>
</x-app-layout>