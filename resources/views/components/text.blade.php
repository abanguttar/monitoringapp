<div class="input-group p-2 mt-2">
    <label for="name" class="text-black ms-2 font-semibold">{{ ucwords($name) }}</label>
    <textarea name="{{ $value }}" placeholder="Masukkan {{ $name }}"
        class="mt-2 textarea textarea-bordered w-full bg-white">{{ old($value, $data->$value ?? '') }}</textarea>
    @error($value)
        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
    @enderror
</div>
