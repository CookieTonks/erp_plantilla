<x-app-layout>
    <x-slot name="header">
        <div class="container">
            <div class="py-5">
                <div class="mb-3">
                    <label for="client" class="form-label">Cliente</label>
                    <input type="text" class="form-control" value="{{$budget->client->name}}" name="client" placeholder="Cliente" readonly>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="client" class="form-label">Usuario</label>
                    <input type="text" class="form-control" value="{{ $budget->clientUser->name }}" name="client" placeholder="Usuario" readonly>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Moneda</label>
                    <input type="text" class="form-control" value="{{$budget->moneda}}" name="tipo" placeholder="Moneda" readonly>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tiempo de Entrega</label>
                    <input type="text" class="form-control" value="{{$budget->delivery_time}}" name="tipo" placeholder="Tiempo de Entrega" readonly>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Número de Orden de Compra (OC)</label>
                    <input type="text" class="form-control" value="{{$budget->oc_number}}" name="tipo" placeholder="Número de Orden de Compra (OC)" readonly>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <td>Cotizacion</td>
                                <th>Partida</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>P/U</th>
                                <th>PDF</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($items as $item)
                            <tr>
                                <td>
                                    {{$budget->codigo}}
                                </td>
                                <td>
                                    {{$item->partida}}
                                </td>
                                <td>
                                    {{$item->descripcion}}
                                </td>
                                <td>
                                    {{$item->cantidad}}
                                </td>
                                <td>
                                    {{$item->precio_unitario}}
                                </td>
                                <td>
                                    <a href="/storage/{{ $item->imagen }}" target="_blank">Ver PDF</a>
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('item.destroy', ['itemId' => $item->id]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm delete-row">Eliminar</button>
                                    </form>

                                    <a href="#"
                                        class="btn btn-info btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalModifyItem"
                                        data-id="{{ $item->id }}"
                                        data-descripcion="{{ $item->descripcion }}"
                                        data-cantidad="{{ $item->cantidad }}"
                                        data-pdf="{{ $item->imagen }}"
                                        data-precio_unitario="{{ $item->precio_unitario }}">
                                        Editar
                                    </a>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <button type="button" id="add-row" class="btn btn-primary mb-3 text-end" data-bs-toggle="modal" data-bs-target="#addItemModal">
                    + Partida
                </button>
            </div>

            <div class="py-12 text-end">
                <a href="{{ route('budgets.index') }}" class="btn btn-secondary btn-sm">Regresar</a>
                <a href="{{ route('budgets.make', ['budgetId' => $budget->id]) }}" target="_blank" class="btn btn-success btn-sm">Crear Cotización</a>
                <a href="{{ route('budgets.edit', ['budgetId' => $budget->id]) }}" target="_blank" class="btn btn-info btn-sm">Editar Cotización</a>
                <a href="#" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAssignOC">Aprobada</a>
                <a href="{{ route('budgets.rejected', ['budgetId' => $budget->id]) }}"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta cotización?')">
                    Rechazada
                </a>
                <a href="{{ route('budgets.destroy', ['budgetId' => $budget->id]) }}"
                    class="btn btn-danger btn-sm"
                    onclick="return confirm('¿Estás seguro de que deseas eliminar esta cotización?')">
                    Eliminar Cotización
                </a>
            </div>
        </div>


        <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addItemModalLabel">Agregar Partida</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('item.store', ['budgetId' => $budget->id] ) }}" method="POST" enctype="multipart/form-data">
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
                                <label for="precio_unitario" class="form-label">Precio Unitario</label>
                                <input type="number" class="form-control" id="precio_unitario" name="precio_unitario" step="0.01" placeholder="Precio Unitario">
                            </div>
                            <div class="mb-3">
                                <label for="pdf" class="form-label">Archivo PDF</label>
                                <input type="file" class="form-control" id="pdf" name="pdf" accept="pdf/*">
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

        <div class="modal fade" id="modalAssignOC" tabindex="-1" aria-labelledby="modalAssignOCTitle" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalAssignOCTitle">Asignar Orden de Compra y Cotización</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('budgets.assignOC', ['budgetId' => $budget->id]) }}">
                        @csrf
                        <div class="modal-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="assignOCCheck" name="assignOC" value="1" checked>
                                <label class="form-check-label" for="assignOCCheck">
                                    Ingresar OC del cliente
                                </label>
                            </div>
                            <div class="mb-3" id="ocInputContainer">
                                <label for="ocNumber" class="form-label">Número de Orden de Compra (OC)</label>
                                <input type="text" class="form-control" id="ocNumber" name="ocNumber">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-success">Enviar a Ordenes de Trabajo</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalModifyItem" tabindex="-1" aria-labelledby="modifyItemModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modifyItemModalLabel">Editar Partida</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editItemForm" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editItemId" name="id"> <!-- Campo oculto para el ID -->

                            <!-- Otros campos -->
                            <div class="mb-3">
                                <label for="editDescripcion" class="form-label">Descripción</label>
                                <input type="text" class="form-control" id="editDescripcion" name="descripcion">
                            </div>
                            <div class="mb-3">
                                <label for="editCantidad" class="form-label">Cantidad</label>
                                <input type="number" class="form-control" id="editCantidad" name="cantidad">
                            </div>
                            <div class="mb-3">
                                <label for="editPrecioUnitario" class="form-label">Precio Unitario</label>
                                <input type="number" class="form-control" id="editPrecioUnitario" name="precio_unitario" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label for="editPdf" class="form-label">Archivo PDF</label>
                                <div id="pdfPreview" class="mb-2">
                                    <a href="#" id="existingPdfLink" target="_blank" style="display: none;">Ver archivo actual</a>
                                </div>
                                <input type="file" class="form-control" id="editPdf" name="pdf" accept="application/pdf">
                                <small class="form-text text-muted">Sube un archivo para reemplazar el actual.</small>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>



        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const assignOCCheck = document.getElementById('assignOCCheck');
                const ocInputContainer = document.getElementById('ocInputContainer');

                assignOCCheck.addEventListener('change', function() {
                    if (this.checked) {
                        ocInputContainer.style.display = 'block';
                        document.getElementById('ocNumber').required = true;
                    } else {
                        ocInputContainer.style.display = 'none';
                        document.getElementById('ocNumber').required = false;
                    }
                });
            });
        </script>


        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('modalModifyItem');
                modal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget; // Botón que activó el modal
                    const id = button.getAttribute('data-id'); // Obtener el ID del ítem

                    // Actualizar la acción del formulario
                    const form = modal.querySelector('#editItemForm');
                    form.action = `/item/update/${id}`; // Construir la URL con el ID dinámico

                    // Actualizar los campos del formulario
                    modal.querySelector('#editItemId').value = id;
                    modal.querySelector('#editDescripcion').value = button.getAttribute('data-descripcion');
                    modal.querySelector('#editCantidad').value = button.getAttribute('data-cantidad');
                    modal.querySelector('#editPrecioUnitario').value = button.getAttribute('data-precio_unitario');

                    // Manejar el PDF actual (enlace o nombre)
                    const pdfLink = modal.querySelector('#existingPdfLink');
                    const pdfPath = button.getAttribute('data-pdf');
                    if (pdfPath) {
                        pdfLink.style.display = 'block';
                        pdfLink.href = `/storage/${pdfPath}`; // Asegúrate de que esta ruta sea correcta
                        pdfLink.textContent = 'Ver archivo actual';
                    } else {
                        pdfLink.style.display = 'none';
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>