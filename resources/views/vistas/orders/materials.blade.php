<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Orden Trabajo/ Materiales
        </h2>
        <div class="container">
            <div class="py-5">

                <div class="table-responsive">
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <th>Cotizacion</th>
                                <th>Partida</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Unidad</th>
                                <th>Medida</th>
                                <th>Estatus</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($materials as $material)
                            <tr>
                                <td>
                                    {{$item->budget->codigo}}
                                </td>
                                <td>
                                    {{$item->partida}}
                                </td>
                                <td>
                                    {{$material->descripcion}}
                                </td>
                                <td>
                                    {{$material->cantidad}}
                                </td>
                                <td>
                                    {{$material->unidad}}
                                </td>
                                <td>
                                    {{$material->medida}}
                                </td>

                                <td>
                                    {{$material->estatus}}
                                </td>

                                <td>

                                    <a href="{{ route('budgets.order.materials.delete', ['materialId' => $material->id]) }}"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('¿Estás seguro de que deseas eliminar este material?')">
                                        Eliminar material
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="py-12 text-end">
                <a href="{{ route('budgets.show.orders', ['budgetId' => $item->budget->id] ) }}" class="btn btn-secondary btn-sm">Regresar</a>
                <a href="{{ route('budgets.order.materials.send', ['ItemId' => $item->id] ) }}" class="btn btn-success btn-sm">Solicitar</a>
                <a href="#" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addMaterial">+ Material</a>
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

        <div class="modal fade" id="addMaterial" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Agregar Material</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('budgets.order.materials.add', ['ItemId' => $item->id] ) }}" method="POST" enctype="multipart/form-data">
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
                                <button type="submit" class="btn btn-success mb-3">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </x-slot>
</x-app-layout>