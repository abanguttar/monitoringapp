@php
    $disable = $disable ?? false;
@endphp
<div class="input-group p-2 mt-2">
    <label for="{{ $value }}" class="text-black ms-2 font-semibold">{{ ucwords($name) }}</label>
    <select {{ $disable === true ? 'disabled' : '' }} class="select select-bordered w-full bg-white" id="{{ $value }}" name="{{ $value }}">
        <option value="">--- Pilih {{ $name }} ---</option>
        @foreach ($options as $option)
            <option value="{{ $option->value }}"
                {{ $data !== null ? ($data->$value == $option->value ? 'selected' : '') : null }}>
                {{ $option->title }}</option>
        @endforeach
    </select>
    @error($value)
        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
    @enderror
</div>
