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
                                    'name' => 'Nama Digital Platform',
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
                                    <th>Nama Digital Platform</th>
                                    <th>User Create</th>
                                    <th>Waktu Create</th>
                                    <th>User Update</th>
                                    <th>Waktu Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dps as $key => $dp)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-digital-platform/{{ $dp->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $dp->name }}</td>
                                        <td>{{ $dp->uc->name }}</td>
                                        <td>{{ $dp->created_at }}</td>
                                        <td>{{ $dp->uu->name }}</td>
                                        <td>{{ $dp->updated_at }}</td>
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
