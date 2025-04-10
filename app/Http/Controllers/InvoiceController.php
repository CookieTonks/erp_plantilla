<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Budget;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function home()
    {

        $ordenes = Budget::whereHas('items', function ($query) {
            $query->where('estado', 'E.ENTREGADO');
        })
            ->select('budgets.*')
            ->distinct()
            ->get();


        $partidas = Item::where('estado', 'E.ENTREGADO')->get();

        $contador = Item::where('estado', 'E.ENTREGADO')->count();

        $clientes = Client::all();

        $facturas = Invoice::all();

        return view('vistas.invoice.home', compact('ordenes', 'contador', 'clientes', 'facturas', 'partidas'));
    }

    public function liberacion($otId, Request $request)
    {

        dd($otId, $request->all());
    }


    public function invoice_alta(Request $request)
    {


        try {
            $invoice = new Invoice();
            $invoice->codigo = $request->codigo;
            $invoice->empresa = $request->razon_social;
            $invoice->estatus = 'PENDIENTE';
            $invoice->cliente = $request->client_id;
            $invoice->save();
            return back()->with('success', 'Factura dada de alta con éxito!');
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un problema al dar de alta la factura, por favor intenta de nuevo!' . $th);
        }
    }

    public function partida_factura(Request $request)
    {

        try {
            $partida = Item::find($request->partida_id);
            $partida->invoice_number = $request->factura_id;
            $partida->save();
            return back()->with('success', 'Partida actualizada con éxito!');
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un problema al actualizar la partida, por favor intenta de nuevo!' . $th);
        }
    }

    public function invoice_partidas($id)
    {

        $partidas = Item::with(['budget', 'budget.client', 'budget.user', 'invoice'])
            ->where('invoice_number', $id)
            ->get();



        return response()->json($partidas); // Devuelve la respuesta en formato JSON.


    }

    public function invoice_estatus($id, Request $request)
    {
        try {
            $invoice = Invoice::find($id);
            $invoice->estatus = $request->estatus;
            $invoice->save();
            return back()->with('success', 'Estatus actualizado con éxito!');
        } catch (\Throwable $th) {
            return back()->with('error', '¡Hubo un problema al actualizar el estatus, por favor intenta de nuevo!' . $th);
        }
    }
}
