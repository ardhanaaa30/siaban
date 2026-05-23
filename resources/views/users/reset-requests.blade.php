<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-extrabold text-3xl text-slate-900 leading-tight">
                    {{ __('Permintaan Reset Password') }}
                </h2>
                <p class="text-slate-500 mt-1 font-medium">Kelola permintaan pengguna yang lupa kata sandi.</p>
            </div>
            <a href="{{ route('users') }}" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-600 font-black rounded-2xl hover:bg-slate-50 transition-all shadow-sm border border-slate-100 uppercase tracking-widest text-xs">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
            <h3 class="text-xl font-black text-slate-900">Antrian Permintaan</h3>
            <span class="px-4 py-1.5 bg-white rounded-xl text-xs font-black text-slate-400 border border-slate-100 shadow-sm uppercase tracking-widest">
                Total: {{ $requests->total() }}
            </span>
        </div>

        @if(session('success'))
            <div class="m-8 p-6 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-2xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <span class="text-sm font-bold">{{ session('success') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Pengguna</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Waktu Permintaan</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                        <th class="px-8 py-6 text-xs font-black text-slate-400 uppercase tracking-[0.2em]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($requests as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-6">
                                <div class="flex flex-col">
                                    <span class="text-sm font-black text-slate-900">{{ $item->user->name ?? 'User Tidak Ditemukan' }}</span>
                                    <span class="text-xs font-bold text-slate-400">{{ $item->email }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                <span class="text-sm font-bold text-slate-600">{{ $item->created_at->diffForHumans() }}</span>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusColor = 'slate';
                                    if ($item->status === 'pending') $statusColor = 'amber';
                                    if ($item->status === 'completed') $statusColor = 'emerald';
                                    if ($item->status === 'rejected') $statusColor = 'rose';
                                @endphp
                                <span class="inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-100 uppercase tracking-widest">
                                    {{ $item->status }}
                                </span>
                            </td>
                            <td class="px-8 py-6">
                                @if($item->status === 'pending')
                                <div class="flex items-center gap-2">
                                    <button 
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'approve-reset-{{ $item->id }}')"
                                        class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl hover:bg-emerald-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest shadow-sm"
                                    >
                                        Setujui & Reset
                                    </button>
                                    <button 
                                        x-data=""
                                        x-on:click.prevent="$dispatch('open-modal', 'reject-reset-{{ $item->id }}')"
                                        class="px-4 py-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl hover:bg-rose-600 hover:text-white transition-all text-[10px] font-black uppercase tracking-widest shadow-sm"
                                    >
                                        Tolak
                                    </button>
                                </div>

                                <!-- Approve Modal -->
                                <x-modal name="approve-reset-{{ $item->id }}" focusable>
                                    <form method="post" action="{{ route('users.reset-requests.approve', $item) }}" class="p-8">
                                        @csrf
                                        <h2 class="text-2xl font-black text-slate-900 mb-2">Setujui Reset Password?</h2>
                                        <p class="text-sm font-medium text-slate-500 mb-8">Masukkan password baru untuk user <strong>{{ $item->user->name ?? $item->email }}</strong>.</p>

                                        <div class="space-y-2" x-data="{ show: false }">
                                            <x-input-label for="password" value="Password Baru" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                                            <div class="relative group">
                                                <x-text-input id="password" name="password" ::type="show ? 'text' : 'password'" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" required />
                                                <button type="button" @click="show = !show" class="absolute right-6 top-1/2 -translate-y-1/2 text-slate-300 hover:text-cyan-600 transition-colors focus:outline-none">
                                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                    <svg x-show="show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="mt-10 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-2xl px-6 py-3 font-black uppercase tracking-widest text-xs">Batal</x-secondary-button>
                                            <button type="submit" class="rounded-2xl px-8 py-3 bg-emerald-600 text-white font-black uppercase tracking-widest text-xs shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all">Simpan & Selesaikan</button>
                                        </div>
                                    </form>
                                </x-modal>

                                <!-- Reject Modal -->
                                <x-modal name="reject-reset-{{ $item->id }}" focusable>
                                    <form method="post" action="{{ route('users.reset-requests.reject', $item) }}" class="p-8">
                                        @csrf
                                        <h2 class="text-2xl font-black text-slate-900 mb-2">Tolak Permintaan?</h2>
                                        <p class="text-sm font-medium text-slate-500 mb-8">Apakah Anda yakin ingin menolak permintaan reset password dari <strong>{{ $item->email }}</strong>?</p>

                                        <div class="space-y-2">
                                            <x-input-label for="admin_note" value="Alasan Penolakan (Opsional)" class="text-xs font-black text-slate-400 uppercase tracking-widest ml-1" />
                                            <textarea id="admin_note" name="admin_note" class="w-full bg-slate-50 border-none rounded-2xl py-4 px-6 font-bold focus:ring-2 focus:ring-cyan-500/20" rows="3"></textarea>
                                        </div>

                                        <div class="mt-10 flex justify-end gap-3">
                                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-2xl px-6 py-3 font-black uppercase tracking-widest text-xs">Batal</x-secondary-button>
                                            <button type="submit" class="rounded-2xl px-8 py-3 bg-rose-600 text-white font-black uppercase tracking-widest text-xs shadow-lg shadow-rose-200 hover:bg-rose-700 transition-all">Ya, Tolak Permintaan</button>
                                        </div>
                                    </form>
                                </x-modal>
                                @else
                                    <span class="text-xs font-bold text-slate-400">Selesai pada {{ $item->updated_at->format('d/m/Y') }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-12 text-center">
                                <div class="flex flex-col items-center gap-2">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                                    </div>
                                    <p class="text-slate-400 font-bold">Tidak ada permintaan reset password saat ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
            <div class="p-8 bg-slate-50/30 border-t border-slate-50">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
