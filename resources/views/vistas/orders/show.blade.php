<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Orden Trabajo/ Partidas
        </h2>
        <div class="container">
            <div class="py-5">
                <div class="mb-3">
                    <label for="client" class="form-label">Cliente</label>
                    <input type="text" class="form-control" value="{{$budget->client->name}}" name="client" placeholder="Cliente" readonly>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="client" class="form-label">Usuario</label>
                    <input type="text" class="form-control" value="{{ $budget->clientUser->name }}" name="client" placeholder="Cliente" readonly>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Moneda</label>
                    <input type="text" class="form-control" value="{{$budget->moneda}}" name="tipo" placeholder="Tipo" readonly>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Tiempo de Entrega</label>
                    <input type="text" class="form-control" value="{{$budget->delivery_time}}" name="tipo" placeholder="Tipo" readonly>
                </div>
                <div class="mb-3">
                    <label for="tipo" class="form-label">Número de Orden de Compra (OC)</label>
                    <input type="text" class="form-control" value="{{$budget->oc_number}}" name="tipo" placeholder="Tipo" readonly>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="items-table">
                        <thead>
                            <tr>
                                <th>Cotizacion</th>
                                <th>Partida</th>
                                <th>Descripción</th>
                                <th>Cantidad</th>
                                <th>P/U</th>
                                <th>PDF</th>
                                <th>Material</th>
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
                                <td>
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#itemsModal"
                                        onclick="loadItems({{ $item->id }})">
                                        Ver Material
                                    </button>
                                </td>
                                <td>
                                    <a target="_blank" href="{{ route('budgets.pdf.order', ['budgetId' => $budget->id, 'ItemId' => $item->id]) }}"
                                        class="btn btn-success btn-sm"
                                        style="width: 100px; text-align: center;">
                                        OT
                                    </a>

                                    <a href="{{ route('budgets.order.materials.show', ['ItemId' => $item->id]) }}"
                                        class="btn btn-primary btn-sm"
                                        style="width: 100px; text-align: center;">
                                        + Material
                                    </a>
                                </td>

                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="py-12 text-end">
                <a href="{{ route('orders.home') }}" class="btn btn-secondary btn-sm">Regresar</a>
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
    </x-slot>
</x-app-layout>