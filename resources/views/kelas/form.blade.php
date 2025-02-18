@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <form action="" method="POST">
                        @csrf
                        @if ($kelas)
                            @method('PUT')
                        @endif
                        @include('components/input', [
                            'name' => 'Nama Kelas',
                            'value' => 'name',
                            'data' => $kelas ?? null,
                        ])

                        @include('components/input', [
                            'name' => 'Nama Jadwal',
                            'value' => 'jadwal_name',
                            'data' => $kelas ?? null,
                        ])


                        @include('components/select', [
                            'name' => 'Tipe Kelas',
                            'value' => 'is_prakerja',
                            'options' => [
                                (object) [
                                    'title' => 'Umum',
                                    'value' => '0',
                                ],
                                (object) [
                                    'title' => 'Prakerja',
                                    'value' => '1',
                                ],
                            ],
                            'data' => $kelas ?? null,
                        ])
                        @include('components/select', [
                            'name' => 'Jenis Kelas',
                            'value' => 'metode',
                            'options' => [
                                (object) [
                                    'title' => 'Luring',
                                    'value' => 'Luring',
                                ],
                                (object) [
                                    'title' => 'Webinar',
                                    'value' => 'Webinar',
                                ],
                                (object) [
                                    'title' => 'SPL',
                                    'value' => 'SPL',
                                ],
                                (object) [
                                    'title' => 'Video Learning',
                                    'value' => 'Video Learning',
                                ],
                            ],
                            'data' => $kelas ?? null,
                        ])

                        @include('components/input', [
                            'name' => 'Waktu Pelatihan',
                            'value' => 'date',
                            'data' => $kelas ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'Jam Pelatihan',
                            'value' => 'jam',
                            'data' => $kelas ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'Jumlah Hari',
                            'value' => 'day',
                            'data' => $kelas ?? null,
                        ])

                        @include('components/input', [
                            'name' => 'Harga Kelas',
                            'value' => 'price',
                            'data' => $kelas ?? null,
                        ])
                        {{-- @include('components/select2', [
                            'name' => 'Cari Berdasarkan Nama Trainer',
                            'value' => 'trainer_id',
                            'options' => $trainers,
                        ]) --}}

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
