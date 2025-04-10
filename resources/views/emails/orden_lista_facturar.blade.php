<x-mail::message>
    # OC lista para facturar

    La siguiente Patida de la Orden de Compra ha sido marcada como la ultima entrega y está lista para facturación.

    <x-mail::table>
        | OC | Partida | Cliente | Vendedor | Fecha de Salida |
        |--------------|-----------------|-------------|----------------|---------------------|
        | {{ $oc }} | {{ $partida }} | {{ $cliente }} | {{ $vendedor }} | {{ $fecha }} |
    </x-mail::table>

    Por favor, procedan con la facturación correspondiente.

    Gracias.
</x-mail::message>