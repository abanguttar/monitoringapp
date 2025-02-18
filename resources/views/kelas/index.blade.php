@extends('layouts.main')
@section('body')
    <div class="py-12 ">
        <div class="w-full flex justify-center  sm:px-6 lg:px-8 ">
            <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="w-full mt-5 lg:px-8">
                    <div class="min-h-96 overflow-auto">
                        <form action="">
                            <div>
                                @include('components/input-search', [
                                    'name' => 'Nama Kelas',
                                    'value' => 'name',
                                    'data' => null,
                                ])
                                @include('components/input-search', [
                                    'name' => 'Nama Jadwal',
                                    'value' => 'jadwal_name',
                                    'data' => null,
                                ])
                            </div>

                            @include('components/button-table')


                        </form>
                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Jadwal</th>
                                    <th>Tipe</th>
                                    <th>Jenis</th>
                                    <th>Waktu Pelatihan</th>
                                    <th>Jam</th>
                                    <th>Harga Kelas</th>
                                    <th>Jumlah Hari</th>
                                    <th>User Create</th>
                                    <th>Waktu Create</th>
                                    <th>User Update</th>
                                    <th>Waktu Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $page = request('page') ?? 1;
                                    $p = $page > 0 ? ($page - 1) * 20 : 0;
                                @endphp
                                @foreach ($kelas as $key => $k)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-data-kelas/{{ $k->id }}">
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $k->name }}</td>
                                        <td>{{ $k->jadwal_name }}</td>
                                        <td>{{ $k->is_prakerja === 1 ? 'Prakerja' : 'Umum' }}</td>
                                        <td>{{ $k->metode }}</td>
                                        <td>{{ $k->date }}</td>
                                        <td>{{ $k->jam }}</td>
                                        <td>{{ number_format($k->price) }}</td>
                                        <td>{{ $k->day }}</td>
                                        <td>{{ $k->uc->name }}</td>
                                        <td>{{ $k->created_at }}</td>
                                        <td>{{ $k->uu->name }}</td>
                                        <td>{{ $k->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            @if (!$kelas->isEmpty())
                                {{ $kelas->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
