<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-2">
            <a href="{{ route('venues.index') }}" class="text-indigo-600 hover:underline text-sm">← Daftar Venue</a>
            <span class="text-gray-400">/</span>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $venue->name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Detail venue --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Venue</h3>
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
                    <div>
                        <dt class="font-medium text-gray-500">Alamat</dt>
                        <dd class="text-gray-800 mt-0.5">{{ $venue->address }}</dd>
                    </div>
                    @if ($venue->phone)
                        <div>
                            <dt class="font-medium text-gray-500">Telepon</dt>
                            <dd class="text-gray-800 mt-0.5">{{ $venue->phone }}</dd>
                        </div>
                    @endif
                    <div>
                        <dt class="font-medium text-gray-500">Jam Operasional</dt>
                        <dd class="text-gray-800 mt-0.5">
                            {{ substr($venue->opening_time, 0, 5) }} – {{ substr($venue->closing_time, 0, 5) }}
                        </dd>
                    </div>
                    @if ($venue->description)
                        <div class="sm:col-span-2">
                            <dt class="font-medium text-gray-500">Deskripsi</dt>
                            <dd class="text-gray-800 mt-0.5">{{ $venue->description }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            {{-- Daftar court --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Lapangan Tersedia</h3>

                @if ($courts->isEmpty())
                    <p class="text-gray-500 text-sm">Belum ada lapangan yang tersedia saat ini.</p>
                @else
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($courts as $court)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-medium text-gray-900">{{ $court->name }}</h4>
                                    <span class="text-xs px-2 py-0.5 rounded bg-indigo-100 text-indigo-700 capitalize">
                                        {{ $court->sport_type }}
                                    </span>
                                </div>
                                @if ($court->description)
                                    <p class="text-gray-500 text-sm mb-2">{{ $court->description }}</p>
                                @endif
                                <p class="text-indigo-600 font-semibold text-sm">
                                    Rp {{ number_format($court->price_per_hour, 0, ',', '.') }} / jam
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
