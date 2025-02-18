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
                            <div class="grid grid-cols-2 gap-4">
                                <input type="text" name="search" value="true" hidden>

                                @php
                                    $selects = [
                                        (object) [
                                            'title' => 'Mitra',
                                            'id' => 'mitra_id',
                                            'option' => $mitras,
                                        ],
                                    ];

                                    $params = [];
                                @endphp

                                {{-- Print select options --}}
                                @foreach ($selects as $item)
                                    <div>
                                        @include('components/select2', [
                                            'name' => 'Cari Berdasarkan ' . $item->title,
                                            'value' => $item->id,
                                            'options' => $item->option,
                                        ])
                                    </div>

                                    @php
                                        $params[] = [
                                            'id' => $item->id,
                                            'value' => request()->get($item->id),
                                        ];
                                    @endphp
                                @endforeach
                                {{-- Print select options --}}
                                <div>
                                    @include('components/input-search', [
                                        'name' => 'Cari Berdasarkan Periode',
                                        'value' => 'period',
                                        'data' => null,
                                    ])
                                </div>
                            </div>

                            <div class="ml-2 mt-3">
                                {{-- <button type="button" class="btn btn-error btn-sm" id="btn-delete">Hapus</button> --}}
                                <button type="button" class="btn btn-success btn-outline btn-sm"
                                    id="btn-export">Export</button>
                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                                <a href="/{{ $navbar }}" class="btn btn-neutral bg-black btn-sm">Reset</a>
                            </div>

                        </form>

                        @if (!$transactions->isEmpty())
                            <div class="mt-10">
                                @include('components/input-search', [
                                    'name' => 'Komisi (%)',
                                    'value' => 'commission',
                                    'data' => null,
                                ])

                            </div>
                            <div class="flex items-end">
                                <button type="button" class="btn btn-accent btn-sm ml-2"
                                    id="btn-save-commission">Simpan</button>
                            </div>


                            <div class="container-total flex justify-center font-bold text-black text-xl">
                                <ul>
                                    <li>
                                        <h1>Total Harga Kelas : Rp. {{ number_format($total_kelas) }}</h1>
                                    </li>
                                    <li>
                                        <h1>Total Komisi Mitra : Rp. {{ number_format($total_commission) }} </h1>
                                    </li>
                                    {{-- Refund redeem dihilangkan, refund Keterangan --}}
                                    {{-- Hitung total refund completion --}}
                                    <li>
                                        <h1>Total Refund : Rp. {{ number_format($total_refund) }} </h1>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    {{-- <th><input type="checkbox" class="checkbox checkbox-primary" id="check-all" /></th> --}}
                                    <th>#</th>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>No Hp</th>
                                    <th>Nama Mitra</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Jadwal</th>
                                    <th>Tipe</th>
                                    <th>Jenis</th>
                                    <th>Waktu Pelatihan</th>
                                    <th>Jam</th>
                                    <th>Harga Kelas</th>
                                    <th>Invoice</th>
                                    <th>Voucher</th>
                                    {{-- <th>Redeem Code</th> --}}
                                    {{-- <th>Tanggal Redeem</th> --}}
                                    {{-- <th>100% Pelatihan</th> --}}
                                    <th>Periode Redeem</th>
                                    <th>Periode Completion</th>
                                    {{-- <th>Refund Redeem</th>
                                    <th>Keterangan</th> --}}
                                    <th>Refund Completion</th>
                                    <th>Keterangan</th>
                                    <th>Persentase Komisi (%)</th>
                                    <th>Nilai Komisi</th>
                                    {{-- <th>User Create</th>
                                    <th>Waktu Create</th>
                                    <th>User Update</th>
                                    <th>Waktu Update</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $page = request('page') ?? 1;
                                    $p = $page > 0 ? ($page - 1) * 50 : 0;
                                @endphp

                                @foreach ($transactions as $key => $t)
                                    <tr class="text-black table-checkbox" data-id="{{ $t->id }}">
                                        <td><input type="checkbox" class="checkbox checkbox-child checkbox-primary"
                                                @if ($t->commission_percentage) disabled @endif /></td>
                                        <td>{{ $p + ++$key }}</td>
                                        <td>{{ $t->peserta_name }}</td>
                                        <td>{{ $t->email }}</td>
                                        <td>{{ $t->phone }}</td>
                                        <td>{{ $t->mitra_name }}</td>
                                        <td>{{ $t->nama_kelas }}</td>
                                        <td>{{ $t->jadwal_name }}</td>
                                        <td>{{ $t->is_prakerja === 1 ? 'Prakerja' : 'Umum' }}</td>
                                        <td>{{ $t->metode }}</td>
                                        <td>{{ $t->date }}</td>
                                        <td>{{ $t->jam }}</td>
                                        <td>{{ number_format($t->price) }}</td>
                                        <td>{{ $t->invoice }}</td>
                                        <td>{{ $t->voucher }}</td>
                                        <td>{{ $t->redeem_period }}</td>
                                        <td>{{ $t->finish_period }}</td>
                                        {{-- <td>{{ number_format($t->redeem_refund) }}</td>
                                        <td>{{ $t->redeem_note }}</td> --}}
                                        <td>{{ number_format($t->finish_refund) }}</td>
                                        <td>{{ $t->finish_note }}</td>
                                        {{-- <td>{{ $t->redeem_code }}</td> --}}
                                        {{-- <td>{{ $t->redeem_at }}</td>
                                        <td>{{ $t->finish_at }}</td> --}}
                                        <td>{{ number_format($t->commission_percentage) . '%' }}</td>
                                        <td>{{ number_format($t->commission_value) }}</td>
                                        {{-- <td>{{ $t->create_by }}</td>
                                        <td>{{ $t->created_at }}</td>
                                        <td>{{ $t->update_by }}</td>
                                        <td>{{ $t->updated_at }}</td> --}}
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
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            const params = @json($params);
            params.forEach(el => {
                $(`#${el.id}`).val(el.value).trigger('change')
            });

            $(document).on('click', '.checkbox-child', function() {
                const isChecked = $(this).is(":checked")
                const $row = $(this).closest("tr");

                if (isChecked) {
                    $row.addClass('bg-blue-300')
                } else {
                    $row.removeClass('bg-blue-300')
                }
            })

            $(document).on('click', '#check-all', function() {
                const isChecked = $(this).is(":checked")
                if (isChecked) {
                    $('.table-checkbox').addClass("bg-blue-300")
                    $('.checkbox-child').prop('checked', true)
                } else {
                    $('.table-checkbox').removeClass("bg-blue-300")
                    $('.checkbox-child').prop('checked', false)
                }
            })


            $(document).on('click', '#btn-save-commission', function() {
                let id = [];
                const commission = $('#commission').val()
                $('.bg-blue-300').each(function() {
                    id.push($(this).data("id"))
                })

                fetch('/AplikasiMonitoring/list-komisi-mitra/update', {
                    method: 'POST',
                    headers: {
                        'content-type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({
                        ids: id,
                        commission: commission
                    })
                }).then(res => {
                    if (res.status === 400) {
                        return res.json().then(e => {
                            throw e
                        })
                    }
                    return res.json();
                }).then(d => {

                    location.reload()
                }).catch(e => {
                    const commission_error = e.errors.commission && e.errors.commission[0]
                    const ids_error = e.errors.ids && e.errors.ids[0]
                    let errors = [
                        ids_error,
                        commission_error,
                    ]
                    const message = errors.filter(x => {
                        return x;
                    }).join(', ')

                    toastr.error(message);

                })

            })



            const fullUrl = @json(parse_url(URL::full()));

            $(document).on('click', '#btn-export', function() {
                window.location.href = `export-komisi?${fullUrl['query']}`
            })


        })
    </script>
@endpush
