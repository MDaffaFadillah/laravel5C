{{-- Partial form: dipakai oleh create dan edit court --}}

{{-- Venue --}}
<div class="mb-4">
    <x-input-label for="venue_id" :value="__('Venue')" />
    <select id="venue_id" name="venue_id"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        required>
        <option value="">-- Pilih Venue --</option>
        @foreach ($venues as $id => $name)
            <option value="{{ $id }}" @selected(old('venue_id', $court->venue_id ?? '') == $id)>
                {{ $name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('venue_id')" class="mt-2" />
</div>

{{-- Name --}}
<div class="mb-4">
    <x-input-label for="name" :value="__('Nama Lapangan')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
        :value="old('name', $court->name ?? '')" required maxlength="100" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

{{-- Sport Type --}}
<div class="mb-4">
    <x-input-label for="sport_type" :value="__('Jenis Olahraga')" />
    <select id="sport_type" name="sport_type"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        required>
        <option value="futsal" @selected(old('sport_type', $court->sport_type ?? '') === 'futsal')>Futsal</option>
        <option value="badminton" @selected(old('sport_type', $court->sport_type ?? '') === 'badminton')>Badminton</option>
    </select>
    <x-input-error :messages="$errors->get('sport_type')" class="mt-2" />
</div>

{{-- Description --}}
<div class="mb-4">
    <x-input-label for="description" :value="__('Deskripsi')" />
    <textarea id="description" name="description" rows="3"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $court->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

{{-- Price per hour --}}
<div class="mb-4">
    <x-input-label for="price_per_hour" :value="__('Harga per Jam (Rp)')" />
    <x-text-input id="price_per_hour" name="price_per_hour" type="number" class="mt-1 block w-full"
        :value="old('price_per_hour', $court->price_per_hour ?? '')" required min="0" step="1000" />
    <x-input-error :messages="$errors->get('price_per_hour')" class="mt-2" />
</div>

{{-- Status --}}
<div class="mb-4">
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        <option value="available" @selected(old('status', $court->status ?? 'available') === 'available')>Tersedia</option>
        <option value="maintenance" @selected(old('status', $court->status ?? 'available') === 'maintenance')>Perawatan</option>
        <option value="inactive" @selected(old('status', $court->status ?? 'available') === 'inactive')>Tidak Aktif</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>
