<div class="input-group p-2 mt-2">
    <label for="kelas_id"
        class="text-black ms-2 font-semibold">{{ ucwords('Nama Kelas dan Jadwal') }}</label>
    <select class="select mt-2 select-bordered w-full bg-white" name="kelas_id" id="kelas_id">
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
