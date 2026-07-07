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
        ]);
    }

    public function store(Request $request)
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

        try {
            Customer::create($validate);
            return to_route('customer.index')->withSuccess('Data pelanggan berhasil ditambahkan');
        } catch (\Exception $e) {
            return to_route('customer.create')->withError('Gagal menambahkan data: ' . $e->getMessage());
        }
    }

    public function show(Customer $customer)
    {
        return view('customer.show', [
            'title' => 'Detail Customer',
            'customer' => $customer,
        ]);
    }

    public function edit(Customer $customer)
    {
        return view('customer.edit', [
            'title' => 'Edit Customer',
            'customer' => $customer,
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

        try {
            $customer->update($validate);
            return to_route('customer.index')->withSuccess('Data pelanggan berhasil diubah');
        } catch (\Exception $e) {
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
