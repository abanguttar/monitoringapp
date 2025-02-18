<form action="" method="POST" class="flex flex-col bg-neutral-100 shadow border mx-5 p-4">
    @csrf
    <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="p-4">
            <label for="{{ $obj->name }}"
                class="text-black ms-2 font-semibold">{{ str_replace('_', ' ', ucwords($obj->name)) }}</label>
            {{-- <input type="text" name="{{ $obj->name }}"
                placeholder="Nama {{ str_replace('_', ' ', ucwords($obj->name)) }}"
                class="mt-2 input bg-white input-bordered w-full " id="{{ $obj->name }}"
                value="{{ old($obj->name) }}" /> --}}
            <div class="mt-5">
                <select style="width: 100%" class="select select-bordered w-full trainers" name="{{ $obj->name }}"
                    id="{{ $obj->name }}">
                    <option value="">--- Pilih {{ str_replace('_', ' ', ucwords($obj->name)) }} ---</option>
                    {{-- @foreach ($options as $option)
                        <option value="{{ $option->id }}">{{ $option->name }}</option>
                    @endforeach --}}
                </select>
            </div>

            @error($obj->name)
                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
            @enderror
        </div>
        <div class="p-4">
            <label for="{{ $obj->commission }}"
                class="text-black ms-2 font-semibold">{{ str_replace('_', ' ', ucwords($obj->commission)) }}</label>
            <input type="text" name="{{ $obj->commission }}"
                placeholder="Nilai {{ str_replace('_', ' ', ucwords($obj->commission)) }}"
                class="mt-2 input bg-white input-bordered w-full " id="{{ $obj->commission }}"
                value="{{ old($obj->commission, $obj->total) }}" />
            @error($obj->commission)
                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
            @enderror
        </div>

        <input type="hidden" name="type" value="{{ $obj->type }}">
        <input type="hidden" name="scheme" value="Minimum">
    </div>
    <div class="flex justify-end">
        <button type="submit" class="btn btn-sm btn-error text-white me-5">Simpan</button>
    </div>
</form>
