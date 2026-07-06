<x-filament-panels::page.simple>

    {{-- 1. BACKGROUND & TAMPILAN KUSTOM --}}
    <style>
        /* Mengubah background seluruh halaman login */
        body {
            background-image: url('{{ asset('images/login-bg.webp') }}') !important;
            /* Pastikan gambar ada di public/images/ */
            background-size: cover !important;
            background-position: center !important;
            background-attachment: fixed;
        }

        /* Efek kaca (glassmorphism) pada kotak form agar teks terbaca */
        .fi-simple-main {
            background-color: rgba(50, 49, 49, 0.9) !important;
            backdrop-filter: blur(8px);
            border-radius: 1rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            padding: 2rem !important;
        }
    </style>

    {{-- 2. LINK REGISTER (Jika diaktifkan) --}}
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            Belum punya akun? {{ $this->registerAction }}
        </x-slot>
    @endif

    {{-- 3. FORM LOGIN (MENGGUNAKAN HTML BIASA + TOMBOL FILAMENT) --}}
    <form wire:submit="authenticate" class="grid gap-y-6">

        {{-- Baris ini otomatis merender field "Username/Email", "Password", dan "Remember Me" dari class PHP Anda --}}
        {{ $this->form }}

        {{-- Tombol Submit (Ini adalah pengganti <x-filament-panels::form.actions> yang error) --}}
        <x-filament::button type="submit" class="w-full">
            Log in
        </x-filament::button>

    </form>

</x-filament-panels::page.simple>
