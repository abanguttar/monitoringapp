@extends('layouts.main')
@push('link')
    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush
@section('body')
    <div class="py-12 ">
        <div class="w-full flex justify-center  sm:px-6 lg:px-8 ">
            <div class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="w-full mt-5 lg:px-8">
                    <div class="min-h-96 overflow-auto">
                        <section class="col-11 col-md-9 mb-5 ">
                            <h1 class="text-center text-black text-2xl font-bold">Grafik Pembelian dan Penyelesaian Kelas
                            </h1>
                            <div class="row justify-content-center gap-2">
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Cari Berdasarkan Nama Pelatihan') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="name_1"
                                        id="name_1">
                                        <option value="">--- Pilih Nama Kelas---</option>
                                        @foreach ($listClassName as $kel)
                                            <option value="{{ $kel->name }}">
                                                {{ $kel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="kelas_id_1"
                                        id="kelas_id_1">
                                        <option value="">--- Pilih Nama Kelas dan Jadwal ---</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->name . ' - ' . $k->jadwal_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Year Picker --}}
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Pilih Tahun') }}</label>
                                    <select id="year-picker-1"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 year-picker">
                                    </select>
                                </div>
                                {{-- Year Picker --}}

                                <div class="ml-2 mt-3">
                                    <button class="btn btn-sm btn-info search" data-id="pembelian-penyelesaian"
                                        data-number="1">Cari</button>
                                    <button class="btn btn-sm btn-dark reset"
                                        data-id="pembelian-penyelesaian">Reset</button>
                                </div>
                            </div>

                            <div id="container-pembelian-penyelesaian"
                                class="mt-5 border flex flex-col justify-center items-center">
                                <canvas id="pembelian-penyelesaian" style="max-height: 20rem"></canvas>
                                <p class="text-center text-black" id="p-pembelian-penyelesaian">Cari data lebih dulu</p>

                                {{-- <button type="button" class="btn btn-error text-white mt-5"
                                    id="show-pembelian-penyelesaian">Tampilkan Data</button> --}}
                            </div>
                        </section>


                        {{-- Grafik Penjualan Kelas --}}
                        <section class="col-11 col-md-9 mt-5 mb-5 pt-5">
                            <h4 class="text-center text-black text-2xl font-bold">Grafik Penjualan Kelas</h4>
                            <div class="row justify-content-center gap-2">
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Cari Berdasarkan Nama Pelatihan') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="name_2"
                                        id="name_2">
                                        <option value="">--- Pilih Nama Kelas---</option>
                                        @foreach ($listClassName as $kel)
                                            <option value="{{ $kel->name }}">
                                                {{ $kel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="kelas_id_2"
                                        id="kelas_id_2">
                                        <option value="">--- Pilih Nama Kelas dan Jadwal ---</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->name . ' - ' . $k->jadwal_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Year Picker --}}
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Pilih Tahun') }}</label>
                                    <select id="year-picker-2"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 year-picker">
                                    </select>
                                </div>
                                {{-- Year Picker --}}


                                <div class="ml-2 mt-3">
                                    <button class="btn btn-sm btn-info search" data-id="penjualan-kelas"
                                        data-number="2">Cari</button>
                                    <button class="btn btn-sm btn-dark reset" data-id="penjualan-kelas">Reset</button>
                                </div>
                            </div>

                            <div id="container-penjualan-kelas"
                                class="mt-5 border w-75 d-flex flex-column align-items-center">
                                <canvas id="penjualan-kelas"></canvas>
                                <p class="text-center text-black" id="p-penjualan-kelas">Cari data lebih dulu</p>
                                {{-- <button type="button" class="btn btn-error text-white mt-5"
                                    id="show-penjualan-kelas">Tampilkan Data</button> --}}

                            </div>
                        </section>
                        {{-- Grafik Penjualan Kelas --}}


                        {{-- Grafik Penjualan Mitra --}}

                        <section class="col-11 col-md-9 mt-5 pt-5">
                            <h4 class="text-center text-black text-2xl font-bold">Grafik Mitra</h4>
                            <div class="row justify-content-center gap-2">
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Cari Berdasarkan Nama Pelatihan') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="name_3"
                                        id="name_3">
                                        <option value="">--- Pilih Nama Kelas---</option>
                                        @foreach ($listClassName as $kel)
                                            <option value="{{ $kel->name }}">
                                                {{ $kel->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="input-group p-2 mt-2">
                                    <label for="kelas_id"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
                                    <select class="select mt-2 select-bordered w-full bg-white" name="kelas_id_3"
                                        id="kelas_id_3">
                                        <option value="">--- Pilih Nama Kelas dan Jadwal ---</option>
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id }}">
                                                {{ $k->name . ' - ' . $k->jadwal_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('kelas_id')
                                        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="">
                                    @include('components/select2', [
                                        'name' => 'Cari Berdasarkan Periode Reedemtion',
                                        'value' => 'redeem_period',
                                        'options' => $redeem_periodes,
                                    ])
                                </div>
                                {{-- <div class="">
                                    @include('components/select2', [
                                        'name' => 'Cari Berdasarkan Periode Completion',
                                        'value' => 'finish_period',
                                        'options' => $periodes,
                                    ])
                                </div> --}}
                                  {{-- Year Picker --}}
                                  <div class="input-group p-2 mt-2">
                                    <label for="year-picker-3"
                                        class="text-black ms-2 font-semibold">{{ ucwords('Pilih Tahun') }}</label>
                                    <select id="year-picker-3"
                                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 year-picker">
                                    </select>
                                </div>
                                {{-- Year Picker --}}


                                <div class="ml-2 mt-3">
                                    <button class="btn btn-sm btn-info search" data-id="penjualan-mitra"
                                        data-number="3">Cari</button>
                                    <button class="btn btn-sm btn-dark reset" data-id="penjualan-mitra">Reset</button>
                                </div>
                            </div>

                            <div id="container-penjualan-mitra"
                                class="mt-5 border w-75 d-flex flex-column align-items-center">
                                <canvas id="penjualan-mitra"></canvas>
                                <p class="text-center text-black" id="p-penjualan-mitra">Cari data lebih dulu</p>
                            </div>
                        </section>
                        {{-- Grafik Penjualan Mitra --}}



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            $('#name_1').select2()
            $('#name_2').select2()
            $('#name_3').select2()
            $('#kelas_id_1').select2()
            $('#kelas_id_2').select2()
            $('#kelas_id_3').select2()
            $('#year-picker-1').select2()
            $('#year-picker-2').select2()
            $('#year-picker-3').select2()

            const grafik1 = document.getElementById('pembelian-penyelesaian');
            const grafik2 = document.getElementById('penjualan-kelas');
            const grafik3 = document.getElementById('penjualan-mitra');

            const showGrafik1 = (data) => {
                new Chart(grafik1, {
                    type: 'bar',
                    data: {
                        labels: data.title,
                        datasets: data.datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            const showGrafik2 = (data) => {
                new Chart(grafik2, {
                    type: 'bar',
                    data: {
                        labels: data.title,
                        datasets: data.datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }
            const showGrafik3 = (data) => {
                new Chart(grafik3, {
                    type: 'bar',
                    data: {
                        labels: data.title,
                        datasets: data.datasets
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }

            // Remove chart
            const removeChart = (chart) => {
                $(`#show-${chart}`).addClass('d-none');
                let chartStatus = Chart.getChart(chart); // <canvas> id
                if (chartStatus != undefined) {
                    chartStatus.destroy();
                }
            }

            const fetchDataPembelianPenyelesaian = (params) => {
                return fetch(`/AplikasiMonitoring/grafik-transaksi/pembelian-penyelesaian?${params}`, {
                    method: 'GET'
                }).then(res => {
                    return res.json()
                }).then(d => {
                    return d
                })
            }


            $(document).on('click', '#show-pembelian-penyelesaian', function() {
                removeChart("pembelian-penyelesaian");
                fetchDataPembelianPenyelesaian('').then((d) => {
                    showGrafik1(d);
                });
            })





            const fetchDataPenjualanKelas = (params) => {
                return fetch(`/AplikasiMonitoring/grafik-transaksi/penjualan-kelas?${params}`, {
                    method: 'GET'
                }).then(res => {
                    return res.json()
                }).then(d => {
                    return d
                })
            }

            $(document).on('click', '#show-penjualan-kelas', function() {
                removeChart("penjualan-kelas");
                fetchDataPenjualanKelas('').then((d) => {
                    // showGrafik2(d);
                    console.log({
                        d
                    });

                });
            })


            const fetchDataPenjualanMitra = (params) => {
                return fetch(`/AplikasiMonitoring/grafik-transaksi/penjualan-mitra?${params}`, {
                    method: 'GET'
                }).then(res => {
                    return res.json()
                }).then(d => {
                    return d
                })
            }




            $(document).on('click', '.search', function() {
                const btn_id = $(this).data('id')
                const btn_number = $(this).data('number')
                const name = $(`#name_${btn_number}`).val()
                const kelas_id = $(`#kelas_id_${btn_number}`).val()
                const year = $(`#year-picker-${btn_number}`).val()
                const redeem_period = $(`#redeem_period`).val()
                const finish_period = $(`#finish_period`).val()
                // console.log({
                //     btn_id,
                //     btn_number,
                //     name,
                //     kelas_id
                // });

                if (btn_id !== 'penjualan-mitra') {
                    if (name && kelas_id) {
                        alert("Pilh salah satu antara Nama Kelas atau Nama Kelas dan Jadwal!")
                        return
                    }
                    // if (!name && !kelas_id) {
                    //     alert("Pilh salah satu antara Nama Kelas atau Nama Kelas dan Jadwal!")
                    //     return
                    // }
                }

                switch (btn_id) {
                    case 'pembelian-penyelesaian':
                        $(`#p-${btn_id}`).addClass('hidden')
                        removeChart("pembelian-penyelesaian");
                        fetchDataPembelianPenyelesaian(`kelas_id=${kelas_id}&name=${name}&year=${year}`)
                            .then((d) => {
                                showGrafik1(d);
                            });
                        break;
                    case 'penjualan-kelas':
                        $(`#p-${btn_id}`).addClass('hidden')
                        removeChart("penjualan-kelas");
                        fetchDataPenjualanKelas(`kelas_id=${kelas_id}&name=${name}&year=${year}`)
                            .then((d) => {
                                showGrafik2(d);
                            });
                        break;
                    case 'penjualan-mitra':
                        $(`#p-${btn_id}`).addClass('hidden')
                        removeChart("penjualan-mitra");
                        fetchDataPenjualanMitra(
                            `kelas_id=${kelas_id}&name=${name}&redeem_period=${redeem_period}&year=${year}`
                            ).then((d) => {
                            showGrafik3(d)
                        })
                        break;

                    default:
                        break;
                }

            })


        })
    </script>
@endpush
