<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Pengaturan Profil') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Kelola informasi akun dan keamanan kata sandi Anda.</p>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto space-y-8 pb-12">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <h3 class="text-xl font-black text-slate-900">Informasi Profil</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Perbarui nama dan alamat email akun Anda.</p>
            </div>
            <div class="p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <h3 class="text-xl font-black text-slate-900">Keamanan Password</h3>
                <p class="text-sm font-medium text-slate-500 mt-1">Pastikan akun Anda menggunakan kata sandi yang kuat dan aman.</p>
            </div>
            <div class="p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
