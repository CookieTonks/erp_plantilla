<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SALIDA - REMISION</title>
    <link rel="stylesheet" href="build/assets/app-C38joj50.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" crossorigin="anonymous">
    <style type="text/css">
        thead:before,
        thead:after,
        tbody:before,
        tbody:after {
            display: none;
        }

        .table th {
            background-color: rgb(198, 198, 198);
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

        body {
            position: relative;
        }

        /* ✅ Marca de agua SOLO en el fondo SIN afectar el contenido */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('logo.png') no-repeat center center;
            background-size: 80%;
            opacity: 0.1;
            /* Solo el logo es transparente */
            z-index: -1;
            /* Se queda atrás del contenido */
        }

        .content {
            position: relative;
            z-index: 1;
            /* Asegura que el contenido sea visible normalmente */
        }
    </style>
</head>

<body>
    <div class="content">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>   
                <td style="width: 50%;">
                    <img src="logo.png" width="200px">
                </td>
                <td style="width: 50%; text-align: right; font-size: small;">
                    @if($entrega->razon_social === 'RICARDO JAVIER BADILLO AMAYA')
                    <strong>RICARDO JAVIER BADILLO AMAYA</strong> <br>
                    <strong style="color: #4682B4;">R.F.C. BAAR561015TU5</strong> <br>
                    @else
                    <strong>MAQUINADOS BADILSA S.A. DE C.V.</strong> <br>
                    <strong style="color: #4682B4;">R.F.C. MBA140904LY0</strong> <br>
                    @endif
                    CARRETERA AGUA FRÍA KM 1.5 <br>
                    COL. CERRITOS DE AGUA FRÍA, APODACA, N.L. <br>
                    C.P. 66200
                </td>
            </tr>
        </table>

        <table class="table table-sm" style="text-align:center;font-size:xx-small;" width="100%">
            <thead style="background-color: #4682B4; color:white;">
                <tr>
                    <th>CLIENTE</th>
                    <th>RFC</th>
                    <th>DIRECCION</th>
                    <th>USUARIO</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$entrega->item->budget->client->name}}</td>
                    <td>{{$entrega->item->budget->client->rfc}}</td>
                    <td>{{$entrega->item->budget->client->address}}</td>
                    <td>{{$entrega->item->budget->clientUser->name}}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm" style="text-align:center;font-size:xx-small;" width="100%">
            <thead style="background-color: #4682B4; color:white;">
                <tr>
                    <th>CODIGO</th>
                    <th>TIPO ENTREGA</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SAL-{{$entrega->id}}</td>
                    <td>{{$entrega->tipo_documento}}</td>
                </tr>
            </tbody>
        </table>

        <table class="table table-sm" style="text-align:center;font-size:xx-small;" width="100%">
            <thead style="background-color: #4682B4; color:white;">
                <tr>
                    <th>COTIZACION</th>
                    <th>DESCRIPCION</th>
                    <th>CANTIDAD</th>
                    <th>P/U</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>COT-{{$entrega->item->budget->id}}</td>
                    <td>{{$entrega->item->descripcion}}</td>
                    <td>{{$entrega->cantidad}}</td>
                    <td>{{$entrega->item->precio_unitario}}</td>
                </tr>
            </tbody>
        </table>


        <table class="table table-sm" style="text-align:center;font-size:xx-small;" width="100%">
            <thead style="background-color: #4682B4; color:white;">
                <tr>

                    <th>ENTREGA</th>
                    <th>RECIBE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$entrega->persona_entrega}}</td>
                    <td>{{$entrega->persona_recibe}}</td>
                </tr>
            </tbody>
        </table>

    </div>
</body>

</html>