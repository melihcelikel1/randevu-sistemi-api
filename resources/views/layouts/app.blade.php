<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>@yield('title', 'LorienDent')</title>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="bg-[#DCEEFF] shadow-sm py-2 px-6 md:px-8 flex justify-between items-center gap-4 flex-nowrap sticky top-0 z-50">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center shrink-0">
            <img 
                src="{{ asset('images/loriendent-logo.png') }}" 
                alt="LorienDent"
                class="h-16 w-auto object-contain"
            >
        </a>

        <!-- Menü -->
        <div class="hidden md:flex gap-6 font-semibold text-slate-700">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition duration-200">
                Ana Sayfa
            </a>

            <a href="{{ route('appointments.my') }}" class="hover:text-indigo-600 transition duration-200">
                Randevularım
            </a>
        </div>

        <!-- Sağ Butonlar -->
        <div class="flex items-center gap-2 md:gap-3 shrink-0">

            @auth

                <a href="{{ route('appointments.create') }}"
                   class="inline-flex items-center whitespace-nowrap bg-indigo-600 text-white px-5 py-2 rounded-full font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Randevu Al
                </a>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf

                    <button type="submit"
                            class="inline-flex items-center whitespace-nowrap border border-slate-300 bg-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-100 transition">
                        Çıkış
                    </button>
                </form>

            @else

                <a href="{{ route('login') }}"
                   class="inline-flex items-center whitespace-nowrap border border-slate-300 bg-white px-4 py-2 rounded-full text-sm font-semibold hover:bg-slate-100 transition">
                    Giriş Yap
                </a>

                <a href="{{ route('register') }}"
                   class="inline-flex items-center whitespace-nowrap bg-indigo-600 text-white px-4 py-2 rounded-full text-sm font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                    Kayıt Ol
                </a>

            @endauth

        </div>
    </nav>

    <!-- İçerik -->
    <main class="flex-1">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-white py-12 mt-20 text-center">
        <p>&copy; 2026 LorienDent Klinik. Tüm Hakları Saklıdır.</p>
    </footer>

</body>
</html>