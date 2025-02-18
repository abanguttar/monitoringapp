@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <form action="" method="POST" enctype="multipart/form-data">
                        @csrf


                        @php
                            $isPayment = ['refund', 'payment'];
                        @endphp

                        @if (in_array($tipe, $isPayment))
                            <div>
                                @include('components/select', [
                                    'name' => 'Pilih Pembayaran yang ingin di update',
                                    'value' => 'tipe',
                                    'options' => [
                                        (object) [
                                            'title' => 'Redeemtion',
                                            'value' => 'Redeemtion',
                                        ],
                                        (object) [
                                            'title' => 'Completion',
                                            'value' => 'Completion',
                                        ],
                                    ],
                                    'data' => null,
                                ])
                            </div>
                        @endif

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
                        @if (!in_array($tipe, $isPayment))
                            <a href="/export/errors/import/{{ $tipe === 'redemption' ? 'redeemtion' : 'completion' }}"
                                type="button" class="btn btn-success btn-outline btn-sm" id="btn-export">Export</a>
                        @else
                            <a href="/export/errors/import/{{ $tipe === 'payment' ? 'payment' : 'refund' }}" type="button"
                                class="btn btn-success btn-outline btn-sm" id="btn-export">Export</a>
                        @endif

                        <table class="table mt-2">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    @if (in_array($tipe, $isPayment))
                                        {{-- <th>Nama</th>
                                        <th>Email</th> --}}
                                        <th>Invoice</th>
                                        @if ($tipe === 'payment')
                                            <th>Periode</th>
                                        @else
                                            <th>Keterangan</th>
                                        @endif
                                    @else
                                        <th>Voucher</th>
                                        @if ($tipe === 'redemption')
                                            <th>Kode Redeem</th>
                                            <th>Tanggal Redeem</th>
                                        @else
                                            <th>100% Pelatihan</th>
                                        @endif
                                    @endif
                                    <th>Keterangan Gagal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (in_array($tipe, $isPayment))
                                    @foreach ($error_imports as $key => $error)
                                        <tr class="text-black">
                                            <td>{{ ++$key }}</td>
                                            {{-- <td>{{ $error->name }}</td>
                                            <td>{{ $error->email }}</td> --}}
                                            <td>{{ $error->invoice }}</td>
                                            @if ($tipe === 'payment')
                                                <td>{{ session('tipe_import') === 'Redeemtion' ? $error->redeem_period : $error->finish_period }}
                                                @else
                                                <td>{{ session('tipe_import') === 'Redeemtion' ? $error->redeem_note : $error->finish_note }}
                                            @endif
                                            </td>
                                            <td>{{ $error->message }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @if ($tipe === 'redemption')
                                        @foreach ($error_imports as $key => $error)
                                            <tr class="text-black">
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $error->voucher }}</td>
                                                <td>{{ $error->redeem_code }}</td>
                                                <td>{{ $error->redeem_at }}</td>
                                                <td>{{ $error->message }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($error_imports as $key => $error)
                                            <tr class="text-black">
                                                <td>{{ ++$key }}</td>
                                                <td>{{ $error->voucher }}</td>
                                                <td>{{ $error->finish_at }}</td>
                                                <td>{{ $error->message }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endif
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).ready(function() {
            const value = @json(old('tipe')) ?? null
            $('#tipe').val(value)
        })
    </script>
@endpush
