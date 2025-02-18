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
                                    'name' => 'Cari Nama Peserta / Email',
                                    'value' => 'name',
                                    'data' => null,
                                ])
                            </div>

                            <div class="ml-2 mt-3">
                                <a type="button" href="{{ URL::current() . '/create' }}"
                                    class="btn btn-success btn-sm">Tambah Peserta Baru</a>

                                <button type="button" class="btn btn-primary btn-sm" id="btn-edit">Ubah Data Peserta</button>
                                {{-- <button type="button" class="btn btn-error btn-sm" id="btn-delete">Hapus</button> --}}
                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                            </div>

                        </form>
                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>No Hp</th>
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
                                @foreach ($pesertas as $key => $peserta)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/master-data-peserta/{{ $peserta->id }}">
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $peserta->name }}</td>
                                        <td>{{ $peserta->email }}</td>
                                        <td>{{ $peserta->phone }}</td>
                                        <td>{{ $peserta->uc->name }}</td>
                                        <td>{{ $peserta->created_at }}</td>
                                        <td>{{ $peserta->uu->name }}</td>
                                        <td>{{ $peserta->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            @if (!$pesertas->isEmpty())
                                {{ $pesertas->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
