<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Venue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Form pencarian dan filter --}}
            <form method="GET" action="{{ route('venues.index') }}" class="mb-8 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari nama venue</label>
                    <input type="text" name="name" value="{{ $search ?? '' }}"
                        placeholder="Contoh: Merdeka Sport"
                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis olahraga</label>
                    <select name="sport_type"
                        class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                        <option value="">Semua</option>
                        <option value="futsal" @selected($sportType === 'futsal')>Futsal</option>
                        <option value="badminton" @selected($sportType === 'badminton')>Badminton</option>
                    </select>
                </div>
                <button type="submit"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-700">
                    Cari
                </button>
                @if ($search || $sportType)
                    <a href="{{ route('venues.index') }}"
                        class="px-4 py-2 text-sm text-gray-600 hover:underline">Reset</a>
                @endif
            </form>

            {{-- Grid venue --}}
            @if ($venues->isEmpty())
                <p class="text-gray-500">Tidak ada venue yang ditemukan.</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach ($venues as $venue)
                        <a href="{{ route('venues.show', $venue) }}"
                            class="block bg-white rounded-lg shadow hover:shadow-md transition p-5">
                            <h3 class="font-semibold text-gray-900 text-lg mb-1">{{ $venue->name }}</h3>
                            <p class="text-gray-500 text-sm mb-3">{{ Str::limit($venue->address, 70) }}</p>
                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                {{ substr($venue->opening_time, 0, 5) }} – {{ substr($venue->closing_time, 0, 5) }}
                            </div>
                        </a>
                    @endforeach
                </div>
                {{ $venues->links() }}
            @endif

        </div>
    </div>
</x-app-layout>
