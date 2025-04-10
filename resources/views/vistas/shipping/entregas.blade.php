<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Embarques / Carga de Archivos Enviados
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
                                Ordenes pendientes:
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
                                <th data-field="numero_salida" data-sortable="true"># SALIDA</th>
                                <th data-field="cantidad" data-sortable="true">Cantidad</th>
                                <th data-field="ot" data-sortable="true">OT</th>
                                <th data-field="tipo_salida" data-sortable="true">Tipo Salida</th>
                                <th data-field="entrega" data-sortable="true">Entrega</th>
                                <th data-field="recibe" data-sortable="true">Recibe</th>
                                <th data-field="razon_social" data-sortable="true">Razon Social</th>
                                <th data-field="archivo_firmado" data-sortable="true">Firma</th>
                                <th data-field="documento_firmado" data-sortable="true">Documento Firmado</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($entregas as $entrega)
                            <tr>
                                <td>SAL-{{$entrega->id}}</td>
                                <td>{{$entrega->cantidad}}</td>
                                <td>{{$entrega->item->budget->id}}_{{$entrega->item->id}}</td>
                                <td>{{$entrega->tipo_documento}}</td>
                                <td>{{$entrega->persona_entrega}}</td>
                                <td>{{$entrega->persona_recibe}}</td>
                                <td>{{ $entrega->razon_social }}</td>
                                <td>
                                    @if($entrega->carga_firma)
                                    <span style="color: green; font-weight: bold;">✅</span>
                                    @if($entrega->pdf_path)
                                    <a href="{{ Storage::url($entrega->pdf_path) }}" target="_blank" style="margin-left: 10px; color: blue; text-decoration: underline;">
                                        Ver archivo
                                    </a>
                                    @endif
                                    @else
                                    <span style="color: red; font-weight: bold;">❌</span>
                                    @endif
                                </td>

                                <td>
                                    <!-- Botón para abrir el modal -->
                                    <button
                                        type="button"
                                        class="btn btn-success mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#liberarOTModal{{$entrega->id}}">
                                        Carga de Documento
                                    </button>

                                    <div class="modal fade" id="liberarOTModal{{$entrega->id}}" tabindex="-1" aria-labelledby="asignarTecnicoLabel{{$entrega->id}}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="asignarTecnicoLabel{{$entrega->id}}">
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ route('shipping.cargaSalida', $entrega->id) }}" method="POST" enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="mb-3">
                                                            <label for="pdf{{$entrega->id}}" class="form-label">Subir Entrega Firmada (PDF)</label>
                                                            <input type="file" class="form-control" id="pdf{{$entrega->id}}" name="pdf" accept=".pdf" required>
                                                        </div>

                                                        <button type="submit" class="btn btn-success mt-3">Subir Documento</button>
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






    <!-- Modales -->

</x-app-layout>