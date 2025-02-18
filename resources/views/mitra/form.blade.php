@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <form action="" method="POST">
                        @csrf
                        @if ($mitra)
                            @method('PUT')
                        @endif
                        @include('components/input', [
                            'name' => 'Nama Mitra',
                            'value' => 'name',
                            'data' => $mitra ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'No NPWP',
                            'value' => 'npwp',
                            'data' => $mitra ?? null,
                        ])
                        @include('components/text', [
                            'name' => 'Alamat Mitra',
                            'value' => 'address',
                            'data' => $mitra ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'Penanggung Jawab',
                            'value' => 'responsible',
                            'data' => $mitra ?? null,
                        ])

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
