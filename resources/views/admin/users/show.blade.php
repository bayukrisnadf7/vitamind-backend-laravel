@extends('layouts.admin')

@section('title', 'Detail User - ' . $user->name)
@section('header_title', 'Detail Profil Pengguna')
@section('header_subtitle', 'Informasi lengkap akun dan riwayat skrining user')

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Daftar User</span>
        </a>
    </div>

    <!-- User Profile Header Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-emerald-500/20">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $user->name }}</h2>
                    <p class="text-sm text-slate-500">{{ $user->email }}</p>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700">
                            User
                        </span>
                        <span class="text-xs text-slate-400">Terdaftar sejak {{ $user->created_at->format('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <div class="px-4 py-3 bg-slate-50 rounded-2xl border border-slate-100 text-center">
                    <p class="text-xs text-slate-400 font-medium">Total Skrining</p>
                    <p class="text-lg font-bold text-slate-800">{{ $user->screenings->count() }} kali</p>
                </div>
            </div>
        </div>

        <!-- Detail Data Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 pt-6">
            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Induk Kependudukan (NIK)</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $user->detail->nik ?? 'Belum Diisi' }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Jenis Kelamin</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $user->detail->jenis_kelamin ?? 'Belum Diisi' }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tanggal Lahir</p>
                <p class="text-sm font-bold text-slate-800 mt-1">
                    @if($user->detail && $user->detail->tgl_lahir)
                        {{ \Carbon\Carbon::parse($user->detail->tgl_lahir)->translatedFormat('d F Y') }} 
                        <span class="text-xs font-normal text-slate-500">({{ \Carbon\Carbon::parse($user->detail->tgl_lahir)->age }} tahun)</span>
                    @else
                        Belum Diisi
                    @endif
                </p>
            </div>

            <div>
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Nomor Telepon / WhatsApp</p>
                <p class="text-sm font-bold text-slate-800 mt-1">{{ $user->detail->no_telepon ?? 'Belum Diisi' }}</p>
            </div>
        </div>
    </div>

    <!-- Screening History Table Card -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Riwayat Skrining Pengguna</h3>
                <p class="text-xs text-slate-500">Daftar seluruh asesmen yang telah diselesaikan oleh {{ $user->name }}</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700">
                {{ $user->screenings->count() }} Data
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-slate-50 text-slate-500 uppercase text-[11px] font-semibold tracking-wider border-b border-slate-200">
                    <tr>
                        <th class="py-3.5 px-6">ID Skrining</th>
                        <th class="py-3.5 px-6">Waktu Skrining</th>
                        <th class="py-3.5 px-6">Hasil Tingkat Risiko</th>
                        <th class="py-3.5 px-6 text-center">Jumlah Jawaban</th>
                        <th class="py-3.5 px-6 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($user->screenings as $sc)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-4 px-6 font-mono text-xs text-slate-500">
                                {{ substr($sc->skrining_id, 0, 8) }}...
                            </td>
                            <td class="py-4 px-6">
                                <p class="font-medium text-slate-800">{{ $sc->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                <p class="text-xs text-slate-400">{{ $sc->created_at->diffForHumans() }}</p>
                            </td>
                            <td class="py-4 px-6">
                                @if ($sc->result === 'Risiko Tinggi')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-700 border border-rose-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                        Risiko Tinggi
                                    </span>
                                @elseif ($sc->result === 'Risiko Sedang')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Risiko Sedang
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Risiko Rendah
                                    </span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span class="text-xs text-slate-600 font-medium">
                                    {{ is_array($sc->answers) ? count($sc->answers) : 0 }} butir
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.screenings.show', $sc->skrining_id) }}" 
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors">
                                    <span>Lihat Hasil Detail</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-400 text-sm">
                                Pengguna ini belum pernah melakukan skrining.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
