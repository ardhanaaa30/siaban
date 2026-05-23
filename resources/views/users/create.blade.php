<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Tambah User Baru') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Buat akun baru untuk Staff atau Warga secara manual.</p>
            </div>
            <a href="{{ route('users') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-black rounded-2xl hover:bg-slate-50 transition-all shadow-sm border border-slate-100 uppercase tracking-widest text-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <h3 class="text-xl font-black text-slate-900">Formulir Registrasi User</h3>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="p-8">
                @csrf
                
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <x-input-label for="name" value="Nama Lengkap" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                            <x-text-input id="name" name="name" type="text" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="email" value="Alamat Email" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                            <x-text-input id="email" name="email" type="email" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" :value="old('email')" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <x-input-label for="role" value="Role / Hak Akses" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                            <select id="role" name="role" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold text-sm focus:ring-2 focus:ring-cyan-500/20 appearance-none">
                                <option value="Warga" {{ old('role') == 'Warga' ? 'selected' : '' }}>Warga</option>
                                <option value="Staff" {{ old('role') == 'Staff' ? 'selected' : '' }}>Staff</option>
                                <option value="Admin" {{ old('role') == 'Admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>

                        <div class="space-y-2">
                            <x-input-label for="password" value="Password" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                            <x-text-input id="password" name="password" type="password" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" required />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-3 border-t border-slate-50 pt-8">
                    <a href="{{ route('users') }}" class="px-8 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-slate-200 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="px-10 py-4 bg-cyan-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs shadow-lg shadow-cyan-200 hover:bg-cyan-700 transition-all">
                        Simpan User Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
