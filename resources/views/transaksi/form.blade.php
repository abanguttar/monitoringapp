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
                    <form action="" method="POST">
                        @csrf
                        @if ($title !== 'Peserta Add Pelatihan')
                            @method('PUT')
                        @endif

                        @if ($title === 'Peserta Add Pelatihan')
                            @include('components/select2', [
                                'name' => 'Nama Peserta',
                                'value' => 'peserta_id',
                                'options' => $pesertas,
                            ])
                        @endif


                        @include('components/input', [
                            'name' => 'Nama peserta',
                            'value' => 'name',
                            'data' => $transaction->peserta ?? null,
                            'disable' => true,
                        ])
                        @include('components/input', [
                            'name' => 'Email peserta',
                            'value' => 'email',
                            'data' => $transaction->peserta ?? null,
                            'disable' => true,
                        ])

                        @include('components/input', [
                            'name' => 'No Hp',
                            'value' => 'phone',
                            'data' => $transaction->peserta ?? null,
                            'disable' => true,
                        ])

                        @include('components/select2', [
                            'name' => 'Digital Platform',
                            'value' => 'digital_platform_id',
                            'options' => $digitalPlatforms,
                        ])

                        <div class="input-group p-2 mt-2">
                            <label for="kelas_id"
                                class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
                            <select class="select mt-2 select-bordered w-full" name="kelas_id" id="kelas_id"
                                @if ($title !== 'Peserta Add Pelatihan') disabled @endif>
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
                        @if ($title !== 'Peserta Add Pelatihan')
                            <input type="text" name="kelas_id" value="{{ $transaction->kelas_id }}" hidden>
                        @endif

                        @include('components/select2', [
                            'name' => 'Nama Mitra',
                            'value' => 'mitra_id',
                            'options' => $mitras,
                        ])

                        @include('components/input', [
                            'name' => 'Voucher',
                            'value' => 'voucher',
                            'data' => $title !== 'Peserta Add Pelatihan' ? $transaction : null,
                            'disable' =>
                                $title !== 'Peserta Add Pelatihan'
                                    ? ($transaction->redeem_paid
                                        ? true
                                        : false)
                                    : null,
                        ])

                        @include('components/input', [
                            'name' => 'invoice',
                            'value' => 'invoice',
                            'data' => $title !== 'Peserta Add Pelatihan' ? $transaction : null,
                            'disable' =>
                                $title !== 'Peserta Add Pelatihan'
                                    ? ($transaction->redeem_paid
                                        ? true
                                        : false)
                                    : null,
                        ])


                        @if ($title === 'Peserta Edit Redeem/Complete')
                            @include('components/input', [
                                'name' => 'Redeem Code',
                                'value' => 'redeem_code',
                                'data' => $transaction,
                                'disable' => $transaction->redeem_paid ? true : false,
                            ])
                            @include('components/input', [
                                'name' => 'Tanggal Redeem',
                                'value' => 'redeem_at',
                                'data' => $transaction,
                                'disable' => $transaction->redeem_paid ? true : false,
                            ])
                            @include('components/input', [
                                'name' => '100% Pelatihan',
                                'value' => 'finish_at',
                                'data' => $transaction,
                                'disable' => $transaction->finish_paid ? true : false,
                            ])
                        @endif

                        <div class="ms-3 mt-4 pb-3">
                            <button type="submit" class="btn btn-accent btn-sm">Simpan</button>
                            <a href="{{ URL::previous() }}" class="btn btn-neutral btn-sm">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @if ($title !== 'Peserta Add Pelatihan')
        <script>
            $(document).ready(function() {
                const kelas_id = @json($transaction->kelas_id) ?? null;
                const mitra_id = @json($transaction->mitra_id) ?? null;
                const digital_platform_id = @json($transaction->digital_platform_id) ?? null;
                if (kelas_id) {
                    $('#kelas_id').val(kelas_id).trigger('change')
                }

                if (mitra_id) {
                    $('#mitra_id').val(mitra_id).trigger('change')
                }

                if (digital_platform_id) {
                    $('#digital_platform_id').val(digital_platform_id).trigger('change')
                }
            })
        </script>
    @endif

    <script>
        $(document).ready(function() {
            const cb_kelas_id = @json(old('kelas_id')) ?? null
            const cb_peserta_id = @json(old('peserta_id')) ?? null
            const cb_mitra_id = @json(old('mitra_id')) ?? null
            const cb_digital_platform_id = @json(old('digital_platform_id')) ?? null

            $('#kelas_id').select2()
            $('#peserta_id').select2()
            $('#mitra_id').select2()
            $('#digital_platform_id').select2()

            if (cb_kelas_id) {
                $('#kelas_id').val(cb_kelas_id).trigger('change')
            }

            if (cb_peserta_id) {
                $('#peserta_id').val(cb_peserta_id).trigger('change')
            }
            if (cb_mitra_id) {
                $('#mitra_id').val(cb_mitra_id).trigger('change')
            }
            if (cb_digital_platform_id) {
                $('#digital_platform_id').val(cb_digital_platform_id).trigger('change')
            }


            $(document).on('change', '#peserta_id', function() {
                const peserta_id = $(this).val()
                fetch(`/master-data-peserta/${peserta_id}/fetch`, {
                    method: 'GET',
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    }
                }).then(res => {
                    return res.json()
                }).then(d => {
                    $("#name").val(d.data.name);
                    $("#email").val(d.data.email);
                    $("#phone").val(d.data.phone);
                })
            })
        })
    </script>

    @if ($title === 'Peserta Edit Redeem/Complete')
        <script>
            $('#voucher').attr('readonly', true)
            $('#invoice').attr('readonly', true)
            $('#digital_platform_id').attr('disabled', true)
            $('#mitra_id').attr('disabled', true)
        </script>
    @endif

@endpush
