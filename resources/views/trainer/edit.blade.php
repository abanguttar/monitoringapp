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

                        <div id="Normal-scheme" class="mt-10 container-scheme ">

                            @php
                                $is_done = 1;
                            @endphp

                            <h5 class="ms-4 font-bold text-xl text-black">Skema Normal</h5>
                            @foreach ($pivots as $item)
                                @if ($item->day_number >= 1 && $item->trainer_id_1)
                                    @php
                                        echo $item->day_number;
                                        $is_done++;
                                    @endphp
                                @endif

                                <form action="" method="POST" class="mt-5 bg-neutral-100 shadow border mx-5 p-4 ">
                                    <h6 class="text-black font-semibold ms-6 text-lg">Hari {{ $item->day_number }}</h6>
                                    @include('components/scheme-2', [
                                        'obj' => (object) [
                                            'name' => 'trainer_1',
                                            'commission' => 'komisi_1',
                                            'day_number' => "day-$item->day_number",
                                            'total' => $item->commission_1,
                                        ],
                                    ])
                                    @include('components/scheme-2', [
                                        'obj' => (object) [
                                            'name' => 'trainer_2',
                                            'commission' => 'komisi_2',
                                            'day_number' => "day-2-$item->day_number",
                                            'total' => $item->commission_2,
                                        ],
                                    ])
                                    <div class="flex justify-end">
                                        <input type="hidden" name="type" value="normal">
                                        <input type="hidden" name="scheme" value="Normal">
                                        <input type="hidden" name="day" value="{{ $item->day_number }}">
                                        <button type="submit"
                                            class="btn btn-sm btn-error text-white me-5 @if ($is_done < $item->day_number) btn-disabled @endif ">Simpan</button>
                                    </div>
                                </form>
                            @endforeach
                        </div>
                        {{-- @php
                            dd($is_done);
                        @endphp --}}


                        <div id="Minimum-scheme" class="mt-10 container-scheme hidden">
                            <h5 class="ms-4 font-bold text-xl text-black">Skema Minimum</h5>

                            @include('components/scheme', [
                                'obj' => (object) [
                                    'name' => 'trainer',
                                    'commission' => 'komisi',
                                    'type' => 'minimum',
                                    'total' => $transaction_kelas->total,
                                ],
                            ])
                        </div>

                        <div class="ms-3 mt-4 pb-3">
                            {{-- <button type="submit" class="btn btn-accent btn-sm">Simpan</button> --}}
                            <a href="{{ URL::previous() }}" class="btn btn-neutral btn-sm">Kembali</a>
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
        const transaction_kelas = @json($transaction_kelas);
        const trainers = @json($trainers);
        let data_trainers = trainers.map((x) => {
            return {
                'id': `${x.id + '|'+x.name}`,
                'text': x.name,
            }
        })
    </script>


    <script>
        const tk = @json($transaction_kelas);
        const days = tk.day

        function changeVal(index) {
            const vals = @json(old('trainer_1')) ?? null;
            const vals2 = @json(old('trainer_2')) ?? null;
            console.log({
                vals,
                vals2
            });

            if (vals) {
                $(`#day-${index}`).val(vals).trigger("change")
            }
            if (vals2) {
                $(`#day-2-${index}`).val(vals).trigger("change")
            }
        }

        for (let i = 1; i <= days; i++) {
            $(`#day-${i}`).select2({
                data: data_trainers
            })
            $(`#day-2-${i}`).select2({
                data: data_trainers
            })

            changeVal(i)
        }


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
        })
    </script>



    @if ($transaction_kelas->status)
        <script>
            $(document).ready(function() {

                if (transaction_kelas.scheme === 'Minimum') {
                    $('#trainer').select2({
                        data: data_trainers
                    })
                    const value = `${transaction_kelas.trainer_ids+"|"+transaction_kelas.trainer_names}`;
                    console.log({
                        value
                    });

                    $(`#trainer`).val(value).trigger("change")
                }
            })
        </script>
    @else
        <script>
            $(document).ready(function() {

                $('#trainer').select2({
                    data: data_trainers
                })

                const trainer = @json(old('trainer')) ?? null;

                if (trainer) {
                    $(`#trainer`).val(trainer).trigger("change")
                }



            })
        </script>
    @endif
@endpush
