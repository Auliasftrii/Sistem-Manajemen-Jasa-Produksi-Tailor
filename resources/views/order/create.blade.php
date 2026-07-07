<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('order.store') }}" method="post" class="form">
            @csrf

            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="customer_id" class="form-label required">Pelanggan</label>
                    <select class="form-select select2-default @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                        <option value="">Pilih Pelanggan</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" @selected(old('customer_id') == $customer->id)>{{ $customer->name }} ({{ $customer->phone }})</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="order_date" class="form-label required">Tanggal Pesanan</label>
                    <input type="date" class="form-control @error('order_date') is-invalid @enderror" id="order_date" name="order_date" value="{{ old('order_date', date('Y-m-d')) }}" required>
                    @error('order_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-4">
                    <label for="expected_completion_date" class="form-label">Perkiraan Selesai</label>
                    <input type="date" class="form-control @error('expected_completion_date') is-invalid @enderror" id="expected_completion_date" name="expected_completion_date" value="{{ old('expected_completion_date') }}">
                    @error('expected_completion_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <h5 class="mb-3">Item Pesanan (Pakaian)</h5>
            @error('items')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            
            <div class="table-responsive mb-3">
                <table class="table table-bordered" id="items-table">
                    <thead class="table-light">
                        <tr>
                            <th width="25%">Jenis Pakaian</th>
                            <th width="35%">Detail Kain/Catatan</th>
                            <th width="10%">Qty</th>
                            <th width="20%">Harga Satuan (Rp)</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="items-container">
                        @php $oldItems = old('items', []); @endphp
                        
                        @if(count($oldItems) > 0)
                            @foreach($oldItems as $index => $item)
                                <tr class="item-row">
                                    <td>
                                        <input type="text" class="form-control" name="items[{{ $index }}][product_type]" value="{{ $item['product_type'] }}" required placeholder="Kemeja, Celana...">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="items[{{ $index }}][fabric_details]" value="{{ $item['fabric_details'] ?? '' }}" placeholder="Kain Katun Biru...">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control qty-input" name="items[{{ $index }}][quantity]" value="{{ $item['quantity'] }}" required min="1">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control price-input" name="items[{{ $index }}][unit_price]" value="{{ $item['unit_price'] }}" required min="0">
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm btn-remove-item"><i class="bx bx-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="item-row">
                                <td>
                                    <input type="text" class="form-control" name="items[0][product_type]" required placeholder="Kemeja, Celana...">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="items[0][fabric_details]" placeholder="Kain Katun Biru...">
                                </td>
                                <td>
                                    <input type="number" class="form-control qty-input" name="items[0][quantity]" value="1" required min="1">
                                </td>
                                <td>
                                    <input type="number" class="form-control price-input" name="items[0][unit_price]" value="0" required min="0">
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-item"><i class="bx bx-trash"></i></button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <button type="button" class="btn btn-secondary btn-sm" id="add-item">Tambah Item</button>
                            </td>
                        </tr>
                        <tr>
                            <th colspan="3" class="text-end">Estimasi Total :</th>
                            <th colspan="2">
                                <span id="total-display" class="fw-bold fs-5">Rp 0</span>
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('order.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Pesanan</button>
            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                let itemIndex = {{ count($oldItems) > 0 ? count($oldItems) : 1 }};

                function calculateTotal() {
                    let total = 0;
                    $('.item-row').each(function() {
                        let qty = parseFloat($(this).find('.qty-input').val()) || 0;
                        let price = parseFloat($(this).find('.price-input').val()) || 0;
                        total += (qty * price);
                    });
                    
                    $('#total-display').text('Rp ' + total.toLocaleString('id-ID'));
                }

                $('#add-item').click(function() {
                    const row = `
                        <tr class="item-row">
                            <td>
                                <input type="text" class="form-control" name="items[${itemIndex}][product_type]" required placeholder="Kemeja, Celana...">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="items[${itemIndex}][fabric_details]" placeholder="Kain Katun Biru...">
                            </td>
                            <td>
                                <input type="number" class="form-control qty-input" name="items[${itemIndex}][quantity]" value="1" required min="1">
                            </td>
                            <td>
                                <input type="number" class="form-control price-input" name="items[${itemIndex}][unit_price]" value="0" required min="0">
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-item"><i class="bx bx-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    $('#items-container').append(row);
                    itemIndex++;
                    calculateTotal();
                });

                $(document).on('click', '.btn-remove-item', function() {
                    if($('.item-row').length > 1) {
                        $(this).closest('tr').remove();
                        calculateTotal();
                    } else {
                        alert('Minimal harus ada 1 item pesanan.');
                    }
                });

                $(document).on('input', '.qty-input, .price-input', function() {
                    calculateTotal();
                });

                // Init calc
                calculateTotal();
            });
        </script>
    @endpush

</x-app>
