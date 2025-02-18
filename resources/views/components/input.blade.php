@php
    $disable = $disable ?? false;
@endphp
<div class="input-group p-2 mt-2">
    <label for="{{ $value }}" class="text-black ms-2 font-semibold">{{ ucwords($name) }}</label>
    <input type="text" name="{{ $value }}" placeholder="Masukkan {{ $name }}"
        class="mt-2 input bg-white input-bordered w-full " id="{{ $value }}" value="{{ old($value, $data->$value ?? '') }}"
        {{ $disable === true ? 'readonly' : '' }} />
    @error($value)
        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
    @enderror
</div>
