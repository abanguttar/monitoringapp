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
                        @method('PUT')

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

                        @include('components/input', [
                            'name' => 'Digital Platform',
                            'value' => 'name',
                            'data' => $transaction->dp ?? null,
                            'disable' => true,
                        ])

                        @include('components/input', [
                            'name' => 'Mitra',
                            'value' => 'name',
                            'data' => $transaction->mitra ?? null,
                            'disable' => true,
                        ])

                        @include('components/input', [
                            'name' => 'Voucher',
                            'value' => 'voucher',
                            'data' => $transaction,
                            'disable' => true,
                        ])

                        @include('components/input', [
                            'name' => 'invoice',
                            'value' => 'invoice',
                            'data' => $transaction,
                            'disable' => true,
                        ])


                        @include('components/input', [
                            'name' => 'Redeem Code',
                            'value' => 'redeem_code',
                            'data' => $transaction,
                            'disable' => true,
                        ])
                        @include('components/input', [
                            'name' => 'Tanggal Redeem',
                            'value' => 'redeem_at',
                            'data' => $transaction,
                            'disable' => true,
                        ])
                        @include('components/input', [
                            'name' => '100% Pelatihan',
                            'value' => 'finish_at',
                            'data' => $transaction,
                            'disable' => true,
                        ])



                        @include('components/input', [
                            'name' => 'Periode Redeem',
                            'value' => 'redeem_period',
                            'data' => $transaction,
                            'disable' => $title === 'Peserta Ubah Refund' ? true : null,
                        ])
                        <div class="w-36 ms-2">
                            <div class="flex flex-col">
                                <label class="label cursor-pointer">
                                    <input type="checkbox" name="check_redeem_period" value="ya"
                                        id="check_redeem_period" class="checkbox checkbox-primary"
                                        {{ $transaction->redeem_period ? 'checked' : '' }} />
                                    <span class="label-text font-bold text-black">Bayar Redeem</span>
                                </label>
                            </div>
                        </div>

                        @include('components/input', [
                            'name' => 'Periode Completion',
                            'value' => 'finish_period',
                            'data' => $transaction,
                            'disable' => $title === 'Peserta Ubah Refund' ? true : null,
                        ])
                        <div class="w-40 ms-2">
                            <div class="flex flex-col">
                                <label class="label cursor-pointer">
                                    <input type="checkbox" name="check_finish_period" value="ya"
                                        id="check_finish_period" class="checkbox checkbox-primary"
                                        {{ $transaction->finish_period ? 'checked' : '' }} />
                                    <span class="label-text font-bold text-black">Bayar Completion</span>
                                </label>
                            </div>
                        </div>

                        @if ($title === 'Peserta Ubah Refund')
                            @include('components/text', [
                                'name' => 'Keterangan Refund Redeem',
                                'value' => 'redeem_note',
                                'data' => $transaction,
                            ])
                            <div class="w-36 ms-2">
                                <div class="flex flex-col">
                                    <label class="label cursor-pointer">
                                        <input type="checkbox" name="check_redeem_note" value="ya"
                                            id="check_redeem_note" class="checkbox checkbox-primary"
                                            {{ $transaction->redeem_note ? 'checked' : '' }} />
                                        <span class="label-text font-bold text-black">Refund Redeem</span>
                                    </label>
                                </div>
                            </div>
                            @include('components/text', [
                                'name' => 'Keterangan Refund Completion',
                                'value' => 'finish_note',
                                'data' => $transaction,
                            ])
                            <div class="w-40 ms-2">
                                <div class="flex flex-col">
                                    <label class="label cursor-pointer">
                                        <input type="checkbox" name="check_finish_note" value="ya"
                                            id="check_finish_note" class="checkbox checkbox-primary"
                                            {{ $transaction->finish_note ? 'checked' : '' }} />
                                        <span class="label-text font-bold text-black">Refund Completion</span>
                                    </label>
                                </div>
                            </div>
                        @endif



                        <div class="ms-3 mt-4 pb-3">
                            <button type="submit" class="btn btn-accent btn-sm">Simpan</button>
                            <a href="/list-pembayaran" class="btn btn-neutral btn-sm">Kembali</a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const cb_check_rp = @json(old('check_redeem_period')) ?? null
            if (cb_check_rp) {
                $('#check_redeem_period').prop('checked', true)
            }
            const cb_check_cp = @json(old('check_finish_period')) ?? null
            if (cb_check_cp) {
                $('#check_finish_period').prop('checked', true)
            }
            const cb_check_rr = @json(old('check_redeem_note')) ?? null
            if (cb_check_rr) {
                $('#check_redeem_note').prop('checked', true)
            }
            const cb_check_cr = @json(old('check_finish_note')) ?? null
            if (cb_check_cr) {
                $('#check_finish_note').prop('checked', true)
            }
        })
    </script>
@endpush
