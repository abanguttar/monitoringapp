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
                                    'name' => 'Cari Username / Nama',
                                    'value' => 'name',
                                    'data' => null,
                                ])
                            </div>

                            <div class="ml-2 mt-3">
                                <a type="button" href="{{ URL::current() . '/create' }}"
                                    class="btn btn-success btn-sm">Tambah User Baru</a>
                                <button type="button" class="btn btn-primary btn-sm" id="btn-edit">Ubah Data User</button>
                                <button type="button" class="btn btn-primary btn-outline btn-sm" id="btn-access">Akses User</button>
                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                            </div>

                        </form>
                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Username</th>
                                    <th>Nama</th>
                                    <th>Role</th>
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
                                @foreach ($users as $key => $user)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-user/{{ $user->id }}">
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $user->username }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->role }}</td>
                                        <td>{{ $user->uc->name }}</td>
                                        <td>{{ $user->created_at }}</td>
                                        <td>{{ $user->uu->name }}</td>
                                        <td>{{ $user->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            @if (!$users->isEmpty())
                                {{ $users->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
