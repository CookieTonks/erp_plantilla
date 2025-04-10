<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Badilsa') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <!-- Scripts de jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Scripts de Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-100">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-blue dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Mostrar el mensaje de éxito -->
    @if(session('success'))
    <div class="alert alert-success position-absolute top-0 end-0 mt-3 me-3 alert-dismissible fade show" id="successMessage" style="max-width: 25rem;" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Mostrar el mensaje de error -->
    @if(session('error'))
    <div class="alert alert-danger position-absolute top-0 end-0 mt-3 me-3 alert-dismissible fade show" id="errorMessage" style="max-width: 25rem;" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Script para cerrar el mensaje después de unos segundos -->
    <script>
        $(document).ready(function() {
            // Desvanecer el mensaje de éxito después de 5 segundos
            setTimeout(function() {
                $('#successMessage').fadeOut(500); // Fade out en 500ms
            }, 5000);

            // Desvanecer el mensaje de error después de 5 segundos
            setTimeout(function() {
                $('#errorMessage').fadeOut(500); // Fade out en 500ms
            }, 5000);
        });
    </script>

</body>

</html>