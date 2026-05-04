@extends('layouts.app')

@section('title', 'Hoş Geldiniz | LorienDent')

@section('content')
<section class="relative bg-white py-20 px-8">
    <div class="max-w-7xl mx-auto grid md:grid-cols-2 gap-12 items-center">
        <div>
            <span class="text-indigo-600 font-bold tracking-widest uppercase text-sm">Sağlıklı ağız, mutlu hayat.</span>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 mt-4 leading-tight">
                Modern Diş Hekimliği İle <span class="text-indigo-600">Tanışın.</span>
            </h1>
            <p class="text-slate-500 mt-6 text-lg leading-relaxed">
                Alanında uzman hekim kadromuz ve son teknoloji ekipmanlarımızla, acısız ve konforlu tedavi imkanı sunuyoruz.
            </p>
            <div class="mt-10 flex gap-4">
                <a href="/randevu-al" class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black text-lg hover:scale-105 transition shadow-xl shadow-indigo-200">
                    Hemen Randevu Al
                </a>
                <button class="border-2 border-slate-200 px-8 py-4 rounded-2xl font-bold text-lg hover:bg-slate-50 transition">
                    Hizmetlerimiz
                </button>
            </div>
        </div>
        <div class="relative">
            <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?auto=format&fit=crop&q=80&w=1000" class="rounded-[3rem] shadow-2xl" alt="Klinik">
        </div>
    </div>
</section>
@endsection