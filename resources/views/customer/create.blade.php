<x-app>

    <x-slot:title>{{ $title }}</x-slot:title>

    <div class="card shadow-lg p-3">

        <form action="{{ route('customer.store') }}" method="post" class="form">
            @csrf

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <h5 class="mb-3">Informasi Pelanggan</h5>
                    <div class="mb-3">
                        <label for="name" class="form-label required">Nama</label>
                        <input class="form-control @error('name') is-invalid  @enderror" type="text" id="name"
                            name="name" required value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">No. HP / Telepon</label>
                        <input class="form-control @error('phone') is-invalid  @enderror" type="text" id="phone"
                            name="phone" value="{{ old('phone') }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Alamat</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <h5 class="mb-3">Ukuran Badan (Measurements)</h5>
                    <div id="measurements-container">
                        <!-- Dynamic inputs will be added here -->
                        @if(old('measurements'))
                            @foreach(old('measurements') as $garmentId => $meas)
                                @foreach($meas as $key => $val)
                                    <div class="row mb-2 measurement-row">
                                        <div class="col-4">
                                            <select class="form-select measurement-category">
                                                @foreach($garmentCategories as $cat)
                                                    <option value="{{ $cat->id }}" @selected($cat->id == $garmentId)>{{ $cat->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <input type="text" class="form-control measurement-key" value="{{ $key }}" placeholder="Bagian">
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="form-control measurement-val" value="{{ $val }}" placeholder="Nilai (ex: 90 cm)">
                                        </div>
                                        <div class="col-1">
                                            <button type="button" class="btn btn-danger btn-sm btn-remove-measurement"><i class="bx bx-trash"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                        @else
                            <div class="row mb-2 measurement-row">
                                <div class="col-4">
                                    <select class="form-select measurement-category">
                                        @foreach($garmentCategories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="text" class="form-control measurement-key" placeholder="Bagian">
                                </div>
                                <div class="col-4">
                                    <input type="text" class="form-control measurement-val" placeholder="Nilai (ex: 90 cm)">
                                </div>
                                <div class="col-1">
                                    <button type="button" class="btn btn-danger btn-sm btn-remove-measurement"><i class="bx bx-trash"></i></button>
                                </div>
                            </div>
                        @endif
                    </div>
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="add-measurement">Tambah Ukuran</button>
                </div>
            </div>

            <div class="text-end mt-4">
                <a href="{{ route('customer.index') }}" class="btn btn-warning me-1">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>

        </form>

    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#add-measurement').click(function() {
                    let categoryOptions = '';
                    @foreach($garmentCategories as $cat)
                        categoryOptions += `<option value="{{ $cat->id }}">{{ $cat->name }}</option>`;
                    @endforeach

                    const row = `
                        <div class="row mb-2 measurement-row">
                            <div class="col-4">
                                <select class="form-select measurement-category">
                                    ${categoryOptions}
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="text" class="form-control measurement-key" placeholder="Bagian">
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control measurement-val" placeholder="Nilai (ex: 90 cm)">
                            </div>
                            <div class="col-1">
                                <button type="button" class="btn btn-danger btn-sm btn-remove-measurement"><i class="bx bx-trash"></i></button>
                            </div>
                        </div>
                    `;
                    $('#measurements-container').append(row);
                });

                $(document).on('click', '.btn-remove-measurement', function() {
                    $(this).closest('.measurement-row').remove();
                });

                // Update name attribute based on key before submit
                $('form').submit(function() {
                    $('.measurement-row').each(function() {
                        let garmentId = $(this).find('.measurement-category').val();
                        let key = $(this).find('.measurement-key').val();
                        let valInput = $(this).find('.measurement-val');
                        if (key && key.trim() !== '') {
                            valInput.attr('name', 'measurements[' + garmentId + '][' + key.trim() + ']');
                        }
                    });
                });
            });
        </script>
    @endpush

</x-app>
