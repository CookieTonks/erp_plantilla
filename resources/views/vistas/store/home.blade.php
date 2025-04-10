<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Almacen / Materiales
        </h2>

    </x-slot>

    <div class="container">
        <div class="py-5">

            <div class="row justify-content-center">
                <!-- Módulo 1 -->
                <div class="col-12 col-sm-12">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="" class="text-decoration-none text-dark fw-bold fs-5">
                                Material pendiente:
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
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addMaterial">
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
                            @foreach($materiales as $material)
                            <tr>
                                <td>OT-{{$material->item->budget->id}}_{{$material->item->partida}}</td>
                                <td>{{$material->item->budget->client->name}}</td>
                                <td>{{$material->item->budget->clientUser->name}}</td>
                                <td>{{$material->item->budget->user->name}}</td>
                                <td>{{$material->oc->codigo ?? 'NO OC'}}</td>
                                <td>{{$material->descripcion}}</td>
                                <td>{{$material->cantidad}}</td>
                                <td>{{$material->estatus}}</td>
                                <td>
                                    <a href="{{ route('almacen.material.check', ['materialId' => $material->id]) }}" class="btn btn-success btn-sm">
                                        Entrada
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
    <div class="modal fade" id="addMaterial" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addItemModalLabel">Solicitar Material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('almacen.material.ask')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="descripcion" name="descripcion" placeholder="Descripción">
                        </div>
                        <div class="mb-3">
                            <label for="cantidad" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="cantidad" name="cantidad" placeholder="Cantidad">
                        </div>
                        <div class="mb-3">
                            <label for="unidad" class="form-label">Unidad</label>
                            <input type="text" class="form-control" id="unidad" name="unidad" step="0.01" placeholder="Unidad">
                        </div>
                        <div class="mb-3">
                            <label for="medida" class="form-label">Medida</label>
                            <input type="text" class="form-control" id="medida" name="medida" step="0.01" placeholder="Medida">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary mb-3" data-bs-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-success mb-3">Solicitar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>









</x-app-layout>