<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Budget;
use App\Models\Item;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\ClientUser;
use App\Models\Invoice;
use Spatie\Permission\Models\Role;


class AdministrationController extends Controller
{
    public function home()
    {
        $budgetMonto = Budget::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '!=', 'rechazado')
            ->sum('monto');

        $budgetOpen = Budget::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '=', 'ABIERTA')
            ->count();


        $budgetClosed = Budget::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '=', 'CERRADA')
            ->count();


        $budgetRejected = Budget::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '=', 'RECHAZADA')
            ->count();

        $budgetsBySeller = Budget::join('users', 'budgets.user_id', '=', 'users.id')
            ->whereYear('budgets.created_at', Carbon::now()->year)
            ->whereMonth('budgets.created_at', Carbon::now()->month)
            ->groupBy('budgets.user_id', 'users.name')
            ->selectRaw('users.name as vendedor, COUNT(*) as total')
            ->get();


        $budgetsByClient = Budget::join('users', 'budgets.user_id', '=', 'users.id')
            ->join('clients', 'budgets.client_id', '=', 'clients.id')  // Asegúrate de tener la relación con 'clients'
            ->whereYear('budgets.created_at', Carbon::now()->year)
            ->whereMonth('budgets.created_at', Carbon::now()->month)
            ->groupBy('budgets.client_id', 'clients.name') // Agrupar por client_id y client name
            ->selectRaw('clients.name as cliente, COUNT(*) as total')
            ->get();

        $budgetsByMonth = Budget::selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', Carbon::now()->year)  // Año actual (puedes cambiarlo si quieres todos los años)
            ->groupBy('year', 'month')  // Agrupar por año y mes
            ->orderBy('year', 'asc')  // Ordenar por año descendente
            ->orderBy('month', 'asc') // Ordenar por mes descendente
            ->get();


        $budgetStatus = Budget::selectRaw('MONTH(created_at) as month, 
            COUNT(CASE WHEN estado = "APROBADA" OR estado = "PROCESO" THEN 1 END) as aprobadas_en_proceso, 
            COUNT(CASE WHEN estado = "RECHAZADA" THEN 1 END) as rechazadas')
            ->whereYear('created_at', Carbon::now()->year) // Current year
            ->whereMonth('created_at', Carbon::now()->month) // Current month
            ->groupBy('month')
            ->get()
            ->map(function ($item) {
                // Convert the month number to month name using Carbon
                $item->month_name = Carbon::parse("2022-{$item->month}-01")->format('F');
                return $item;
            });




        //Inicio desgloce de ordenes
        $itemProcess = Item::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '!=', 'F.CERRADA')
            ->where('estado', '!=', 'RECHAZADA')
            ->count();

        $itemToDelivery = Item::join('budgets', 'items.budget_id', '=', 'budgets.id')
            ->whereYear('items.created_at', Carbon::now()->year)
            ->whereMonth('items.created_at', Carbon::now()->month)
            ->where('items.estado', '!=', 'F.CERRADA')
            ->where('items.estado', '!=', 'RECHAZADA')
            ->whereBetween('budgets.delivery_date', [
                Carbon::now()->addDays(1)->startOfDay(), // Start of the next day
                Carbon::now()->addDays(7)->endOfDay()    // End of the 7th day
            ])
            ->count();


        $itemPastDelivery = Item::join('budgets', 'items.budget_id', '=', 'budgets.id')
            ->where('items.estado', '!=', 'F.CERRADA')
            ->where('items.estado', '!=', 'RECHAZADA')
            ->where('budgets.delivery_date', '<', Carbon::now()) // Delivery date has passed
            ->count();


        $itemClosed = Item::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estado', '=', 'F.CERRADA')
            ->count();



        $invoicePaid = Invoice::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estatus', '=', 'Pagada')
            ->count();

        $invoiceSubtotal = Invoice::whereYear('invoices.created_at', Carbon::now()->year)
            ->whereMonth('invoices.created_at', Carbon::now()->month)
            ->where('invoices.estatus', 'Pagada')
            ->join('items', 'items.invoice_number', '=', 'invoices.id')
            ->sum('items.subtotal');



        $invoicePortal = Invoice::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estatus', '=', 'PORTAL')
            ->count();

        $invoiceClient = Invoice::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->where('estatus', '=', 'CLIENTE')
            ->count();


        $ordenes = Item::with('budget')->get();

        $roles = Role::all();


        $clientes = Client::all();

        return view(
            'vistas.administration.home',
            compact(
                'budgetMonto',
                'budgetOpen',
                'budgetClosed',
                'budgetRejected',
                'budgetsBySeller',
                'budgetsByClient',
                'budgetsByMonth',
                'budgetStatus',
                'itemProcess',
                'itemToDelivery',
                'itemPastDelivery',
                'itemClosed',
                'ordenes',
                'roles',
                'clientes',
                'invoiceClient',
                'invoicePaid',
                'invoicePortal',
                'invoiceSubtotal',
            )
        );
    }


    public function proveedor(Request $request)
    {
        try {
            $proveedor = new Supplier();
            $proveedor->nombre = $request->nombre;
            $proveedor->razon_social = $request->razon_social;
            $proveedor->direccion = $request->direccion;
            $proveedor->save();
            return redirect()->route('administration.home')
                ->with('success', '¡Proveedor dado de alta con exito!');
        } catch (\Exception $e) {
            return redirect()->route('administration.home')
                ->with('error', 'Ocurrió un error al dar de alta el proveedor. Intenta nuevamente.');
        }
    }


    public function cliente(Request $request)
    {

        try {
            $cliente = new Client();
            $cliente->name = $request->nombre;
            $cliente->rfc = $request->rfc;
            $cliente->email = $request->email;
            $cliente->phone = $request->telefono;
            $cliente->address = $request->direccion;
            $cliente->save();
            return redirect()->route('administration.home')
                ->with('success', '¡Cliente dado de alta con exito!');
        } catch (\Exception $e) {
            return redirect()->route('administration.home')
                ->with('error', 'Ocurrió un error al dar de alta el cliente. Intenta nuevamente.');
        }
    }


    public function clienteUsuario(Request $request)
    {
        try {
            $cliente = new ClientUser();
            $cliente->name = $request->nombre;
            $cliente->client_id = $request->cliente_id;
            $cliente->email = $request->email;
            $cliente->phone = $request->telefono;
            $cliente->save();
            return redirect()->route('administration.home')
                ->with('success', '¡Usuario dado de alta con exito!');
        } catch (\Exception $e) {
            return redirect()->route('administration.home')
                ->with('error', 'Ocurrió un error al dar de alta el usuario. Intenta nuevamente.' . $e);
        }
    }

    public function empleado(Request $request)
    {
        try {
            $user = new User();
            $user->name = $request->nombre;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $role = Role::findById($request->role);
            $user->assignRole($role->name);

            return redirect()->route('administration.home')
                ->with('success', 'Usuario dado de alta con exito!');
        } catch (\Exception $e) {
            return redirect()->route('administration.home')
                ->with('error', 'Ocurrió un error al dar de alta al usuario. Intenta nuevamente.');
        }
    }
}
