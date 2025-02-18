@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="w-full flex justify-center  sm:px-6 lg:px-8">
            <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="w-full mt-5 lg:px-8">
                    <div class="h-96 overflow-x-auto">
                        <form action="">

                            <div>
                                @include('components/input-search', [
                                    'name' => 'Nama Trainer',
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
                                    <th>Nama Trainer</th>
                                    <th>No NPWP</th>
                                    <th>Status Tanggungan Pajak</th>
                                    <th>User Create</th>
                                    <th>Waktu Create</th>
                                    <th>User Update</th>
                                    <th>Waktu Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainers as $key => $trainer)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/master-data-trainer/{{ $trainer->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $trainer->name }}</td>
                                        <td>{{ $trainer->npwp }}</td>
                                        <td>{{ $trainer->status_tanggungan }}</td>
                                        <td>{{ $trainer->uc->name }}</td>
                                        <td>{{ $trainer->created_at }}</td>
                                        <td>{{ $trainer->uu->name }}</td>
                                        <td>{{ $trainer->updated_at }}</td>
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
