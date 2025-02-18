@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="w-full flex justify-center  sm:px-6 lg:px-8">
            <div class=" w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="w-full mt-5 lg:px-8">
                    <div class="h-96 overflow-x-auto">
                        <form action="">

                            <div>
                                @include('components/input-search', [
                                    'name' => 'Nama Mitra',
                                    'value' => 'name',
                                    'data' => null,
                                ])
                            </div>

                           @include('components/button-table')
                        </form>
                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Mitra</th>
                                    <th>No NPWP</th>
                                    <th>Alamat</th>
                                    <th>Penanggung Jawab</th>
                                    <th>User Create</th>
                                    <th>Waktu Create</th>
                                    <th>User Update</th>
                                    <th>Waktu Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($mitras as $key => $m)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-mitra/{{ $m->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $m->name }}</td>
                                        <td>{{ $m->npwp }}</td>
                                        <td>{{ $m->address }}</td>
                                        <td>{{ $m->responsible }}</td>
                                        <td>{{ $m->uc->name }}</td>
                                        <td>{{ $m->created_at }}</td>
                                        <td>{{ $m->uu->name }}</td>
                                        <td>{{ $m->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
