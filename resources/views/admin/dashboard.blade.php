@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('header_title', 'Dashboard Utama')
@section('header_subtitle', 'Ringkasan performa sistem dan hasil skrining pengguna')

@section('content')
<div class="space-y-6">

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Card 1: Total User -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total User</span>
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalUsers) }}</div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="inline-flex items-center text-emerald-600 font-medium">+{{ $newUsersThisMonth }}</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Skrining -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Skrining</span>
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalScreenings) }}</div>
                <div class="mt-1 flex items-center gap-1.5 text-xs text-slate-500">
                    <span class="inline-flex items-center text-emerald-600 font-medium">+{{ $screeningsThisMonth }}</span>
                    <span>bulan ini</span>
                </div>
            </div>
        </div>

        <!-- Card 3: Risiko Rendah -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Risiko Rendah</span>
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-emerald-600">{{ number_format($riskBreakdown['rendah']) }}</div>
                <div class="mt-1 text-xs text-slate-500">
                    <span class="font-semibold text-emerald-700">{{ $riskPercentages['rendah'] }}%</span> dari total skrining
                </div>
            </div>
        </div>

        <!-- Card 4: Risiko Sedang -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Risiko Sedang</span>
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-amber-600">{{ number_format($riskBreakdown['sedang']) }}</div>
                <div class="mt-1 text-xs text-slate-500">
                    <span class="font-semibold text-amber-700">{{ $riskPercentages['sedang'] }}%</span> dari total skrining
                </div>
            </div>
        </div>

        <!-- Card 5: Risiko Tinggi -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Risiko Tinggi</span>
                <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="text-2xl font-extrabold text-rose-600">{{ number_format($riskBreakdown['tinggi']) }}</div>
                <div class="mt-1 text-xs text-slate-500">
                    <span class="font-semibold text-rose-700">{{ $riskPercentages['tinggi'] }}%</span> butuh perhatian
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Section: Distribution & Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left 2 Cols: Risk Analysis & Monthly Timeline -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Risk Breakdown Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Distribusi Tingkat Risiko</h2>
                        <p class="text-xs text-slate-500">Komposisi hasil skrining seluruh responden</p>
                    </div>
                    <a href="{{ route('admin.screenings.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 hover:underline">
                        Lihat Semua Riwayat &rarr;
                    </a>
                </div>

                <!-- Progress Bars -->
                <div class="space-y-4 mt-6">
                    <!-- Rendah -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1.5">
                            <span class="text-emerald-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                                Risiko Rendah
                            </span>
                            <span class="text-slate-700">{{ $riskBreakdown['rendah'] }} skrining ({{ $riskPercentages['rendah'] }}%)</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $riskPercentages['rendah'] }}%"></div>
                        </div>
                    </div>

                    <!-- Sedang -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1.5">
                            <span class="text-amber-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500 inline-block"></span>
                                Risiko Sedang
                            </span>
                            <span class="text-slate-700">{{ $riskBreakdown['sedang'] }} skrining ({{ $riskPercentages['sedang'] }}%)</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $riskPercentages['sedang'] }}%"></div>
                        </div>
                    </div>

                    <!-- Tinggi -->
                    <div>
                        <div class="flex justify-between text-xs font-semibold mb-1.5">
                            <span class="text-rose-700 flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                                Risiko Tinggi
                            </span>
                            <span class="text-slate-700">{{ $riskBreakdown['tinggi'] }} skrining ({{ $riskPercentages['tinggi'] }}%)</span>
                        </div>
                        <div class="w-full h-3 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ $riskPercentages['tinggi'] }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends Card -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <h2 class="text-base font-bold text-slate-900 mb-1">Aktivitas 6 Bulan Terakhir</h2>
                <p class="text-xs text-slate-500 mb-6">Pertumbuhan pengguna dan aktivitas skrining bulanan</p>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-200 text-slate-400 font-semibold uppercase tracking-wider">
                                <th class="pb-3">Bulan</th>
                                <th class="pb-3 text-center">User Baru</th>
                                <th class="pb-3 text-center">Skrining Dilakukan</th>
                                <th class="pb-3 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($monthlyLabels as $index => $label)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="py-3 font-medium text-slate-800">{{ $label }}</td>
                                    <td class="py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                                            +{{ $monthlyUsers[$index] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-teal-50 text-teal-700">
                                            {{ $monthlyScreenings[$index] ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <span class="text-slate-400 text-[11px]">Aktif</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Right 1 Col: Recent Screenings & New Users -->
        <div class="space-y-6">
            
            <!-- Recent Screenings -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">Skrining Terbaru</h2>
                    <a href="{{ route('admin.screenings.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                        Semua &rarr;
                    </a>
                </div>

                <div class="space-y-3">
                    @forelse ($recentScreenings as $sc)
                        <a href="{{ route('admin.screenings.show', $sc->skrining_id) }}" class="block p-3 rounded-xl border border-slate-100 hover:border-emerald-200 hover:bg-emerald-50/30 transition-all">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-semibold text-xs text-slate-900 truncate max-w-[150px]">
                                    {{ $sc->user->name ?? 'User Anonim' }}
                                </span>
                                @if ($sc->result === 'Risiko Tinggi')
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-rose-100 text-rose-700">Tinggi</span>
                                @elseif ($sc->result === 'Risiko Sedang')
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-700">Sedang</span>
                                @else
                                    <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">Rendah</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-slate-500">
                                <span>{{ $sc->user->email ?? '-' }}</span>
                                <span>{{ $sc->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            Belum ada riwayat skrining.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Recent Registered Users -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-slate-900">User Terbaru</h2>
                    <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">
                        Semua &rarr;
                    </a>
                </div>

                <div class="divide-y divide-slate-100">
                    @forelse ($recentUsers as $usr)
                        <div class="py-3 flex items-center justify-between first:pt-0 last:pb-0">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs shrink-0">
                                    {{ substr($usr->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <a href="{{ route('admin.users.show', $usr->user_id) }}" class="font-semibold text-xs text-slate-900 hover:text-emerald-600 truncate block">
                                        {{ $usr->name }}
                                    </a>
                                    <p class="text-[11px] text-slate-500 truncate">{{ $usr->email }}</p>
                                </div>
                            </div>
                            <span class="text-[10px] text-slate-400 shrink-0 ml-2">
                                {{ $usr->created_at->format('d M') }}
                            </span>
                        </div>
                    @empty
                        <div class="py-6 text-center text-xs text-slate-400">
                            Belum ada pengguna terdaftar.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
