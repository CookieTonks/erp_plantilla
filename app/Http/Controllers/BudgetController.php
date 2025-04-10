<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Client;
use App\Models\ClientUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Helpers\StringHelper;
use App\Models\Item;
use setasign\Fpdi\Fpdi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Proceso;



class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $estado = $request->query('estado');
        $userId = auth()->id();

        // Obtener cotizaciones según el estado
        $budgets = Budget::with(['client', 'user', 'clientUser'])
            ->where('user_id', $userId)
            ->when($estado, function ($query) use ($estado) {
                $query->where('estado', strtoupper($estado));
            })
            ->get();


        // Calcular el total por estado
        $totales = [
            'abiertas' => Budget::where('user_id', $userId)->where('estado', 'ABIERTA')->count(), //Cuando se crea cotizacion
            'enviadas' => Budget::where('user_id', $userId)->where('estado', 'ENVIADA')->count(), //Al mandar a ordenes de trabajo
            'pendientes' => Budget::where('user_id', $userId)->where('estado', 'PENDIENTE')->count(), //Aqui va a aplicar de que cuando se abra y no cambie a proceso
            'en_proceso' => Budget::where('user_id', $userId)->where('estado', 'PROCESO')->count(), //Cuando se cierre en facturacion
            'entregadas' => Budget::where('user_id', $userId)->where('estado', 'ENTREGADA')->count(), //Cuando cancele una cotizacion
        ];



        $clients = Client::all();


        return view('vistas.budget.home', compact('budgets', 'estado', 'totales', 'clients'));
    }


    public function getClientUsers($clientId)
    {
        // Obtener los ClientUser relacionados con el Cliente seleccionado
        $client = Client::find($clientId);

        // Verificar si el cliente existe
        if (!$client) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }

        // Obtener los ClientUsers relacionados
        $clientUsers = $client->clientUsers; // Si tienes la relación correctamente definida como 'clientUsers'

        // Devolver los datos en formato JSON
        return response()->json($clientUsers);
    }


    public function store(Request $request)
    {

        try {
            $this->createBudget($request);

            return redirect()->route('budgets.index')->with('success', 'Cotizacion creada con éxito.');
        } catch (\Throwable $th) {
            Log::error('Error al crear el cotizacion: ' . $th->getMessage());
            return redirect()->route('budgets.index')->with('error', 'Hubo un problema al crear la cotizacion.');
        }
    }


    public function createBudget(Request $request)
    {

        // Convertir datos generales a mayúsculas
        $data = [
            'client_id' => $request->client,
            'moneda' => $request->moneda,
            'client_user_id' => $request->clientUser,
            'delivery_time' => $request->delivery_time,

        ];

        // Especificamos los campos que queremos convertir
        $fieldsToUpper = ['estado', 'moneda'];

        $data = StringHelper::convertToUpperCase($data, $fieldsToUpper);

        // Crear el presupuesto
        $budget = Budget::create([
            'client_id' => $data['client_id'],
            'user_id' => auth()->id(),
            'estado' => 'ABIERTA',  // Estado convertido a mayúsculas
            'codigo' => 'COT-' . (Budget::max('id') + 1),
            'moneda' => $data['moneda'],
            'delivery_time' => $data['delivery_time'],
            'client_user_id' => $data['client_user_id'],
            'monto' => 0
        ]);

        $total = 0;

        // Crear partidas y convertir descripciones de items a mayúsculas
        $items = StringHelper::convertItemsToUpperCase($request->items);
        foreach ($request->items as $index => $item) {
            $item_track = $budget->items()->count() + 1;

            $path = null;

            if (isset($item['pdf']) && $item['pdf']->isValid()) {
                // 1. Guarda el archivo original
                $originalPath = $item['pdf']->store('partidas-imagenes', 'public');
                $originalPdfPath = storage_path('app/public/' . $originalPath);

                // 2. Combina con FPDI
                $fpdi = new FPDI();
                $pageCount = $fpdi->setSourceFile($originalPdfPath);

                // Ruta del archivo final
                $processedPath = 'partidas-imagenes/processed-' . basename($originalPath);
                $processedPdfPath = storage_path('app/public/' . $processedPath);

                // Importa las páginas del PDF original y ajusta tamaño
                for ($i = 1; $i <= $pageCount; $i++) {
                    $templateId = $fpdi->importPage($i);



                    // Obtiene el tamaño y orientación de la página original
                    $size = $fpdi->getTemplateSize($templateId);
                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                    // Crea una nueva página con las dimensiones originales
                    $fpdi->AddPage($orientation, [$size['width'], $size['height']]);

                    // Usa la plantilla de la página original
                    $fpdi->useTemplate($templateId);

                    // Agregar el código
                    $fpdi->SetY(10);
                    $fpdi->SetX($size['width'] - 60);
                    $fpdi->SetFillColor(200, 200, 200);
                    $fpdi->SetFont('Arial', '', 14);

                    // Agregar el logo a la página
                    $fpdi->Image(public_path('logo.png'), 10, 10, 20);


                    // Información del cliente
                    $clientName = $budget->client?->name ?? 'Sin cliente'; // Si no hay nombre, usamos un valor por defecto
                    $clientInfo =  $clientName; // Concatenamos el ID y el nombre del cliente

                    // Agregar código del presupuesto y cliente
                    $fpdi->Cell(50, 10, $budget->codigo ?? 'Sin código' . '_' . $item_track, 0, 0, 'C', true); // Código
                    $fpdi->SetX($size['width'] - 130); // Ajustamos la posición para la siguiente celda
                    $fpdi->Cell(70, 10, $clientInfo, 0, 0, 'C', true); // Información del cliente

                    // Agregar el nombre del usuario
                    $fpdi->SetX($size['width'] - 210); // Ajustamos la posición para la siguiente celda
                    $fpdi->Cell(80, 10, $budget->user?->name ?? 'Sin usuario', 0, 0, 'C', true);

                    // Agregar pie de página
                    $fpdi->SetY(-15); // Pie de página a 15mm del borde inferior
                    $fpdi->SetFont('Arial', 'I', 8);
                    // $fpdi->Cell(0, 10, 'Página ' . $fpdi->PageNo(), 0, 0, 'C');
                }

                // 3. Guardar el PDF procesado
                $fpdi->Output($processedPdfPath, 'F');

                // El path del archivo final procesado
                $path = $processedPath;
            }



            $budget->items()->create([
                'descripcion' => $item['descripcion'],
                'cantidad' => $item['cantidad'],
                'precio_unitario' => $item['precio_unitario'],
                'subtotal' => $item['cantidad'] * $item['precio_unitario'],
                'imagen' => $path,
                'partida' => $item_track,
            ]);
        }

        // Actualizar el monto total
        $budget->update([
            'monto' => $total,
        ]);

        return $budget;
    }

    public function assignOC(Request $request, $budgetId)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'assignOC' => 'nullable|boolean',
                'ocNumber' => 'nullable|string|max:255',
            ]);

            // Buscar el presupuesto
            $budget = Budget::findOrFail($budgetId);


            $budget->items()->update(['estado' => 'PROCESO']);

            // Calcular la fecha de entrega
            $delivery_date = Carbon::now()->addDays($budget->delivery_time);

            // Verificar si el cliente asignó una OC
            if ($request->has('assignOC') && $request->assignOC) {
                $budget->oc_number = $request->ocNumber;
                $budget->estado = 'PROCESO';
                $budget->delivery_date = $delivery_date;
            } else {
                // Generar una OC interna si no fue asignada
                $budget->oc_number = 'BAL-' . $budgetId;
                $budget->estado = 'PROCESO';
                $budget->delivery_date = $delivery_date;
            }

            // Crear o encontrar un proceso relacionado con el presupuesto
            Proceso::firstOrCreate([
                'budget_id' => $budget->id,
                'cotizaciones' => 1,
            ]);

            // Guardar los cambios en el presupuesto
            $budget->save();

            // Redirigir con mensaje de éxito
            return redirect()->route('budgets.show', ['budgetId' => $budgetId])
                ->with('success', 'Orden de Compra asignada correctamente.');
        } catch (\Exception $e) {
            // Manejar la excepción
            // Registrar el error en el log
            \Log::error('Error al asignar la Orden de Compra: ' . $e->getMessage());

            // Devolver un mensaje de error al usuario
            return redirect()->route('budgets.show', ['budgetId' => $budgetId])
                ->with('error', 'Ocurrió un error al asignar la Orden de Compra. Intenta nuevamente.');
        }
    }



    public function getUsersByClient($clientId)
    {
        $client = Client::find($clientId);
        return response()->json(['client_user' => $client->user]);
    }



    public function getItems($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);
        return response()->json($budget->items);
    }

    public function show($budgetId)
    {

        $budget = Budget::findorfail($budgetId);
        $items = $budget->items;
        return view('vistas.budget.show', compact('budget', 'items'));
    }


    public function make($budgetId)
    {

        $budget = Budget::findOrFail($budgetId);
        $subtotal = $budget->items->sum('subtotal');
        $iva = $subtotal * 0.16;
        $total = $subtotal + $iva;
        $budget->monto = $total;
        $budget->save();

        $items = $budget->items;


        $html = view('vistas.budget.cot', compact('budget', 'items', 'subtotal', 'iva', 'total'))->render();

        $pdf = \PDF::loadHTML($html)->setPaper('a4', 'portrait');

        // Descargar el PDF
        return $pdf->stream("budget_{$budget->id}.pdf");
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Budget $budgetId)
    {
        $budget = $budgetId;
        $clients = Client::all();
        return view('vistas.budget.edit', compact('budget', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $budgetId)
    {

        $budget = Budget::findorfail($budgetId);

        try {
            $budget->update($request->only(['client', 'client_user_id', 'moneda', 'delivery_time', 'oc_number']));
            return back()->with('success', '¡Cotizacion modificada con éxito!');
        } catch (\Exception $e) {
            return back()->with('error', '¡Cotizacion no modificada, intenta de nuevo!');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroyBudget(Budget $budget)
    {
        //
    }

    public function rejectedBudget($budgetId)
    {
        $budget = Budget::findOrFail($budgetId);

        try {
            $budget->estado = 'RECHAZADA';
            $budget->save();
            return redirect()->route('budgets.index')->with('success', '¡Cotizacion rechazada con éxito!');
        } catch (\Exception $e) {
            return redirect()->route('budgets.index')->with('error', '¡Cotizacion no rechazada, intenta de nuevo!');
        }
    }


    public function storeItem(Request $request, $budgetId)
    {

        try {
            $budget = Budget::findOrFail($budgetId);

            $item_track = $budget->items()->count() + 1;


            $path = null;

            if (isset($request->pdf) && $request->pdf->isValid()) {
                // 1. Guarda el archivo original
                $originalPath = $request->pdf->store('partidas-imagenes', 'public');
                $originalPdfPath = storage_path('app/public/' . $originalPath);

                // 2. Combina con FPDI
                $fpdi = new FPDI();
                $pageCount = $fpdi->setSourceFile($originalPdfPath);

                // Ruta del archivo final
                $processedPath = 'partidas-imagenes/processed-' . basename($originalPath);
                $processedPdfPath = storage_path('app/public/' . $processedPath);

                // Importa las páginas del PDF original y ajusta tamaño
                for ($i = 1; $i <= $pageCount; $i++) {
                    $templateId = $fpdi->importPage($i);
                    $size = $fpdi->getTemplateSize($templateId);
                    $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                    $fpdi->AddPage($orientation, [$size['width'], $size['height']]);
                    $fpdi->useTemplate($templateId);

                    // Agregar el logo
                    $fpdi->Image(public_path('logo.png'), 10, 10, 20);

                    // Agregar el código
                    $fpdi->SetY(10);
                    $fpdi->SetX($size['width'] - 60);
                    $fpdi->SetFillColor(200, 200, 200);
                    $fpdi->SetFont('Arial', '', 14);

                    // Información del cliente
                    $clientName = $budget->client?->name ?? 'Sin cliente'; // Si no hay nombre, usamos un valor por defecto
                    $clientInfo =  $clientName; // Concatenamos el ID y el nombre del cliente

                    // Agregar código del presupuesto y cliente
                    $fpdi->Cell(50, 10, $budget->codigo ?? 'Sin código' . '_' . $item_track, 0, 0, 'C', true); // Código
                    $fpdi->SetX($size['width'] - 130); // Ajustamos la posición para la siguiente celda
                    $fpdi->Cell(70, 10, $clientInfo, 0, 0, 'C', true); // Información del cliente

                    // Agregar el nombre del usuario
                    $fpdi->SetX($size['width'] - 210); // Ajustamos la posición para la siguiente celda
                    $fpdi->Cell(80, 10, $budget->user?->name ?? 'Sin usuario', 0, 0, 'C', true);
                }

                // 3. Guardar el PDF procesado
                $fpdi->Output($processedPdfPath, 'F');

                // El path del archivo final procesado
                $path = $processedPath;
            }

            $budget->items()->create([
                'descripcion' => $request['descripcion'],
                'partida' => $item_track,
                'cantidad' => $request['cantidad'],
                'precio_unitario' => $request['precio_unitario'],
                'subtotal' => $request['cantidad'] * $request['precio_unitario'],
                'imagen' => $path,
            ]);

            return back()->with('success', '¡Partida agregada con éxito!');
        } catch (\Throwable $th) {
            return back()->with('error', '¡Partida no agregada, intenta de nuevo!');
        }
    }


    public function destroyItem($itemId)
    {
        try {
            $item = Item::findOrFail($itemId);

            if ($item->imagen && file_exists(public_path($item->imagen))) {
                unlink(public_path($item->imagen));
            }
            $item->delete();

            return back()->with('success', '¡Partida y archivo PDF borrados con éxito!');
        } catch (\Throwable $th) {
            return back()->with('error', '¡Partida no eliminada, intenta de nuevo!');
        }
    }

    public function updateItem(Request $request, $itemId)
    {
        $item = Item::findOrFail($itemId); // Busca el ítem por su ID o lanza un error 404 si no existe

        $budget = Budget::findOrFail($item->budget->id);


        $path = $item->imagen; // Mantener el path del archivo anterior

        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            // 1. Guardar el archivo original
            $originalPath = $request->file('pdf')->store('partidas-imagenes', 'public');
            $originalPdfPath = storage_path('app/public/' . $originalPath);

            // 2. Procesar el archivo PDF con FPDI
            $fpdi = new FPDI();
            $pageCount = $fpdi->setSourceFile($originalPdfPath);

            $processedPath = 'partidas-imagenes/processed-' . basename($originalPath);
            $processedPdfPath = storage_path('app/public/' . $processedPath);

            for ($i = 1; $i <= $pageCount; $i++) {
                $templateId = $fpdi->importPage($i);
                $size = $fpdi->getTemplateSize($templateId);
                $orientation = ($size['width'] > $size['height']) ? 'L' : 'P';

                $fpdi->AddPage($orientation, [$size['width'], $size['height']]);
                $fpdi->useTemplate($templateId);

                // Agregar el logo
                $fpdi->Image(public_path('logo.png'), 10, 10, 20);

                // Agregar el código
                $fpdi->SetY(10);
                $fpdi->SetX($size['width'] - 60);
                $fpdi->SetFillColor(200, 200, 200);
                $fpdi->SetFont('Arial', '', 14);

                // Información del cliente
                $clientName = $item->budget->client?->name ?? 'Sin cliente'; // Si no hay nombre, usamos un valor por defecto
                $clientInfo =  $clientName; // Concatenamos el ID y el nombre del cliente

                // Agregar código del presupuesto y cliente
                $fpdi->Cell(50, 10, ($item->budget->codigo ?? 'Sin código') . '_' . $item->partida, 0, 0, 'C', true); // Código
                $fpdi->SetX($size['width'] - 130); // Ajustamos la posición para la siguiente celda
                $fpdi->Cell(70, 10, $clientInfo, 0, 0, 'C', true); // Información del cliente

                // Agregar el nombre del usuario
                $fpdi->SetX($size['width'] - 210); // Ajustamos la posición para la siguiente celda
                $fpdi->Cell(80, 10, $item->budget->user?->name ?? 'Sin usuario', 0, 0, 'C', true);
            }

            // Guardar el PDF procesado
            $fpdi->Output($processedPdfPath, 'F');

            // Actualiza el path
            $path = $processedPath;

            // Eliminar el archivo anterior, si existe
            if ($item->imagen) {
                Storage::disk('public')->delete($item->imagen);
            }
        }

        // 3. Actualizar los datos del ítem
        $item->update([
            'descripcion' => $request->input('descripcion'),
            'cantidad' => $request->input('cantidad'),
            'precio_unitario' => $request->input('precio_unitario'),
            'subtotal' => $request->input('cantidad') * $request->input('precio_unitario'),
            'imagen' => $path,
        ]);

        // 4. Recalcular el monto total del presupuesto
        $total = $item->budget->items()->sum('subtotal') * .16;
        $item->budget->update(['monto' => $total]);

        return redirect()->back()->with('success', 'Ítem actualizado correctamente.');
    }
}
