<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fabric;
use App\Models\FabricStock;

class FabricController extends Controller
{
    public function index()
    {
        $fabrics = Fabric::withSum('stocks', 'quantity_in_meters')->orderBy('name')->get();
        return view('fabric.index', [
            'title' => 'Master Data: Kain & Stok',
            'fabrics' => $fabrics,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fabric_type' => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        Fabric::create($request->all());
        return back()->withSuccess('Data kain berhasil ditambahkan.');
    }

    public function update(Request $request, Fabric $fabric)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'fabric_type' => 'required|string|max:255',
            'color' => 'required|string|max:255',
        ]);

        $fabric->update($request->all());
        return back()->withSuccess('Data kain berhasil diperbarui.');
    }

    public function destroy(Fabric $fabric)
    {
        $fabric->delete();
        return back()->withSuccess('Data kain berhasil dihapus.');
    }

    public function addStock(Request $request, Fabric $fabric)
    {
        $request->validate([
            'quantity_in_meters' => 'required|numeric|min:0.1',
        ]);

        FabricStock::create([
            'fabric_id' => $fabric->id,
            'quantity_in_meters' => $request->quantity_in_meters,
            'last_restock_date' => now()->toDateString(),
        ]);

        return back()->withSuccess('Stok kain berhasil ditambahkan.');
    }
}
