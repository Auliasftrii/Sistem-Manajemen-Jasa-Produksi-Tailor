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
        $user = Auth::user();
        $role = $user->role;

        // Data khusus per Role
        if ($role == 'Superadmin') {
            $totalPesanan = \App\Models\Order::count();
            $pelanggan = \App\Models\Customer::count();
            $pesananBelumSelesai = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
            
            $pendapatanBulanIni = \App\Models\Payment::whereMonth('payment_date', now()->month)
                ->whereYear('payment_date', now()->year)
                ->sum('amount');

            // Chart Data: Pendapatan 7 hari terakhir (Aggregate Query)
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();
            
            $payments = \App\Models\Payment::select(
                DB::raw('DATE(payment_date) as date'),
                DB::raw('SUM(amount) as total')
            )
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

            $chartDates = [];
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $dateStr = now()->subDays($i)->format('Y-m-d');
                $chartDates[] = \Carbon\Carbon::parse($dateStr)->format('d M');
                $chartData[] = $payments->has($dateStr) ? (float) $payments[$dateStr] : 0;
            }

            return view('dashboard.index', [
                'title' => 'Dashboard Superadmin',
                'role' => $role,
                'totalPesanan' => $totalPesanan,
                'pelanggan' => $pelanggan,
                'pesananBelumSelesai' => $pesananBelumSelesai,
                'pendapatanBulanIni' => $pendapatanBulanIni,
                'chartDates' => $chartDates,
                'chartData' => $chartData,
                'chartName' => 'Pendapatan (Rp)',
            ]);
        } 
        elseif ($role == 'Admin') {
            $totalPesanan = \App\Models\Order::count();
            $pesananBaru = \App\Models\Order::where('status', 'pending')->count();
            $komplainAktif = \App\Models\OrderRevision::whereIn('status', ['Pending', 'In Progress'])->count();
            $stokKain = \App\Models\FabricStock::sum('quantity_in_meters');

            // Chart Data: Pesanan baru 7 hari terakhir (Aggregate Query)
            $startDate = now()->subDays(6)->startOfDay();
            $endDate = now()->endOfDay();

            $orders = \App\Models\Order::select(
                DB::raw('DATE(order_date) as date'),
                DB::raw('COUNT(id) as total')
            )
            ->whereBetween('order_date', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

            $chartDates = [];
            $chartData = [];
            for ($i = 6; $i >= 0; $i--) {
                $dateStr = now()->subDays($i)->format('Y-m-d');
                $chartDates[] = \Carbon\Carbon::parse($dateStr)->format('d M');
                $chartData[] = $orders->has($dateStr) ? (float) $orders[$dateStr] : 0;
            }

            return view('dashboard.index', [
                'title' => 'Dashboard Admin',
                'role' => $role,
                'totalPesanan' => $totalPesanan,
                'pesananBaru' => $pesananBaru,
                'komplainAktif' => $komplainAktif,
                'stokKain' => $stokKain,
                'chartDates' => $chartDates,
                'chartData' => $chartData,
                'chartName' => 'Total Pesanan (Order)',
            ]);
        } 
        else {
            // Pegawai (Tailor)
            $tailor = $user->tailor;
            $tugasSaya = 0;
            $tugasSelesai = 0;
            $tugasBelumSelesai = 0;
            
            $chartDates = [];
            $chartData = [];

            if ($tailor) {
                $tugasSaya = \App\Models\ProductionTracking::where('tailor_id', $tailor->id)->count();
                $tugasSelesai = \App\Models\ProductionTracking::where('tailor_id', $tailor->id)->where('status', 'completed')->count();
                $tugasBelumSelesai = \App\Models\ProductionTracking::where('tailor_id', $tailor->id)->whereIn('status', ['pending', 'in_progress'])->count();
                
                // Chart Data: Tugas diselesaikan 7 hari terakhir (Aggregate Query)
                $startDate = now()->subDays(6)->startOfDay();
                $endDate = now()->endOfDay();

                $tasks = \App\Models\ProductionTracking::select(
                    DB::raw('DATE(completed_at) as date'),
                    DB::raw('COUNT(id) as total')
                )
                ->where('tailor_id', $tailor->id)
                ->where('status', 'completed')
                ->whereBetween('completed_at', [$startDate, $endDate])
                ->groupBy('date')
                ->orderBy('date')
                ->pluck('total', 'date');

                for ($i = 6; $i >= 0; $i--) {
                    $dateStr = now()->subDays($i)->format('Y-m-d');
                    $chartDates[] = \Carbon\Carbon::parse($dateStr)->format('d M');
                    $chartData[] = $tasks->has($dateStr) ? (float) $tasks[$dateStr] : 0;
                }
            } else {
                for ($i = 6; $i >= 0; $i--) {
                    $dateStr = now()->subDays($i)->format('Y-m-d');
                    $chartDates[] = \Carbon\Carbon::parse($dateStr)->format('d M');
                    $chartData[] = 0;
                }
            }

            return view('dashboard.index', [
                'title' => 'Dashboard Pegawai',
                'role' => $role,
                'tugasSaya' => $tugasSaya,
                'tugasSelesai' => $tugasSelesai,
                'tugasBelumSelesai' => $tugasBelumSelesai,
                'chartDates' => $chartDates,
                'chartData' => $chartData,
                'chartName' => 'Tugas Diselesaikan',
            ]);
        }
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
