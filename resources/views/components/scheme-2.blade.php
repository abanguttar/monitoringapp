<div class="flex flex-col">
    @csrf
    <div class="mt-4 grid grid-cols-2 gap-4">
        <div class="p-4">
            <label for="{{ $obj->name }}"
                class="text-black ms-2 font-semibold">{{ str_replace('_', ' ', ucwords($obj->name)) }}</label>
            <div class="mt-5">
                <select style="width: 100%" class="select select-bordered w-full"
                    name="{{ $obj->name . '[' . $obj->day . ']' }}" id="{{ $obj->day_number }}">
                    <option value="">--- Pilih {{ str_replace('_', ' ', ucwords($obj->name)) }} ---</option>
                </select>
            </div>

            @error($obj->name . '.' . $obj->day)
                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
            @enderror
        </div>
        <div class="p-4">
            <label for="{{ $obj->commission }}"
                class="text-black ms-2 font-semibold">{{ str_replace('_', ' ', ucwords($obj->commission)) }}</label>
            <input type="text" name="{{ $obj->commission . '[' . $obj->day . ']' }}"
                placeholder="Nilai {{ str_replace('_', ' ', ucwords($obj->commission)) }}"
                class="mt-2 input bg-white input-bordered w-full " id="{{ $obj->commission }}"
                value="{{ old($obj->commission . '.' . $obj->day, $obj->total) }}" />
            @error($obj->commission . '.' . $obj->day)
                <p class="text-red-600 block ml-2 mt-2 font-bold">{{ $message }}</p>
            @enderror
        </div>



    </div>

</div>
