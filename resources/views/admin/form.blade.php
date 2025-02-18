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
                        @if ($admin)
                            @method('PUT')
                        @endif
                        @include('components/input', [
                            'name' => 'Username',
                            'value' => 'username',
                            'data' => $admin ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'Nama',
                            'value' => 'name',
                            'data' => $admin ?? null,
                        ])
                        @include('components/select', [
                            'name' => 'Role',
                            'value' => 'role',
                            'options' => [
                                (object) [
                                    'title' => 'Admin',
                                    'value' => 'admin',
                                ],
                                (object) [
                                    'title' => 'Admin Marketing',
                                    'value' => 'adminmarketing',
                                ],
                            ],
                            'data' => $admin,
                        ])

                        <div class="input-group p-2 mt-2">
                            <label for="password" class="text-black ms-2 font-semibold">{{ ucwords('Password') }}</label>
                            <input type="password" name="password" placeholder="Masukkan Password"
                                class="mt-2 input input-bordered w-full " id="password" value="" />
                            @error('password')
                                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                            @enderror
                        </div>


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
