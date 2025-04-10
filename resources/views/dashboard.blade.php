<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    </x-slot>
    <div class="container">

        <div class="py-12">
            <div class="row">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-black-100 dark:text-black-100">
                        {{ __("¡Bienvenido, ") }} {{ Auth::user()->name }}{{ __("!") }}
                    </div>

                </div>
            </div>
        </div>

        <div class="py-1">
            <div class="row">
                <!-- Módulo 1 -->
                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('budgets.index') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-file-earmark-text me-2"></i> Cotizaciones
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Módulo 2 -->
                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('orders.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-clipboard-check me-2"></i> Orden Trabajo
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('compras.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-cart me-2"></i> Compras
                            </a>
                        </div>
                    </div>
                </div>


                <!-- Módulo 3 -->
                <!-- <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('roles.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                Permisos</a>
                        </div>
                    </div>
                </div> -->

            </div>

            <div class="row" style="padding-top: 20px;">
                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('almacen.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-box-seam me-2"></i> Almacén
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('production.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-gear-wide-connected me-2"></i> Producción
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('quality.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-check-circle me-2"></i> Calidad
                            </a>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row" style="padding-top: 20px;">
                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('shipping.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-truck me-2"></i> Embarques
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('invoice.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-cash-stack me-2"></i> Facturación
                            </a>
                        </div>
                    </div>
                </div>


                <div class="col-12 col-sm-4">
                    <div class="card shadow rounded h-100 d-flex align-items-center justify-content-center">
                        <div class="card-body text-center">
                            <a href="{{ route('administration.home') }}" class="text-decoration-none text-dark fw-bold fs-5">
                                <i class="bi bi-gear me-2"></i> Administración
                            </a>
                        </div>
                    </div>
                </div>

            </div>


        </div>

    </div>


</x-app-layout>