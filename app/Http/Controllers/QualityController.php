<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Item;


class QualityController extends Controller
{
    //

    public function Home()
    {
        $ordenes = Item::where('estado', '=', 'C.ENVIADA')->get();

        $contador = Item::where('estado', '=', 'C.ENVIADA')->count();


        return view('vistas.quality.home', compact('ordenes', 'contador'));
    }

    public function liberacion($id)
    {
        try {
            $orden = Item::findOrFail($id);
            $orden->estado = 'E.PENDIENTE';
            $orden->save();
            return redirect()->route('quality.home')->with('success', 'OT liberada con éxito.');
        } catch (\Throwable $th) {
            return redirect()->route('quality.home')->with('error', 'Hubo un problema al liberar la OT.');
        }
    }

    public function rechazo($id)
    {
        try {
            $orden = Item::findOrFail($id);
            $orden->estado = 'P.RECHAZADA';
            $orden->tecnico = null;
            $orden->save();
            return redirect()->route('quality.home')->with('success', 'OT rechazada con éxito.');
        } catch (\Throwable $th) {
            return redirect()->route('quality.home')->with('error', 'Hubo un problema al rechazar la OT.');
        }
    }
}
