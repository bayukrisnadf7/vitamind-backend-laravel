@extends('layouts.admin')

@section('title', 'Detail Skrining - ' . ($screening->user->name ?? 'Responden'))
@section('header_title', 'Detail Hasil Skrining')
@section('header_subtitle', 'Rincian skor dan rekaman jawaban kuesioner responden')

@section('content')
<div class="space-y-6">

    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.screenings.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span>Kembali ke Riwayat Skrining</span>
        </a>
    </div>

    <!-- Main Summary Card -->
    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
        
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 pb-6 border-b border-slate-100">
            <!-- User Info -->
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-emerald-500 to-teal-400 text-white flex items-center justify-center font-bold text-2xl shadow-lg shadow-emerald-500/20 shrink-0">
                    {{ substr($screening->user->name ?? 'U', 0, 1) }}
                </div>
                <div>
                    <h2 class="text-xl font-bold text-slate-900">
                        {{ $screening->user->name ?? 'User Telah Dihapus' }}
                    </h2>
                    <p class="text-sm text-slate-500">{{ $screening->user->email ?? '-' }}</p>
                    <p class="text-xs text-slate-400 mt-1">
                        Skrining dilakukan pada <span class="font-semibold text-slate-700">{{ $screening->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                    </p>
                </div>
            </div>

            <!-- Risk Banner -->
            <div>
                @if ($screening->result === 'Risiko Tinggi')
                    <div class="px-5 py-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-rose-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-rose-500 uppercase tracking-wider">Status Penilaian</p>
                            <p class="text-base font-extrabold text-rose-700">Risiko Tinggi Tertular</p>
                        </div>
                    </div>
                @elseif ($screening->result === 'Risiko Sedang')
                    <div class="px-5 py-3.5 rounded-2xl bg-amber-50 border border-amber-200 text-amber-800 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-amber-500 uppercase tracking-wider">Status Penilaian</p>
                            <p class="text-base font-extrabold text-amber-700">Risiko Sedang Tertular</p>
                        </div>
                    </div>
                @else
                    <div class="px-5 py-3.5 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-500/20">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold text-emerald-500 uppercase tracking-wider">Status Penilaian</p>
                            <p class="text-base font-extrabold text-emerald-700">Risiko Rendah</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Metric Details -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Skor Diperoleh</p>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $totalScoreObtained }} poin</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Skor Maksimal</p>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $totalMaxScore }} poin</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Persentase Tingkat Risiko</p>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ $percentage }}%</p>
            </div>

            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase">Jumlah Pertanyaan</p>
                <p class="text-lg font-bold text-slate-800 mt-1">{{ count($detailedAnswers) }} butir dijawab</p>
            </div>
        </div>
    </div>

    <!-- Answers Breakdown Table -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-900">Rincian Jawaban Kuesioner</h3>
                <p class="text-xs text-slate-500">Transkrip lengkap pertanyaan dan opsi yang dipilih oleh responden</p>
            </div>
            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700">
                {{ count($detailedAnswers) }} Pertanyaan
            </span>
        </div>

        <div class="divide-y divide-slate-100">
            @forelse ($detailedAnswers as $index => $item)
                <div class="p-5 sm:p-6 hover:bg-slate-50/60 transition-colors">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                        <div class="space-y-1.5 max-w-3xl">
                            <div class="flex items-center gap-2">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold uppercase tracking-wider">
                                    {{ $item['question_id'] }}
                                </span>
                                <span class="text-xs font-medium text-emerald-600">
                                    {{ $item['category'] }}
                                </span>
                            </div>
                            <h4 class="text-sm font-semibold text-slate-900 leading-snug">
                                {{ $item['question'] }}
                            </h4>
                        </div>

                        <div class="flex sm:flex-col items-center sm:items-end justify-between gap-2 shrink-0">
                            <span class="inline-flex items-center px-3 py-1 rounded-xl bg-slate-100 text-slate-800 text-xs font-semibold">
                                Jawaban: {{ $item['user_answer'] }}
                            </span>
                            <span class="text-[11px] text-slate-500">
                                Skor: <span class="font-bold text-slate-800">{{ $item['score'] }}</span> / {{ $item['max_score'] }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-12 text-center text-slate-400 text-sm">
                    Tidak ada rekaman butir kuesioner.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection
