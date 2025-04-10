<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title></title>
    <link rel="stylesheet" href="build/assets/app-C38joj50.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <style type="text/css">
        thead:before,
        thead:after {
            display: none;
        }

        tbody:before,
        tbody:after {
            display: none;
        }

        .table th {
            background-color: #4682B4;
            color: white;
            text-align: left;
            padding: 8px;
        }

        .table td {
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table-borderless td,
        .table-borderless th {
            border: none !important;
        }
    </style>

</head>

<body>
    <table class="table table-borderless" style="border: none;">
        <thead>
            <tr>
                <td scope="col" style="font-size:xx-small;"><img src="logo.png" width="200px">
                    <p> <strong>BADILSA</strong> <br>
                        RFC: MBA140904LY0 <br>
                        Carretera Agua Fría , Km.1.5<br>
                        Col. Cerritos de Agua Fría, Apodaca, Nuevo León.<br>
                        CP: 66200 <br>
                    </p>
                </td>
                <td scope="col" style="text-align: right; font-size:xx-small;">
                    <p>Telefono: (81)8314-2767<br>
                        Correo: ventas@mymdelnorte.com / ventas@badilsa.com<br>
                    </p>
                    <br>
                    <br>
                    <br>
                    <p class="h6" style="text-align: right;">OC: {{$oc->codigo}} </p>
                    <p class="h7" style="text-align: right;">Comprador: {{ Auth::user()->name }}</p>
                    <p class="h7" style="text-align: right;">Fecha creada: {{$oc->created_at}}</p>
                </td>
            </tr>
        </thead>
    </table>

    <table class="table  table-sm" style="text-align:center;font-size:xx-small;" width="100%">
        <thead style="background-color: #4682B4; color:white;">
            <tr>
                <th colspan="1" style="text-align:left">#OC</th>
                <th colspan="1" style="text-align:left">Proveedor</th>
                <th colspan="1" style="text-align:left">MONEDA</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="text-align: left;" colspan="1"> {{$oc->codigo}} </td>
                <td style="text-align: left;" colspan="1"> {{$oc->supplier->nombre}}</td>
                <td style="text-align: left;" colspan="1"> {{$oc->moneda}} </td>
            </tr>
        </tbody>
    </table>

    <table class="table " style="text-align:center;font-size:xx-small;" width="100%">
        <thead style="background-color: #4682B4; color:white;">
            <tr>
                <th colspan="1" style="text-align:left">DESCRIPCION</th>
                <th colspan="1" style="text-align:left">CANTIDAD</th>
                <th colspan="1" style="text-align:left">P/U</th>
            </tr>
        </thead>
        <tbody>

            @foreach($oc->materials as $materiales)
            <tr>
                <td style="text-align: left;" colspan="1"> {{$materiales->descripcion}} </td>
                <td style="text-align: left;" colspan="1"> {{$materiales->cantidad}} </td>
                <td style="text-align: left;" colspan="1">${{ number_format($materiales->precio_unitario, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <table class="table table-bordered" style="text-align: center;font-size:x-small;">
        <thead style="background-color: #4682B4; color:white;">
            <tr>
                <th>SUBTOTAL</th>
                <th>IVA</th>
                <th>TOTAL</th>
            </tr>
        </thead>
        <tbody style="font-size:xx-small;">
            <tr>
                <td>${{ number_format($subtotal, 2) }}</td>
                <td>${{ number_format($iva, 2) }}</td>
                <td>${{ number_format($total, 2) }}</td>
            </tr>
        </tbody>
    </table>



    <p style="font-size:x-small; text-align:center;">
        NOTA DE PAGINA
    </p>

</html>