<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Selamat datang di panel admin. Gunakan menu di atas untuk mengelola venue, lapangan, dan booking.</p>
                </div>
                <div class="p-6 border-t border-gray-100">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Aksi Cepat') }}</h3>
                    <div class="flex gap-4">
                        <a href="{{ route('admin.venues.index') }}"
                           class="px-6 py-3 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                            {{ __('Kelola Venue') }}
                        </a>
                        <a href="{{ route('admin.courts.index') }}"
                           class="px-6 py-3 bg-indigo-600 text-white rounded-md text-sm font-medium hover:bg-indigo-700 transition">
                            {{ __('Kelola Lapangan') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
