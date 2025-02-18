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
                            <div>
                                {{-- Year Picker --}}
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Pilih Tahun') }}</label>
                                    <select id="year-picker-1" name="year"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 year-picker">
                                    </select>
                                </div>
                                {{-- Year Picker --}}

                                @include('components/select2', [
                                    'name' => 'Cari Berdasarkan Nama Kelas',
                                    'value' => 'class_name',
                                    'options' => $listClassName,
                                ])

                                @include('components/select-kelas-jadwal', [
                                    'data' => $kelas ?? null,
                                ])
                            </div>

                            <div class="ml-2 mt-3">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-edit">Ubah</button>
                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                            </div>



                        </form>
                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Jadwal</th>
                                    <th>Jumlah Hari</th>
                                    <th>Trainer</th>
                                    <th>Status</th>
                                    <th>Waktu Create</th>
                                    <th>Waktu Update</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $page = request('page') ?? 1;
                                    $p = $page > 0 ? ($page - 1) * 20 : 0;
                                @endphp
                                @foreach ($datas as $key => $d)
                                    <tr class="text-black table-row"
                                        data-id="/AplikasiMonitoring/komisi-trainer/kelas-jadwal/{{ $d->id }}">
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->jadwal_name }}</td>
                                        <td>{{ $d->day }}</td>
                                        <td>{{ $d->trainer_names }}</td>
                                        <td>{{ $d->status }}</td>
                                        <td>{{ $d->created_at }}</td>
                                        <td>{{ $d->updated_at }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-3">
                            @if (!$datas->isEmpty())
                                {{ $datas->appends(request()->query())->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let selects = document.getElementsByClassName("year-picker");
            let currentYear = new Date().getFullYear();

            for (let year = currentYear; year >= 1950; year--) {
                let option = new Option(year, year);
                for (let select of selects) {
                    select.appendChild(option.cloneNode(true))
                }
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#class_name').select2()
            $('#kelas_id').select2()
        })
    </script>
@endpush
