<x-mail::message>
# Orden de compras proximas a vencer

Las siguientes Ordenes de Compras tienen fecha de entrega los proximos **7 dias**:

<x-mail::table>
| OC  | Cliente | Usuario | Vendedor | Fecha de Creación |
|-----|---------|---------| ---------|-------------------|
@foreach ($budgets as $budget)
| {{ $budget->oc_number }} | {{ $budget->client?->name }} | {{ $budget->user?->name }} | {{ $budget->clientUser?->name }} |{{ $budget->created_at->format('d/m/Y') }} |
@endforeach
</x-mail::table>

Por favor, revisarlos lo antes posible.

Gracias.
</x-mail::message>
