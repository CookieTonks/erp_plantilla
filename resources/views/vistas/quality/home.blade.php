<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Calidad / Ordenes de trabajo
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
                                                    <form action="{{ route('quality.ot.liberacion', $orden->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success mt-3">Liberar</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <a href="{{ route('quality.ot.rechazo', $orden->id) }}"
                                        class="btn btn-danger mb-3"
                                        onclick="return confirm('¿Estás seguro de que deseas rechazar esta OT?')">
                                        Rechazar
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

</x-app-layout>