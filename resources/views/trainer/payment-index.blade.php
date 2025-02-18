@extends('layouts.main')
@push('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('body')
    <div class="py-12 ">
        <div class="w-full flex justify-center  sm:px-6 lg:px-8 ">
            <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="w-full mt-5 lg:px-8">
                    <div class="min-h-96 overflow-auto">
                        <form action="">
                            <div class="grid grid-cols-3 gap-4">
                                <input type="text" name="search" value="true" hidden>
                                <div>
                                    @include('components/select2', [
                                        'name' => 'Cari Berdasarkan Nama Trainer',
                                        'value' => 'name',
                                        'options' => $trainers,
                                    ])
                                </div>
                            </div>



                            <div class="ml-2 mt-3">

                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                                <a href="/{{ $navbar }}" class="btn btn-neutral bg-black btn-sm">Reset</a>
                            </div>

                        </form>

                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Trainer</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Jadwal</th>
                                    <th>Invoice</th>
                                    <th>Pembayaran</th>
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

                                @foreach ($transactions as $key => $t)
                                    <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-pembayaran/{{ $t->id }}">
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $t->trainer_name }}</td>
                                        <td>{{ $t->peserta_name }}</td>
                                        <td>{{ $t->email }}</td>
                                        <td>{{ $t->nama_kelas }}</td>
                                        <td>{{ $t->jadwal_name }}</td>
                                        <td>{{ $t->invoice }}</td>
                                        <td>{{ '' }}</td>
                                        <td>{{ $t->create_by }}</td>
                                        <td>{{ $t->created_at }}</td>
                                        <td>{{ $t->update_by }}</td>
                                        <td>{{ $t->updated_at }}</td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div class="mt-3">
                            @if (!$transactions->isEmpty())
                                {{ $transactions->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@php
    $params[] = [
        'id' => 'name',
        'value' => request()->get('name'),
    ];
@endphp
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#name').select2()
            const params = @json($params);
            params.forEach(el => {
                $(`#${el.id}`).val(el.value).trigger('change')
            });

            const fullUrl = @json(parse_url(URL::full()));
            $(document).on('click', '#btn-export', function() {
                const tipe = $(this).data('tipe');
                window.location.href = `export?${fullUrl['query']}&tipe=${tipe}`
            })



        })
    </script>
@endpush
