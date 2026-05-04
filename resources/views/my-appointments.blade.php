@extends('layouts.app')

@section('title', 'Randevularım | EstetikDent')

@section('content')
<div class="max-w-5xl mx-auto py-12 px-6">
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-2xl mb-6 font-bold text-center">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-2xl mb-6 font-bold text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-black text-slate-900">Randevularım</h1>
        <a href="{{ route('appointments.create') }}" class="bg-indigo-600 text-white px-5 py-3 rounded-2xl font-bold hover:bg-indigo-700 transition">
            Yeni Randevu Al
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 overflow-hidden">
        @if($appointments->isEmpty())
            <div class="p-8 text-center text-slate-500 font-medium">
                Henüz randevunuz bulunmuyor.
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($appointments as $appointment)
                    <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <p class="text-lg font-black text-slate-900">
                                {{ $appointment->service->name ?? 'Hizmet' }}
                            </p>
                            <p class="text-sm text-slate-500 mt-1">
                                {{ $appointment->start_time->format('d.m.Y') }} - Saat: {{ $appointment->start_time->format('H:i') }}
                            </p>
                            <p class="text-xs text-slate-400 mt-1">
                                Doktor: {{ $appointment->doctor->name ?? 'Atanmadi' }}
                            </p>
                        </div>

                        <div class="flex items-center gap-3">
                            @if($appointment->status === 'cancelled')
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                    İptal Edildi
                                </span>
                            @elseif($appointment->start_time->isPast())
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                                    Geçmiş Randevu
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                    Onaylandı
                                </span>
                            @endif

                            @if($appointment->status !== 'cancelled' && $appointment->start_time->isFuture())
                                <form action="{{ route('appointments.cancel', $appointment) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="border border-red-200 text-red-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-red-50 transition">
                                        İptal Et
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
