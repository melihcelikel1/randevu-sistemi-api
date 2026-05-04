@extends('layouts.app')

@section('title', 'Giriş Yap | LorienDent')

@section('content')
<div class="max-w-md mx-auto py-14 px-6">
    <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8">
        <h1 class="text-2xl font-black text-slate-900 mb-2">Giriş Yap</h1>
        <p class="text-slate-500 mb-8 text-sm">Randevularınızı görüntülemek için hesabınıza giriş yapın.</p>

        <form action="{{ route('login.attempt') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="text-xs font-black text-slate-500 uppercase ml-1">E-posta</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="mt-1 w-full p-4 bg-slate-50 border @error('email') border-red-500 @else border-slate-200 @enderror rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
                @error('email') <p class="text-red-500 text-xs font-bold mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="text-xs font-black text-slate-500 uppercase ml-1">Parola</label>
                <input type="password" name="password" required
                    class="mt-1 w-full p-4 bg-slate-50 border border-slate-200 rounded-2xl focus:ring-2 focus:ring-indigo-500 outline-none transition">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300">
                Beni hatırla
            </label>

            <button type="submit" class="w-full bg-slate-900 text-white py-4 rounded-2xl font-black hover:bg-indigo-600 transition">
                Giriş Yap
            </button>
        </form>

        <p class="text-sm text-slate-500 mt-6 text-center">
            Hesabın yok mu?
            <a href="{{ route('register') }}" class="font-bold text-indigo-600">Kayıt Ol</a>
        </p>
    </div>
</div>
@endsection
