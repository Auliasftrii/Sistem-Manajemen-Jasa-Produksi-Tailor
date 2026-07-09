<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer.index', [
            'title' => 'Customer',
            'customers' => Customer::latest()->get(),
        ]);
    }

    public function create()
    {
        return view('customer.create', [
            'title' => 'Create Customer',
            'garmentCategories' => \App\Models\GarmentCategory::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
            'measurements' => 'nullable|array' // structure: measurements[garment_id][key] = value
        ], [
            'name.required' => 'Nama wajib diisi',
            'measurements.array' => 'Format ukuran tidak valid',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $customer = Customer::create([
                'name' => $validate['name'],
                'phone' => $validate['phone'] ?? null,
                'address' => $validate['address'] ?? null,
            ]);

            if (!empty($validate['measurements'])) {
                foreach ($validate['measurements'] as $garmentId => $measurements) {
                    foreach ($measurements as $key => $value) {
                        if (!empty($key) && !empty($value)) {
                            $customer->measurements()->create([
                                'garment_category_id' => $garmentId,
                                'measurement_key' => $key,
                                'measurement_value' => $value,
                            ]);
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return to_route('customer.index')->withSuccess('Data pelanggan berhasil ditambahkan');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return to_route('customer.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        $customer->load('measurements.garmentCategory');
        return view('customer.show', [
            'title' => 'Detail Customer',
            'customer' => $customer,
        ]);
    }

    public function edit(Customer $customer)
    {
        $customer->load('measurements.garmentCategory');
        return view('customer.edit', [
            'title' => 'Edit Customer',
            'customer' => $customer,
            'garmentCategories' => \App\Models\GarmentCategory::all(),
        ]);
    }

    public function update(Request $request, Customer $customer)
    {
        $validate = $request->validate([
            'name' => 'required',
            'phone' => 'nullable',
            'address' => 'nullable',
            'measurements' => 'nullable|array'
        ], [
            'name.required' => 'Nama wajib diisi',
            'measurements.array' => 'Format ukuran tidak valid',
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();
        try {
            $customer->update([
                'name' => $validate['name'],
                'phone' => $validate['phone'] ?? null,
                'address' => $validate['address'] ?? null,
            ]);

            // Clear old measurements
            $customer->measurements()->delete();

            if (!empty($validate['measurements'])) {
                foreach ($validate['measurements'] as $garmentId => $measurements) {
                    foreach ($measurements as $key => $value) {
                        if (!empty($key) && !empty($value)) {
                            $customer->measurements()->create([
                                'garment_category_id' => $garmentId,
                                'measurement_key' => $key,
                                'measurement_value' => $value,
                            ]);
                        }
                    }
                }
            }

            \Illuminate\Support\Facades\DB::commit();
            return to_route('customer.index')->withSuccess('Data pelanggan berhasil diubah');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return to_route('customer.edit', $customer)->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }

    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return to_route('customer.index')->withSuccess('Data pelanggan berhasil dihapus');
        } catch (\Exception $e) {
            return to_route('customer.index')->withError('Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
