@extends('layouts.admin')

@section('title', 'Riwayat Skrining')
@section('header_title', 'Riwayat Skrining')
@section('header_subtitle', 'Pantau seluruh hasil kuesioner dan asesmen risiko kesehatan')

@section('content')
    <div class="space-y-6">

        <!-- Top Mini Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-500 uppercase">Total Skrining</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1">{{ number_format($totalScreenings) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
            </div>

            <!-- Rendah -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-emerald-700 uppercase">Risiko Rendah</p>
                    <p class="text-2xl font-extrabold text-emerald-600 mt-1">{{ number_format($totalLow) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>

            <!-- Sedang -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-amber-700 uppercase">Risiko Sedang</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ number_format($totalMedium) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Tinggi -->
            <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-rose-700 uppercase">Risiko Tinggi</p>
                    <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ number_format($totalHigh) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Search & Filter Controls -->
        <div class="bg-white rounded-2xl p-4 sm:p-5 border border-slate-200/80 shadow-xs">
            <form action="{{ route('admin.screenings.index') }}" method="GET" class="space-y-3">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <!-- Search Input -->
                    <div class="md:col-span-2 relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ $search ?? '' }}"
                            placeholder="Cari ID Skrining, nama, email, atau NIK..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-hidden focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20">
                    </div>

                    <!-- Risk Filter -->
                    <div>
                        <select name="risk"
                            class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl text-xs sm:text-sm text-slate-700 focus:outline-hidden focus:border-emerald-500">
                            <option value="">Semua Tingkat Risiko</option>
                            <option value="Risiko Rendah" {{ ($risk ?? '') === 'Risiko Rendah' ? 'selected' : '' }}>Risiko
                                Rendah</option>
                            <option value="Risiko Sedang" {{ ($risk ?? '') === 'Risiko Sedang' ? 'selected' : '' }}>Risiko
                                Sedang</option>
                            <option value="Risiko Tinggi" {{ ($risk ?? '') === 'Risiko Tinggi' ? 'selected' : '' }}>Risiko
                                Tinggi</option>
                        </select>
                    </div>

                    <!-- Action buttons -->
                    <div class="flex items-center gap-2">
                        <button type="submit"
                            class="flex-1 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold rounded-xl shadow-xs transition-colors flex items-center justify-center gap-2 cursor-pointer">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            <span>Filter</span>
                        </button>

                        @if (!empty($search) || !empty($risk) || !empty($dateFrom) || !empty($dateTo))
                            <a href="{{ route('admin.screenings.index') }}"
                                class="px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs sm:text-sm font-semibold rounded-xl transition-colors">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Screening Table Card -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800">Daftar Riwayat Skrining</h2>
                <span class="text-xs text-slate-400">Total {{ $screenings->total() }} hasil skrining</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead
                        class="bg-slate-50 text-slate-500 uppercase text-[11px] font-semibold tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4 sm:px-6">Responden</th>
                            <th class="py-3.5 px-4">Hasil Skrining</th>
                            {{-- <th class="py-3.5 px-4 text-center">Jml Jawaban</th> --}}
                            <th class="py-3.5 px-4">Waktu Skrining</th>
                            <th class="py-3.5 px-4 sm:px-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($screenings as $sc)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="py-4 px-4 sm:px-6">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ substr($sc->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <a href="{{ $sc->user ? route('admin.users.show', $sc->user->user_id) : '#' }}"
                                                class="font-semibold text-slate-900 hover:text-emerald-600 truncate block">
                                                {{ $sc->user->name ?? 'User Telah Dihapus' }}
                                            </a>
                                            <p class="text-xs text-slate-400 truncate">{{ $sc->user->email ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-4">
                                    @if ($sc->result === 'Risiko Tinggi')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-700 border border-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                            Risiko Tinggi
                                        </span>
                                    @elseif ($sc->result === 'Risiko Sedang')
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-amber-100 text-amber-700 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                            Risiko Sedang
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center gap-1.5 px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                            Risiko Rendah
                                        </span>
                                    @endif
                                </td>
                                {{-- <td class="py-4 px-4 text-center">
                                <span class="text-xs text-slate-600 font-medium">
                                    {{ is_array($sc->answers) ? count($sc->answers) : 0 }} butir
                                </span>
                            </td> --}}
                                <td class="py-4 px-4">
                                    <p class="text-slate-700 font-medium">
                                        {{ $sc->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                    <p class="text-[11px] text-slate-400">{{ $sc->created_at->diffForHumans() }}</p>
                                </td>
                                <td class="py-4 px-4 sm:px-6 text-right">
                                    <a href="{{ route('admin.screenings.show', $sc->skrining_id) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-700 text-xs font-semibold transition-colors">
                                        <span>Lihat Detail</span>
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center text-slate-400 text-sm">
                                    Tidak ada data skrining yang sesuai dengan kriteria filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($screenings->hasPages())
                <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                    {{ $screenings->links() }}
                </div>
            @endif
        </div>

    </div>
@endsection
