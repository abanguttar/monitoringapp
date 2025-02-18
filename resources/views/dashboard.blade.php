@extends('layouts.main')
@section('body')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <h3 class="text-center font-bold text-black mt-3">{{ $title }}</h3>
            <div class="p-6 text-gray-900">
                {{ __("Selamat datang, ").auth()->user()->name .'!' }}
            </div>
        </div>
    </div>
</div>
@endsection
