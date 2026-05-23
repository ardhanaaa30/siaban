<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Manajemen User') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Kelola hak akses dan akun pengguna sistem.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('users.reset-requests') }}" class="relative flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-black rounded-2xl hover:bg-slate-50 transition-all shadow-sm border border-slate-100 uppercase tracking-widest text-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                    Permintaan Reset
                    @if($resetRequestsCount > 0)
                        <span class="absolute -top-2 -right-2 w-6 h-6 bg-rose-600 text-white rounded-full flex items-center justify-center text-[10px] font-black border-4 border-slate-50 shadow-lg animate-bounce">
                            {{ $resetRequestsCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('users.create') }}" class="flex items-center gap-2 px-6 py-3 bg-cyan-600 text-white font-black rounded-2xl hover:bg-cyan-700 transition-all shadow-lg shadow-cyan-200 uppercase tracking-widest text-xs">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Tambah User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xl font-black text-slate-900">Daftar Pengguna</h3>
            <span class="px-4 py-1.5 bg-white rounded-xl text-xs font-black text-slate-400 border border-slate-100 shadow-sm uppercase tracking-widest">
                Total: {{ $users->total() }}
            </span>
        </div>

        @if(session('success'))
            <div class="m-8 p-6 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="m-8 p-6 bg-rose-50 text-rose-700 border border-rose-100 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                <span class="text-sm font-bold">{{ session('error') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Pengguna</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Role</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach ($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 flex items-center justify-center text-cyan-600 font-black text-lg border border-cyan-100">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900">{{ $user->name }}</span>
                                        <span class="text-xs font-bold text-slate-400">{{ $user->email }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $roleColor = 'slate';
                                    if ($user->role === 'Admin') $roleColor = 'indigo';
                                    if ($user->role === 'Staff') $roleColor = 'cyan';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black bg-{{ $roleColor }}-50 text-{{ $roleColor }}-700 border border-{{ $roleColor }}-100 uppercase tracking-widest">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center gap-2">
                                    <button 
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'view-user-{{ $user->id }}')"
                                        class="p-2.5 bg-white text-slate-400 border border-slate-100 rounded-xl hover:bg-slate-50 hover:text-slate-600 hover:border-slate-100 transition-all shadow-sm"
                                        title="Lihat Detail"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>

                                    <a 
                                        href="{{ route('users.edit', $user) }}"
                                        class="p-2.5 bg-white text-slate-400 border border-slate-100 rounded-xl hover:bg-cyan-50 hover:text-cyan-600 hover:border-cyan-100 transition-all shadow-sm"
                                        title="Edit User"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    
                                    @if($user->id !== auth()->id())
                                    <button 
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'delete-user-{{ $user->id }}')"
                                        class="p-2.5 bg-white text-slate-400 border border-slate-100 rounded-xl hover:bg-rose-50 hover:text-rose-600 hover:border-rose-100 transition-all shadow-sm"
                                        title="Hapus User"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-4v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    @endif
                                </div>

                                <!-- View Modal -->
                                <x-modal name="view-user-{{ $user->id }}" focusable>
                                    <div class="p-8">
                                        <div class="flex items-center gap-6 mb-8 border-b border-slate-50 pb-8">
                                            <div class="w-20 h-20 rounded-[2rem] bg-cyan-50 flex items-center justify-center text-cyan-600 font-black text-3xl border border-cyan-100 shadow-sm">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h2 class="text-2xl font-black text-slate-900">{{ $user->name }}</h2>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-{{ $roleColor }}-50 text-{{ $roleColor }}-700 border border-{{ $roleColor }}-100 uppercase tracking-widest">
                                                        {{ $user->role }}
                                                    </span>
                                                    <span class="text-xs font-bold text-slate-400">ID: #{{ $user->id }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Email</p>
                                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/50">
                                                    <p class="text-sm font-bold text-slate-700">{{ $user->email }}</p>
                                                </div>
                                            </div>
                                            <div class="space-y-1">
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Bergabung Pada</p>
                                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100/50">
                                                    <p class="text-sm font-bold text-slate-700">{{ $user->created_at->format('d M Y, H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-10 flex justify-end pt-6 border-t border-slate-50">
                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-2xl px-8 py-3 font-black uppercase tracking-widest text-xs">Tutup</x-secondary-button>
                                        </div>
                                    </div>
                                </x-modal>

                                <!-- Delete Modal -->
                                <x-modal name="delete-user-{{ $user->id }}" focusable>
                                    <form method="post" action="{{ route('users.destroy', $user) }}" class="p-8">
                                        @csrf
                                        @method('delete')
                                        <h2 class="text-2xl font-black text-slate-900 mb-2">Hapus Pengguna?</h2>
                                        <p class="text-sm font-medium text-slate-500 mb-8">Apakah Anda yakin ingin menghapus akun <strong>{{ $user->name }}</strong>? Tindakan ini tidak dapat dibatalkan.</p>

                                        <div class="mt-10 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-2xl px-6 py-3 font-black uppercase tracking-widest text-xs">Batal</x-secondary-button>
                                            <x-danger-button class="rounded-2xl px-8 py-3 bg-rose-600 font-black uppercase tracking-widest text-xs shadow-lg shadow-rose-200">Ya, Hapus Akun</x-danger-button>
                                        </div>
                                    </form>
                                </x-modal>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($users->hasPages())
            <div class="p-8 bg-slate-50/30 border-t border-slate-50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
