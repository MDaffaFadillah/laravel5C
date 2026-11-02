<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Venue') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('admin.venues.store') }}" method="POST">
                    @csrf
                    @include('admin.venues._form')
                    <div class="flex items-center gap-4 mt-6">
                        <x-primary-button>Simpan</x-primary-button>
                        <a href="{{ route('admin.venues.index') }}" class="text-gray-600 hover:underline text-sm">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
