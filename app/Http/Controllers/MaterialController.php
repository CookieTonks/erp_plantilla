<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\Budget;
use App\Helpers\StringHelper;
use Illuminate\Support\Facades\Log;




class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }


    public function getMaterials($budgetId)
    {
        // Obtener el presupuesto
        $budget = Budget::findOrFail($budgetId);

        // Obtener los materiales relacionados con ese presupuesto
        $materials = $budget->materiales;

        // Calcular el resumen de estatus
        $totalMaterials = $materials->count();
        $receivedMaterials = $materials->where('estatus', 'recibido')->count();

        // Retornar los materiales en formato JSON con el resumen
        return response()->json([
            'materials' => $materials,
            'summary' => [
                'received' => $receivedMaterials,
                'total' => $totalMaterials,
            ],
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $budgetId)
    {

        try {
            // Validación de los datos del formulario
            $request->validate([
                'descripcion' => 'required|string|max:255',
                'cantidad' => 'required|numeric|min:1',
            ]);

            // Convertir datos generales a mayúsculas
            $data = [
                'descripcion' => $request->descripcion,
                'cantidad' => $request->cantidad,
                'estatus' => 'PENDIENTE',
            ];


            $budget = Budget::findOrFail($budgetId);

            $fieldsToUpper = ['descripcion'];

            $data = StringHelper::convertToUpperCase($data, $fieldsToUpper);

            // Crear el nuevo material
            $material = new Material();
            $material->budget_id = $budget->id;
            $material->descripcion = $data['descripcion'];
            $material->cantidad = $data['cantidad'];
            $material->estatus = $data['estatus'];
            $material->save(); // Guardar el material en la base de datos

            // Retornar una respuesta
            return redirect()->route('budgets.show.materials', ['budgetId' => $budgetId])
                ->with('success', 'Material agregado correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error agregar el material: ' . $th->getMessage());
            return redirect()->route('budgets.show.materials', ['budgetId' => $budgetId])
                ->with('error', 'Ocurrió un error al agregar material a la Cotizacion. Intenta nuevamente.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($budgetId)
    {
        $budget = Budget::findorfail($budgetId);
        $materials = $budget->materiales;
        return view('vistas.materials.show', compact('budget', 'materials'));
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $materialId)
    {


        try {
            // Validación de los datos del formulario
            $validatedData = $request->validate([
                'descripcion' => 'required|string|max:255',
                'cantidad' => 'required|integer|min:1',
            ]);

            $data = [
                'descripcion' => $request->descripcion,
                'cantidad' => $request->cantidad,
                'estatus' => 'PENDIENTE',
            ];

            $fieldsToUpper = ['descripcion'];

            $data = StringHelper::convertToUpperCase($data, $fieldsToUpper);

            $material = Material::findOrFail($materialId);
            $material->descripcion = $data['descripcion'];
            $material->cantidad = $data['cantidad'];
            $material->estatus = $data['estatus'];
            $material->save();

            return redirect()->back()->with('success', 'Material actualizado correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error al actualizar el material: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al actualizar el material. Intenta nuevamente.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($materialId)
    {

        try {
            $material = Material::findOrFail($materialId);

            $material->delete();
            return redirect()->back()->with('success', 'Material eliminado correctamente.');
        } catch (\Throwable $th) {
            Log::error('Error al eliminar el material: ' . $th->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al eliminar el material. Intenta nuevamente.');
        }
    }
}
