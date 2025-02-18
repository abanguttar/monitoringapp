@extends('layouts.main')
@section('body')
    <div class="py-12">
        <div class="container-xl flex justify-center  sm:px-6 lg:px-8">
            <div class=" container bg-white overflow-hidden shadow-sm sm:rounded-lg pb-3">
                <h1 class="text-center text-xl font-bold text-black mt-3">{{ $title }}</h1>
                <div class="container mt-5">
                    <form action="" method="POST">
                        @csrf
                        @if ($trainer)
                            @method('PUT')
                        @endif
                        @include('components/input', [
                            'name' => 'Nama Trainer',
                            'value' => 'name',
                            'data' => $trainer ?? null,
                        ])
                        @include('components/input', [
                            'name' => 'No NPWP',
                            'value' => 'npwp',
                            'data' => $trainer ?? null,
                        ])
                         @include('components/select', [
                            'name' => 'Status Tanggungan Pajak',
                            'value' => 'status_tanggungan',
                            'options' => [
                                (object) [
                                    'title' => 'TK/0',
                                    'value' => 'TK/0',
                                ],
                                (object) [
                                    'title' => 'TK/1',
                                    'value' => 'TK/1',
                                ],
                                (object) [
                                    'title' => 'TK/2',
                                    'value' => 'TK/2',
                                ],
                                (object) [
                                    'title' => 'TK/3',
                                    'value' => 'TK/3',
                                ],
                            ],
                            'data' => $trainer ?? null,
                        ])

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
    $(document).ready(function(){
        const callback_status_tanggungan = @json(old('status_tanggungan')) ?? null
        if(callback_status_tanggungan){
            $('#status_tanggungan').val(callback_status_tanggungan)
        }
    })
</script>

@endpush
