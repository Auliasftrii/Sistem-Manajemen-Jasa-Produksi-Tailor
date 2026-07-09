<?php 
require 'vendor/autoload.php'; 
$app = require_once 'bootstrap/app.php'; 
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class); 
$kernel->bootstrap(); 
$startDate = now()->subDays(6)->startOfDay(); 
$endDate = now()->endOfDay(); 
$payments = \App\Models\Payment::select(
    \Illuminate\Support\Facades\DB::raw('DATE(payment_date) as date'), 
    \Illuminate\Support\Facades\DB::raw('SUM(amount) as total')
)->whereBetween('payment_date', [$startDate, $endDate])
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
echo json_encode(['data' => $chartData, 'dates' => $chartDates, 'payments' => $payments]);
