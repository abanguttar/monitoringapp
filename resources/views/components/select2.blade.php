<div class="input-group p-2 mt-2">
    <label for="{{ $value }}" class="text-black ms-2 font-semibold">{{ ucwords($name) }}</label>
    <select class="select mt-2 select-bordered w-full" name="{{ $value }}" id="{{ $value }}">
        <option value="">--- Pilih {{ ucwords($name) }} ---</option>
        @foreach ($options as $option)
            <option value="{{ $option->id }}">{{ $option->name }}</option>
        @endforeach
    </select>
    @error($value)
        <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
    @enderror
</div>
