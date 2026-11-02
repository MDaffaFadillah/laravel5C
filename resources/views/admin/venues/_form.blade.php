{{-- Partial form: dipakai oleh create dan edit --}}

{{-- Name --}}
<div class="mb-4">
    <x-input-label for="name" :value="__('Nama Venue')" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
        :value="old('name', $venue->name ?? '')" required maxlength="150" />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

{{-- Address --}}
<div class="mb-4">
    <x-input-label for="address" :value="__('Alamat')" />
    <textarea id="address" name="address" rows="3"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
        required>{{ old('address', $venue->address ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('address')" class="mt-2" />
</div>

{{-- Description --}}
<div class="mb-4">
    <x-input-label for="description" :value="__('Deskripsi')" />
    <textarea id="description" name="description" rows="3"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $venue->description ?? '') }}</textarea>
    <x-input-error :messages="$errors->get('description')" class="mt-2" />
</div>

{{-- Phone --}}
<div class="mb-4">
    <x-input-label for="phone" :value="__('Telepon')" />
    <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full"
        :value="old('phone', $venue->phone ?? '')" maxlength="20" />
    <x-input-error :messages="$errors->get('phone')" class="mt-2" />
</div>

{{-- Opening & Closing Time --}}
<div class="mb-4 grid grid-cols-2 gap-4">
    <div>
        <x-input-label for="opening_time" :value="__('Jam Buka')" />
        <x-text-input id="opening_time" name="opening_time" type="time" class="mt-1 block w-full"
            :value="old('opening_time', isset($venue) ? substr($venue->opening_time, 0, 5) : '')" required />
        <x-input-error :messages="$errors->get('opening_time')" class="mt-2" />
    </div>
    <div>
        <x-input-label for="closing_time" :value="__('Jam Tutup')" />
        <x-text-input id="closing_time" name="closing_time" type="time" class="mt-1 block w-full"
            :value="old('closing_time', isset($venue) ? substr($venue->closing_time, 0, 5) : '')" required />
        <x-input-error :messages="$errors->get('closing_time')" class="mt-2" />
    </div>
</div>

{{-- Status --}}
<div class="mb-4">
    <x-input-label for="status" :value="__('Status')" />
    <select id="status" name="status"
        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
        <option value="active" @selected(old('status', $venue->status ?? 'active') === 'active')>Aktif</option>
        <option value="inactive" @selected(old('status', $venue->status ?? 'active') === 'inactive')>Tidak Aktif</option>
    </select>
    <x-input-error :messages="$errors->get('status')" class="mt-2" />
</div>
