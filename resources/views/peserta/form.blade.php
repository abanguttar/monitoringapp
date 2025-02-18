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
                        @if ($peserta)
                            @method('PUT')
                        @endif
                        @include('components/input', [
                            'name' => 'Nama peserta',
                            'value' => 'name',
                            'data' => $peserta ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'Email peserta',
                            'value' => 'email',
                            'data' => $peserta ?? null,
                        ])

                        @include('components/input', [
                            'name' => 'No Hp',
                            'value' => 'phone',
                            'data' => $peserta ?? null,
                        ])

                        {{-- @include('components/select2', [
                            'name' => 'Digital Platform',
                            'value' => 'digital_platform_id',
                            'options' => $digitalPlatform,
                        ])

                        @include('components/select2', [
                            'name' => 'Nama Mitra',
                            'value' => 'mitra_id',
                            'options' => $mitras,
                        ]) --}}
                        {{-- @if ($title !== 'Peserta Edit')
                            @include('components/select-kelas-jadwal', [
                                'data' => $kelas ?? null,
                            ])
                            @include('components/input', [
                                'name' => 'Voucher',
                                'value' => 'voucher',
                                'data' => $peserta ?? null,
                            ])

                            @include('components/input', [
                                'name' => 'invoice',
                                'value' => 'invoice',
                                'data' => $peserta ?? null,
                            ])
                        @endif --}}

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
