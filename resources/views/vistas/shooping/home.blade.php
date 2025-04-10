<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Compras / Home
        </h2>
        <div class="container">
            <div class="py-5">

                <div class="row justify-content-center">
                    <!-- Módulo 1 -->
                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                    Material sin asignar: {{$totales['sin_asignar']}}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                            <div class="card-body text-center">
                                <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                    Material en proceso: {{$totales['en_proceso']}}
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
                                        <th data-field="ot" data-sortable="true">OT</th>
                                        <th data-field="orderNumber" data-sortable="true">Empresa</th>
                                        <th data-field="sales" data-sortable="true">Vendedor</th>
                                        <th data-field="oc" data-sortable="true">OC</th>
                                        <th data-field="cantidad" data-sortable="true">Descripcion</th>
                                        <th data-field="descripcion" data-sortable="true">Cantidad</th>
                                        <th data-field="status" data-sortable="true">Estado</th>
                                        <th data-field="acciones" data-sortable="true">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($materiales as $material)
                                    <tr>
                                        <td>OT-{{$material->item->budget->id}}_{{$material->item->id}}</td>
                                        <td>{{$material->item->budget->clientUser->name}}</td>
                                        <td>{{$material->item->budget->user->name}}</td>
                                        <td>{{$material->oc->codigo ?? 'NO OC'}}</td>
                                        <td>{{$material->descripcion}}</td>
                                        <td>{{$material->cantidad}}</td>
                                        <td>{{$material->estatus}}</td>
                                        <td>
                                            <button
                                                type="button"
                                                class="btn btn-success"
                                                data-bs-toggle="modal"
                                                data-bs-target="#materialOc-{{ $material->id }}"
                                                data-material-id="{{ $material->id }}">
                                                OC
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal ÚNICO para cada material -->
                                    <div class="modal fade" id="materialOc-{{ $material->id }}" tabindex="-1" aria-labelledby="materialOcLabel-{{ $material->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="materialOcLabel-{{ $material->id }}">Asignar OC</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('compras.material.oc', ['materialId' => $material->id]) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('POST')

                                                        <!-- Campo oculto para el ID del material -->
                                                        <input type="hidden" name="materialId" value="{{ $material->id }}">

                                                        <!-- Selector de OC -->
                                                        <div class="mb-3">
                                                            <label for="oc_id-{{ $material->id }}" class="form-label">OC</label>
                                                            <select class="form-control" id="oc_id-{{ $material->id }}" name="oc_id" required>
                                                                @foreach ($ocs as $oc)
                                                                <option value="{{ $oc->id }}">{{ $oc->codigo }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>

                                                        <!-- Precio Unitario -->
                                                        <div class="mb-3">
                                                            <label for="precio_unitario-{{ $material->id }}" class="form-label">P/U</label>
                                                            <input type="number" step="0.01" class="form-control" id="precio_unitario-{{ $material->id }}" name="precio_unitario" placeholder="Precio Unitario" required>
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
                                    @endforeach
                            </table>
                        </div>
                    </div>

                    <!-- Nueva columna para mostrar las OC -->
                    <div class="col-md-4">
                        <div class="card">
                            <!-- Encabezado de la tarjeta -->
                            <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                <span>Órdenes de Compra (OC)</span>
                                <!-- Botón alineado dentro del header -->
                                <button type="button" data-bs-toggle="modal" data-bs-target="#itemsModal" class="btn btn-primary mb-3"> + OC</button>

                            </div>

                            <!-- Cuerpo de la tarjeta -->
                            <div class="card-body">
                                <ul class="list-group">
                                    @foreach($ocs as $oc)
                                    <li class="list-group-item">
                                        <strong>Código:</strong> {{ $oc->codigo }}<br>
                                        <strong>Proveedor:</strong> {{ $oc->supplier->nombre }}<br>
                                        <strong>Estado:</strong> {{ $oc->estatus }}
                                        <br>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#ocMaterials"
                                            onclick="loadMaterials({{ $oc->id }})">
                                            Ver Material
                                        </button>
                                        <a href="{{ route('compras.oc.pdf', ['ocId' => $oc->id]) }}" class="btn btn-success btn-sm">
                                            OC PDF
                                        </a>

                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>


        <div class="modal fade" id="itemsModal" tabindex="-1" aria-labelledby="modifyItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyItemModalLabel">Nueva OC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('compras.oc.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <!-- Datos de la OC -->
                            <div class="mb-3">
                                <label for="client" class="form-label">Proveedor</label>
                                <select class="form-control" id="supplier_id" name="supplier_id" required>
                                    @foreach ($proveedores as $proveedor)
                                    <option value="{{ $proveedor->id }}" required>{{ $proveedor->nombre }}</option>
                                    @endforeach
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

                            <div class="modal-footer">
                                <a href="" class="btn btn-secondary mb-3" style="margin-right: 15px;">Regresar</a>
                                <button type="submit" class="btn btn-success mb-3">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="ocMaterials" tabindex="-1" aria-labelledby="itemsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="itemsModalLabel">Detalles de la OC</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>OT</th>
                                    <th>Empresa</th>
                                    <th>Vendedor</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <th>Estatus</th>
                                </tr>
                            </thead>
                            <tbody id="materialTableBody">
                                <!-- Se llenará dinámicamente -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var materialOcModal = document.getElementById('materialOc');
                materialOcModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget; // Botón que activó el modal
                    var materialId = button.getAttribute('data-material-id'); // Obtén el ID del material desde el atributo data-material-id
                    var materialIdInput = materialOcModal.querySelector('#materialIdInput'); // Campo oculto en el formulario
                    materialIdInput.value = materialId; // Asigna el valor al input oculto
                });
            });
        </script>

        <script>
            function loadMaterials(ocId) {
                fetch(`/compras/oc/${ocId}/materials`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(materials => {
                        const tbody = document.getElementById('materialTableBody');
                        tbody.innerHTML = ''; // Limpiar datos anteriores

                        materials.forEach(material => {
                            const row = `
                        <tr>
                            <td>OT-${material.item?.budget?.id}_${material.item_id}</td>
                            <td>${material.item?.budget?.client?.name || 'N/A'}</td>
                            <td>${material.item?.budget?.user?.name || 'N/A'}</td>
                            <td>${material.descripcion || 'N/A'}</td>
                            <td>${material.cantidad || 'N/A'}</td>
                            <td>${material.oc?.codigo || 'NO OC'}</td>
                            <td>${material.estatus || 'N/A'}</td>
                        </tr>
                    `;
                            tbody.innerHTML += row;
                        });
                    })
                    .catch(error => console.error('Error al cargar los materiales:', error));
            }
        </script>






    </x-slot>
</x-app-layout>