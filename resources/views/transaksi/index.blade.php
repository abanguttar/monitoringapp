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
                            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                                <input type="text" name="search" value="true" hidden>
                                <div>
                                    @include('components/input-search', [
                                        'name' => 'Cari Nama Peserta / Email',
                                        'value' => 'name',
                                        'data' => null,
                                    ])
                                </div>
                                <div>
                                    @include('components/input-search', [
                                        'name' => 'Cari Berdasarkan Voucher',
                                        'value' => 'voucher',
                                        'data' => null,
                                    ])
                                </div>
                                <div>
                                    @include('components/input-search', [
                                        'name' => 'Cari Berdasarkan Invoice',
                                        'value' => 'invoice',
                                        'data' => null,
                                    ])
                                </div>
                                <div>
                                    @include('components/input-search', [
                                        'name' => 'Cari Berdasarkan Kode Redeem',
                                        'value' => 'redeem_code',
                                        'data' => null,
                                    ])
                                </div>
                                @php
                                    $selects = [
                                        (object) [
                                            'title' => 'Digital Platform',
                                            'id' => 'digital_platform_id',
                                            'option' => $digitalPlatforms,
                                        ],
                                        (object) [
                                            'title' => 'Mitra',
                                            'id' => 'mitra_id',
                                            'option' => $mitras,
                                        ],
                                        (object) [
                                            'title' => 'Kelas',
                                            'id' => 'class_name',
                                            'option' => $listClassName,
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

                                @include('components/select-kelas-jadwal', [
                                    'data' => $kelas ?? null,
                                ])
                                @php
                                    $params[] = [
                                        'id' => 'kelas_id',
                                        'value' => request()->get('kelas_id'),
                                    ];
                                @endphp
                                {{-- Print select options --}}

                                @if ($title === 'List Pembayaran')
                                    <div>
                                        @include('components/input-search', [
                                            'name' => 'Cari Berdasarkan Periode Redeem',
                                            'value' => 'redeem_period',
                                            'data' => null,
                                        ])
                                    </div>
                                    <div>
                                        @include('components/input-search', [
                                            'name' => 'Cari Berdasarkan Periode Completion',
                                            'value' => 'finish_period',
                                            'data' => null,
                                        ])
                                    </div>
                                @endif

                            </div>
                            <div class="w-36">
                                <div class="flex flex-col">
                                    <label class="label cursor-pointer">
                                        <input type="checkbox" name="is_finished" value="ya" id="is_finished"
                                            class="checkbox checkbox-primary" />
                                        <span class="label-text font-bold text-black">100% Pelatihan</span>
                                    </label>
                                </div>
                            </div>


                            @if ($title === 'List Pembayaran' && $total->redeem_paid)
                                <div class="container-total mt-5 mb-5 flex justify-center font-bold text-black text-lg">
                                    <ul>
                                        <li>
                                            <h1>Total Pembayaran Redeemtion : Rp. {{ number_format($total->redeem_paid) }}
                                            </h1>
                                        </li>
                                        <li>
                                            <h1>Total Pembayaran Completion: Rp. {{ number_format($total->finish_paid) }}
                                            </h1>
                                        </li>
                                        <li>
                                            <h1>Total Refund Redeemtion: Rp. {{ number_format($total->redeem_refund) }}
                                            </h1>
                                        </li>
                                        <li>
                                            <h1>Total Refund Completion: Rp. {{ number_format($total->finish_refund) }}
                                            </h1>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            <div class="ml-2 mt-3">

                                {{-- Jika list pembayaran tampilkan button import pembayaran dan import refund --}}
                                @if ($title === 'List Pembayaran')
                                    <a type="button" href="{{ URL::current() . '/payment/import' }}"
                                        class="btn btn-success btn-sm">Import Pembayaran</a>
                                    <a type="button" href="{{ URL::current() . '/refund/import' }}"
                                        class="btn btn-success btn-sm">Import Refund</a>
                                    <button type="button" class="btn btn-primary  btn-sm" id="btn-payment">Ubah
                                        Payment</button>
                                    <button type="button" class="btn btn-primary  btn-sm" id="btn-refund">Ubah
                                        Refund</button>
                                    <button type="button" class="btn btn-success btn-outline btn-sm"
                                        data-tipe="{{ $title === 'List Pembayaran' ? 'payment' : 'peserta' }}"
                                        id="btn-export">Export</button>
                                @else
                                    <a type="button" class="btn btn-success btn-sm"
                                        href="{{ URL::current() . '/create' }}">Tambah
                                        Pelatihan</a>
                                    <button type="button" class="btn btn-primary btn-sm" id="btn-edit">Ubah Data
                                        Pelatihan</button>
                                    <button type="button" class="btn btn-primary btn-outline btn-sm"
                                        id="btn-redeem-complete">Ubah Redeem/Complete</button>

                                    <a type="button" href="{{ URL::current() . '/import' }}"
                                        class="btn btn-success btn-sm">Import Peserta Baru</a>
                                    <a type="button" href="{{ URL::current() . '/redemption/import' }}"
                                        class="btn btn-success btn-sm">Import Redeemtion</a>
                                    <a type="button" href="{{ URL::current() . '/completion/import' }}"
                                        class="btn btn-success btn-sm">Import Completion</a>
                                @endif
                                <button type="submit" class="btn btn-info btn-sm">Cari</button>
                                <a href="/{{ $navbar }}" class="btn btn-neutral bg-black btn-sm">Reset</a>
                            </div>

                        </form>

                        <table class="table mt-5">
                            <thead class="bg-black text-white">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Peserta</th>
                                    <th>Email</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Jadwal</th>
                                    <th>Tipe</th>
                                    <th>Jenis</th>
                                    <th>Waktu Pelatihan</th>
                                    <th>Jam</th>
                                    <th>Harga Kelas</th>
                                    <th>Nama Digital Platform</th>
                                    <th>Invoice</th>
                                    <th>Voucher</th>
                                    <th>Nama Mitra</th>
                                    <th>Redeem Code</th>
                                    <th>Tanggal Redeem</th>
                                    <th>100% Pelatihan</th>
                                    @if ($title === 'List Pembayaran')
                                        <th>Bayar Redeem</th>
                                        <th>Periode Redeem</th>
                                        <th>Refund Redeem</th>
                                        <th>Keterangan</th>
                                        <th>Bayar Completion</th>
                                        <th>Periode Completion</th>
                                        <th>Refund Completion</th>
                                        <th>Keterangan</th>
                                    @endif
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

                                @if ($title === 'List Pembayaran')
                                    @foreach ($transactions as $key => $t)
                                        <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-pembayaran/{{ $t->id }}">
                                            <td>{{ $p + ++$key }}</td>
                                            <td>{{ $t->peserta_name }}</td>
                                            <td>{{ $t->email }}</td>
                                            <td>{{ $t->nama_kelas }}</td>
                                            <td>{{ $t->jadwal_name }}</td>
                                            <td>{{ $t->is_prakerja === 1 ? 'Prakerja' : 'Umum' }}</td>
                                            <td>{{ $t->metode }}</td>
                                            <td>{{ $t->date }}</td>
                                            <td>{{ $t->jam }}</td>
                                            <td>{{ number_format($t->price) }}</td>
                                            <td>{{ $t->dp_name }}</td>
                                            <td>{{ $t->invoice }}</td>
                                            <td>{{ $t->voucher }}</td>
                                            <td>{{ $t->mitra_name }}</td>
                                            <td>{{ $t->redeem_code }}</td>
                                            <td>{{ $t->redeem_at }}</td>
                                            <td>{{ $t->finish_at }}</td>
                                            <td>{{ number_format($t->redeem_paid) }}</td>
                                            <td>{{ $t->redeem_period }}</td>
                                            <td>{{ number_format($t->redeem_refund) }}</td>
                                            <td>{{ $t->redeem_note }}</td>
                                            <td>{{ number_format($t->finish_paid) }}</td>
                                            <td>{{ $t->finish_period }}</td>
                                            <td>{{ number_format($t->finish_refund) }}</td>
                                            <td>{{ $t->finish_note }}</td>
                                            <td>{{ $t->create_by }}</td>
                                            <td>{{ $t->created_at }}</td>
                                            <td>{{ $t->update_by }}</td>
                                            <td>{{ $t->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($transactions as $key => $t)
                                        <tr class="text-black table-row" data-id="/AplikasiMonitoring/list-peserta/{{ $t->id }}">
                                            <td>{{ $p + ++$key }}</td>
                                            <td>{{ $t->peserta_name }}</td>
                                            <td>{{ $t->email }}</td>
                                            <td>{{ $t->nama_kelas }}</td>
                                            <td>{{ $t->jadwal_name }}</td>
                                            <td>{{ $t->is_prakerja === 1 ? 'Prakerja' : 'Umum' }}</td>
                                            <td>{{ $t->metode }}</td>
                                            <td>{{ $t->date }}</td>
                                            <td>{{ $t->jam }}</td>
                                            <td>{{ number_format($t->price) }}</td>
                                            <td>{{ $t->dp_name }}</td>
                                            <td>{{ $t->invoice }}</td>
                                            <td>{{ $t->voucher }}</td>
                                            <td>{{ $t->mitra_name }}</td>
                                            <td>{{ $t->redeem_code }}</td>
                                            <td>{{ $t->redeem_at }}</td>
                                            <td>{{ $t->finish_at }}</td>
                                            <td>{{ $t->create_by }}</td>
                                            <td>{{ $t->created_at }}</td>
                                            <td>{{ $t->update_by }}</td>
                                            <td>{{ $t->updated_at }}</td>
                                        </tr>
                                    @endforeach
                                @endif

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
            $('#class_name').select2()
            $('#kelas_id').select2()
            const params = @json($params);
            params.forEach(el => {
                $(`#${el.id}`).val(el.value).trigger('change')
            });
            const is_finished = @json(request()->query('is_finished')) ?? null;
            if (is_finished) {
                $('#is_finished').attr('checked', true);
            }



            const fullUrl = @json(parse_url(URL::full()));

            $(document).on('click', '#btn-export', function() {
                const tipe = $(this).data('tipe');
                window.location.href = `export?${fullUrl['query']}&tipe=${tipe}`
            })



        })
    </script>
@endpush
