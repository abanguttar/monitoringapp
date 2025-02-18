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
                        @method("PUT")

                        @include('components/input', [
                            'name' => 'Username',
                            'value' => 'username',
                            'data' => $admin ?? null,
                            'disabled' => true,
                        ])
                        @php
                            $permissions_length = 0;
                        @endphp
                        <div class="row mt-2">
                            <div class="col-12  mt-3 mb-1"> <span class="fw-bold me-5">Izin Akses</span> <input
                                    type="checkbox" class="me-1 checkbox checkbox-primary" name="all_check" id="all_check">Cek semua opsi</div>
                            @foreach ($permissions as $key => $group)
                                {{-- <h6 class="mt-4">--- {{ ucwords(str_replace('_', ' ', $key)) }} </h6> --}}
                                <div class="grid grid-cols-3 gap-5 p-4">
                                    @foreach ($group as $key => $name)
                                        <div class=" mt-2">
                                            <p style="font-size: 15px" class="mb-1">
                                                {{ ucwords(str_replace('_', ' ', $key)) }} </p>
                                            @foreach ($name as $key => $access)
                                                <div class="form-check">
                                                    <input class="checkbox checkbox-primary check-access" type="checkbox"
                                                        name="access[]" value="{{ $access->id }}"
                                                        id="{{ $access->id }}">
                                                    <label class="form-check-label" style="font-size: 14px"
                                                        for="{{ $access->id }}">
                                                        {{ ucwords($access->name) }}
                                                    </label>
                                                </div>
                                                @php
                                                    $permissions_length++;
                                                @endphp
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
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

@push('script')
    <script>
        const permissions_length = @json($permissions_length);
        const user_permissions = @json($user_permissions);
        user_permissions.forEach(el => {
            $(`#${el}`).attr('checked', true)
        });



        if (permissions_length === user_permissions.length) {
            $('#all_check').prop('checked', true)
        }

        $(document).on('change', '#all_check', function() {
            const iSChecked = $(this).prop('checked')
            if (iSChecked) {
                $('.check-access').prop('checked', true)
            } else {
                $('.check-access').prop('checked', false)
            }
        })
    </script>
@endpush
