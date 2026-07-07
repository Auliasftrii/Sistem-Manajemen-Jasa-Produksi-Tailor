<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPesanan = \App\Models\Order::count();
        $pelanggan = \App\Models\Customer::count();
        $pesananBelumSelesai = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
        
        // Pendapatan bulan ini dari payment (total payment yang sukses)
        $pendapatanBulanIni = \App\Models\Payment::whereMonth('payment_date', now()->month)
            ->whereYear('payment_date', now()->year)
            ->sum('amount');

        // Data untuk grafik 7 hari terakhir
        $chartDates = collect(range(6, 0))->map(function($days) {
            return now()->subDays($days)->format('Y-m-d');
        });

        $chartData = $chartDates->map(function($date) {
            return \App\Models\Payment::whereDate('payment_date', $date)->sum('amount');
        });

        return view('dashboard.index', [
            'title' => 'Dashboard',
            'totalPesanan' => $totalPesanan,
            'pelanggan' => $pelanggan,
            'pesananBelumSelesai' => $pesananBelumSelesai,
            'pendapatanBulanIni' => $pendapatanBulanIni,
            'chartDates' => $chartDates->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))->toArray(),
            'chartData' => $chartData->toArray(),
        ]);
    }

    public function show()
    {
        return view('dashboard.show', [
            'title' => 'My Profile',
            'user' => Auth::user()
        ]);
    }

    public function edit()
    {
        return view('dashboard.edit', [
            'title' => 'Edit Profile',
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $validate = $request->validate([
                'name' => 'required',
                'password' => 'nullable|min:8',
                'passwordconfirm' => 'nullable|same:password',
                'email' => 'required|email|lowercase|unique:users,email,' . $user->id,
                'avatar' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:512'
            ], [
                'name.required' => 'Nama wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'passwordconfirm.same' => 'Konfirmasi password tidak cocok',
                'email.required' => 'Email wajib diisi',
                'email.email' => 'Format email tidak valid',
                'email.unique' => 'Email sudah terdaftar',
                'avatar.image' => 'File avatar harus berupa gambar',
                'avatar.mimes' => 'Format avatar harus png, jpg, jpeg, atau svg',
                'avatar.max' => 'Ukuran avatar tidak boleh lebih dari 512 KB',
            ]);

            if ($request->file('avatar')) {
                $validate['avatar'] = $request->file('avatar')->store('img', 'public');
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }
            }

            if ($request->password) {
                $validate['password'] = bcrypt($request->password);
            } else {
                unset($validate['password']);
            }
            $user->update($validate);

            DB::commit();
            return to_route('dashboard.show')->withSuccess('Data berhasil diubah');
        } catch (\Exception $e) {
            DB::rollBack();
            return to_route('dashboard.edit')->withError('Gagal mengubah data: ' . $e->getMessage());
        }
    }
}
