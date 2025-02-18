@extends('layouts.main')
@push('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf

                        @include('components/select2', [
                            'name' => 'Digital Platform',
                            'value' => 'digital_platform_id',
                            'options' => $digitalPlatform,
                        ])



                        <div class="input-group p-2 mt-2">
                            <label for="kelas_id"
                                class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
                            <select class="select mt-2 select-bordered w-full" name="kelas_id" id="kelas_id">
                                <option value="">--- Pilih Nama Kelas dan Jadwal ---</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}">
                                        {{ $k->name . ' - ' . $k->jadwal_name }}</option>
                                @endforeach
                            </select>
                            @error('kelas_id')
                                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        @include('components/select2', [
                            'name' => 'Nama Mitra',
                            'value' => 'mitra_id',
                            'options' => $mitras,
                        ])

                        <div class="input-group p-2 mt-2">
                            <label for="file"
                                class="text-black ms-2 font-semibold">{{ ucwords('Masukkan File Import') }}</label>
                            <input type="file" name="file" id="file"
                                class="file-input file-input-bordered w-full" />

                            @error('file')
                                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>




                        <div class="ms-3 mt-4 pb-3">
                            <button type="submit" class="btn btn-accent btn-sm">Simpan</button>
                            <a href="{{ URL::previous() }}" class="btn btn-neutral btn-sm">Kembali</a>
                        </div>

                    </form>
                </div>
                @if (!$error_imports->isEmpty())
                    <div class="mt-5">
                        <h1 class="text-center text-xl text-black">Data gagal import</h1>
                        <a href="/errors/delete" class="btn btn-sm btn-error mt-5">Hapus</a>
                        <a href="/export/errors/import/pembelian" type="button" class="btn btn-success btn-outline btn-sm"
                            id="btn-export">Export</a>
                        <table class="table mt-2">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Voucher</th>
                                    <th>Invoice</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($error_imports as $key => $error)
                                    <tr class="text-black">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $error->name }}</td>
                                        <td>{{ $error->email }}</td>
                                        <td>{{ $error->phone }}</td>
                                        <td>{{ $error->voucher }}</td>
                                        <td>{{ $error->invoice }}</td>
                                        <td>{{ $error->message }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const cb_kelas_id = @json(old('kelas_id')) ?? null
            const cb_digital_platform_id = @json(old('digital_platform_id')) ?? null
            const cb_mitra_id = @json(old('mitra_id')) ?? null

            $('#kelas_id').select2()
            $('#digital_platform_id').select2()
            $('#mitra_id').select2()

            if (cb_kelas_id) {
                $('#kelas_id').val(cb_kelas_id).trigger('change')
            }
            if (cb_mitra_id) {
                $('#mitra_id').val(cb_kelas_id).trigger('change')
            }
            if (cb_digital_platform_id) {
                $('#digital_platform_id').val(cb_kelas_id).trigger('change')
            }


        })
    </script>
@endpush
