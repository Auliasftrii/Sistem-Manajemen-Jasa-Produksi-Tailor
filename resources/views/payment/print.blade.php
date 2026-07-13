<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Struk/Nota Pembayaran - {{ $order->invoice_number }}</title>
    <style>
        body { font-family: 'Courier New', Courier, monospace; margin: 0; padding: 20px; font-size: 14px; }
        .receipt-container { max-width: 800px; margin: auto; border: 1px solid #ddd; padding: 30px; }
        .header { text-align: center; border-bottom: 2px dashed #000; padding-bottom: 10px; margin-bottom: 20px; }
        .header h2 { margin: 0; }
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 5px 0; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #000; padding: 8px; text-align: left; }
        .table th { background-color: #f2f2f2; }
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        .footer { margin-top: 30px; text-align: center; font-style: italic; }
        .no-print { text-align: center; margin-bottom: 20px; }
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .receipt-container { border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">🖨️ Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">Tutup</button>
    </div>

    <div class="receipt-container">
        <div class="header">
            <h2>SISTEM TAILOR - NOTA PEMBAYARAN</h2>
            <p>Bukti sah transaksi pembayaran pemesanan pakaian.</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="20%"><strong>No. Invoice</strong></td>
                <td width="40%">: {{ $order->invoice_number }}</td>
                <td width="20%"><strong>Tanggal Cetak</strong></td>
                <td width="20%">: {{ date('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <td><strong>Pelanggan</strong></td>
                <td>: {{ $order->customer->name ?? '-' }} ({{ $order->customer->phone ?? '-' }})</td>
                <td><strong>Total Tagihan</strong></td>
                <td>: Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
            </tr>
        </table>

        <h4>Rincian Pembayaran:</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th class="text-right">Nominal (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($order->payments as $payment)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y') }}</td>
                        <td>{{ $payment->payment_method }}</td>
                        <td>{{ $payment->status }}</td>
                        <td class="text-right">{{ number_format($payment->amount, 0, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Belum ada pembayaran yang dicatat.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                @php
                    $totalDibayar = $order->payments->sum('amount');
                    $sisaTagihan = $order->total_amount - $totalDibayar;
                @endphp
                <tr>
                    <th colspan="4" class="text-right">TOTAL DIBAYAR</th>
                    <th class="text-right">Rp {{ number_format($totalDibayar, 0, ',', '.') }}</th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right">SISA TAGIHAN</th>
                    <th class="text-right">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div style="margin-top: 20px; font-weight: bold; font-size: 16px;">
            Status Pembayaran: 
            @if($sisaTagihan <= 0 && $order->total_amount > 0)
                <span style="border: 2px solid green; color: green; padding: 5px 10px;">LUNAS</span>
            @else
                <span style="border: 2px solid orange; color: orange; padding: 5px 10px;">BELUM LUNAS</span>
            @endif
        </div>

        <div class="footer">
            <div style="border: 1px dashed #000; padding: 10px; margin-bottom: 20px; text-align: center;">
                <strong>🔍 Pantau Progres Pesanan Anda Secara Online!</strong><br>
                Buka link: <strong>{{ url('/lacak-pesanan') }}</strong><br>
                Masukkan No. Invoice <strong>{{ $order->invoice_number }}</strong>.
            </div>
            <p>Terima kasih atas kepercayaannya. Kami menunggu kedatangan Anda kembali!</p>
            <p>--- Dokumen ini digenerate secara otomatis oleh sistem ---</p>
        </div>
    </div>
</body>
</html>
