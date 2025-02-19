@extends('layouts.main')

@push('link')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@push('style')
    <style>
        .select2-container {
            min-width: 600px !important;
        }
    </style>
@endpush
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <div>

                        @include('components/select', [
                            'name' => 'Skema Komisi',
                            'value' => 'scheme',
                            'options' => [
                                (object) [
                                    'title' => 'Normal',
                                    'value' => 'Normal',
                                ],
                                (object) [
                                    'title' => 'Minimum',
                                    'value' => 'Minimum',
                                ],
                            ],
                            'data' => $transaction_kelas ?? null,
                            'disable' => !$transaction_kelas->status ? false : true,
                        ])

                        <div id="Normal-scheme" class="mt-10 container-scheme hidden">

                            @php
                                $is_done = 1;
                                $pivots_array = [];
                                // $pivots_array_2 = [];
                            @endphp

                            <h5 class="ms-4 font-bold text-xl text-black">Skema Normal</h5>
                            @foreach ($pivots as $item)
                                @if ($item->day_number >= 1 && $item->trainer_id_1)
                                    @php
                                        // echo $item->day_number;
                                        $is_done++;
                                    @endphp
                                @endif
                                @php
                                    if ($item->trainer_id_1) {
                                        $pivots_array[$item->day_number][] =
                                            $item->trainer_id_1 . '|' . $item->trainer_name_1;
                                    }
                                    if ($item->trainer_id_2) {
                                        $pivots_array[$item->day_number][] =
                                            $item->trainer_id_2 . '|' . $item->trainer_name_2;
                                    }
                                @endphp

                                <form action="" method="POST" class="mt-5 bg-neutral-100 shadow border mx-5 p-4 ">
                                    <h6 class="text-black font-semibold ms-6 text-lg">Hari {{ $item->day_number }}</h6>
                                    @include('components/scheme-2', [
                                        'obj' => (object) [
                                            'name' => 'trainer_1',
                                            'commission' => 'komisi_1',
                                            'day_number' => "day-$item->day_number",
                                            'day' => "$item->day_number",
                                            'total' => $item->commission_1,
                                        ],
                                    ])
                                    @include('components/scheme-2', [
                                        'obj' => (object) [
                                            'name' => 'trainer_2',
                                            'commission' => 'komisi_2',
                                            'day_number' => "day-2-$item->day_number",
                                            'day' => "$item->day_number",
                                            'total' => $item->commission_2,
                                        ],
                                    ])
                                    @if ($title !== 'Edit Payment Kelas & Jadwal')
                                        <div class="flex justify-end">
                                            <input type="hidden" name="type" value="normal">
                                            <input type="hidden" name="scheme" value="Normal">
                                            <input type="hidden" name="day" value="{{ $item->day_number }}">
                                            <button type="submit"
                                                class="btn btn-sm btn-error text-white me-5 @if ($is_done < $item->day_number) btn-disabled @endif ">Simpan</button>
                                        </div>
                                    @endif
                                </form>
                            @endforeach
                        </div>
                        {{-- @php
                            dd($is_done);
                        @endphp --}}


                        <div id="Minimum-scheme" class="mt-10 container-scheme hidden">
                            <h5 class="ms-4 font-bold text-xl text-black">Skema Minimum</h5>
                            <form action="" method="POST" class="mt-5 bg-neutral-100 shadow border mx-5 p-4 ">
                                @include('components/scheme-2', [
                                    'obj' => (object) [
                                        'name' => 'trainer_1',
                                        'commission' => 'komisi_1',
                                        'day_number' => 'day-1-min',
                                        'day' => '1',
                                        'total' => null,
                                    ],
                                ])
                                @include('components/scheme-2', [
                                    'obj' => (object) [
                                        'name' => 'trainer_2',
                                        'commission' => 'komisi_2',
                                        'day_number' => 'day-2-1-min',
                                        'day' => '1',
                                        'total' => null,
                                    ],
                                ])

                                <div class="flex justify-end">
                                    <input type="hidden" name="type" value="minimum">
                                    <input type="hidden" name="scheme" value="Minimum">
                                    <input type="hidden" name="day" value="1">
                                    <button type="submit"
                                        class="btn btn-sm btn-error text-white me-5 @if ($transaction_kelas->status === 'done') btn-disabled @endif ">Simpan</button>
                                </div>
                            </form>
                        </div>
                        <form action="" method="POST">
                            @csrf
                            @if ($title === 'Edit Payment Kelas & Jadwal')
                                <div class="container p-4">
                                    <h6 class="font-bold text-lg text-black text-center mt-5">Skema Komisi:
                                        {{ $transaction_kelas->scheme }}
                                    </h6>
                                    <h6 class="font-bold text-lg text-black text-center">Jumlah Hari:
                                        {{ $transaction_kelas->day }}
                                    </h6>
                                    <h6 class="font-bold text-lg text-black text-center">Total Komisi:
                                        {{ number_format($transaction_kelas->total) }}
                                    </h6>
                                    @include('components/select', [
                                        'name' => 'Status Pembayaran',
                                        'value' => 'status',
                                        'options' => [
                                            (object) [
                                                'title' => 'Pending',
                                                'value' => 'pending',
                                            ],
                                            (object) [
                                                'title' => 'Done',
                                                'value' => 'Done',
                                            ],
                                        ],
                                        'data' => $transaction_kelas ?? null,
                                        'disable' => $transaction_kelas->status ? false : true,
                                    ])
                                </div>
                            @endif

                            <div class="ms-3 mt-10 pb-3">
                                @if ($title === 'Edit Payment Kelas & Jadwal')
                                    <button type="submit" class="btn btn-accent btn-sm">Simpan</button>
                                @endif
                                <a href="{{ URL::previous() }}" class="btn btn-neutral btn-sm">Kembali</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const transaction_kelas = @json($transaction_kelas);
        const trainers = @json($trainers);
        let data_trainers = trainers.map((x) => {
            return {
                'id': `${x.id + '|'+x.name}`,
                'text': x.name,
            }
        })
    </script>

    @if ($transaction_kelas->status && $transaction_kelas->scheme === 'Normal')
        @for ($i = 1; $i <= $transaction_kelas->day; $i++)
            <script>
                $(document).ready(function() {
                    let i = @json($i);
                    const cb_vals = @json(old('trainer_1.' . $i)) ?? null;
                    const cb_vals2 = @json(old('trainer_2.' . $i)) ?? null;

                    $(`#day-${i}`).select2({
                        data: data_trainers
                    })
                    $(`#day-2-${i}`).select2({
                        data: data_trainers
                    })
                    if (cb_vals) {
                        $(`#day-${i}`).val(cb_vals).trigger("change")
                    }
                    if (cb_vals2) {
                        $(`#day-2-${i}`).val(cb_vals).trigger("change")
                    }
                })
            </script>
            @if (isset($pivots_array[$i]))
                <script>
                    $(document).ready(function() {
                        let i = @json($i);
                        const array = @json($pivots_array[$i]) ?? null;
                        let vals = array[0] ?? null;
                        let vals_2 = array[1] ?? null;
                        if (vals) {
                            $(`#day-${i}`).val(vals).trigger("change")
                        }
                        if (vals_2) {
                            $(`#day-2-${i}`).val(vals_2).trigger("change")
                        }
                    })
                </script>
            @endif
        @endfor
    @else
        @for ($i = 1; $i <= 1; $i++)
            <script>
                $(document).ready(function() {
                    let i = @json($i);
                    const cb_vals = @json(old('trainer_1.' . $i)) ?? null;
                    const cb_vals2 = @json(old('trainer_2.' . $i)) ?? null;

                    $(`#day-${i}-min`).select2({
                        data: data_trainers
                    })
                    $(`#day-2-${i}-min`).select2({
                        data: data_trainers
                    })
                    if (cb_vals) {
                        $(`#day-${i}-min`).val(cb_vals).trigger("change")
                    }
                    if (cb_vals2) {
                        $(`#day-2-${i}-min`).val(cb_vals).trigger("change")
                    }

                })
            </script>
            @if (isset($pivots_array[$i]))
                <script>
                    $(document).ready(function() {
                        let i = @json($i);
                        let pivot = @json($pivots[0]);
                        const array = @json($pivots_array[$i]) ?? null;
                        let vals = array[0] ?? null;
                        let vals_2 = array[1] ?? null;
                        console.log({
                            pivot
                        });
                        if (vals) {
                            $(`#day-${i}-min`).val(vals).trigger("change")
                            $(`input[name='komisi_1[${i}]']`).val(pivot.commission_1 ?? 0)
                        }
                        if (vals_2) {
                            $(`#day-2-${i}-min`).val(vals_2).trigger("change")
                            $(`input[name='komisi_2[${i}]']`).val(pivot.commission_2 ?? 0)
                        }
                    })
                </script>
            @endif
        @endfor
    @endif






    @if ($transaction_kelas->status)
        <script>
            $(document).ready(function() {

                // if (transaction_kelas.scheme === 'Minimum') {
                //     $('#trainer').select2({
                //         data: data_trainers
                //     })
                //     const value = `${transaction_kelas.trainer_ids+"|"+transaction_kelas.trainer_names}`;

                //     console.log({
                //         value,
                //     });

                //     $(`#day-1-min`).select2({
                //         data: data_trainers
                //     })

                //     $(`#day-2-1-min`).select2({
                //         data: data_trainers
                //     })

                // }
                const scheme = @json($transaction_kelas->scheme);
                $('#scheme').val(scheme)
                $('.container-scheme').addClass('hidden')
                $(`#${scheme}-scheme`).removeClass('hidden');
            })
        </script>
    @else
        <script>
            $(document).ready(function() {

                $(document).on('change', '#scheme', function() {
                    $('.container-scheme').addClass('hidden')
                    $(`#${$(this).val()}-scheme`).removeClass('hidden');
                })
                const scheme = @json(old('scheme'));
                if (scheme) {
                    $('#scheme').val(scheme)
                    $('.container-scheme').addClass('hidden')
                    $(`#${scheme}-scheme`).removeClass('hidden');
                }

                $(`#day-1-min`).select2({
                    data: data_trainers
                })

                $(`#day-2-1-min`).select2({
                    data: data_trainers
                })

                // const trainer = @json(old('trainer')) ?? null;

                // if (trainer) {
                //     $(`#trainer`).val(trainer).trigger("change")
                // }

            })
        </script>
    @endif
@endpush
