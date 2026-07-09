<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GarmentCategory;

class GarmentCategoryController extends Controller
{
    public function index()
    {
        $categories = GarmentCategory::orderBy('name')->get();
        return view('garment-category.index', [
            'title' => 'Master Data: Kategori Pakaian',
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        GarmentCategory::create($request->all());
        return back()->withSuccess('Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, GarmentCategory $garment_category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $garment_category->update($request->all());
        return back()->withSuccess('Kategori berhasil diperbarui.');
    }

    public function destroy(GarmentCategory $garment_category)
    {
        $garment_category->delete();
        return back()->withSuccess('Kategori berhasil dihapus.');
    }
}
