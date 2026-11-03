<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Daftar Lapangan') }}
            </h2>
            <a href="{{ route('admin.courts.create') }}"
               class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                + Tambah Lapangan
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Lapangan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Venue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Olahraga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga/Jam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($courts as $court)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $court->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">{{ $court->venue->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600 capitalize">{{ $court->sport_type }}</td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    Rp {{ number_format($court->price_per_hour, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @php
                                        $badge = match($court->status) {
                                            'available'   => 'bg-green-100 text-green-800',
                                            'maintenance' => 'bg-yellow-100 text-yellow-800',
                                            default       => 'bg-gray-100 text-gray-600',
                                        };
                                        $label = match($court->status) {
                                            'available'   => 'Tersedia',
                                            'maintenance' => 'Perawatan',
                                            default       => 'Tidak Aktif',
                                        };
                                    @endphp
                                    <span class="px-2 py-1 rounded text-xs font-semibold {{ $badge }}">{{ $label }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm space-x-2">
                                    <a href="{{ route('admin.courts.edit', $court) }}"
                                       class="text-indigo-600 hover:underline">Edit</a>
                                    <form action="{{ route('admin.courts.destroy', $court) }}" method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada lapangan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="p-4">{{ $courts->links() }}</div>
            </div>

        </div>
    </div>
</x-app-layout>
