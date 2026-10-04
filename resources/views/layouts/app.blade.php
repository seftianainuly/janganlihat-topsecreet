<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="GadgetHub — ulasan independen dan panduan teknologi untuk pilihan gadget yang lebih tepat.">
    <title>@yield('title', 'GadgetHub')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            color-scheme: light;
            --page: #f7f6f2;
            --surface: #fff;
            --ink: #202820;
            --muted: #747b72;
            --line: #e6e6de;
            --brand: #e86c43;
            --brand-dark: #c9512d;
            --green: #244e42;
        }
        body { background:var(--page); color:var(--ink); font-family:'DM Sans',sans-serif; }
        h1,h2,h3,h4,.font-orbitron { font-family:'Manrope',sans-serif; }
        [x-cloak] { display:none !important; }
        .text-white { color:var(--ink) !important; }
        .text-slate-200,.text-slate-300 { color:#39423a !important; }
        .text-slate-400,.text-slate-500,.text-slate-600 { color:var(--muted) !important; }
        .border-slate-800,.border-slate-700 { border-color:var(--line) !important; }
        .bg-slate-950,.bg-slate-900,.bg-slate-800 { background-color:var(--surface) !important; }
        .site-nav { background:rgb(255 255 255 / 94%); border-bottom:1px solid var(--line); }
        .site-footer { background:#eeeee7; border-top:1px solid var(--line); }
        .glass-card { background:var(--surface); border:1px solid var(--line); box-shadow:0 2px 8px rgb(32 40 32 / 4%); }
        .gradient-text { color:var(--brand); }
        .category-pill { display:inline-flex; padding:.25rem .65rem; border-radius:999px; background:#fff0e8; color:#a34426; font-size:.72rem; font-weight:700; }
        .btn-primary { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; border:0; border-radius:.55rem; background:var(--brand); color:#fff !important; padding:.7rem 1.1rem; font-weight:700; transition:background .2s ease; }
        .btn-primary:hover { background:var(--brand-dark); }
        .btn-outline { display:inline-flex; align-items:center; justify-content:center; gap:.5rem; border:1px solid var(--line); border-radius:.55rem; background:var(--surface); color:var(--ink); padding:.65rem 1rem; font-weight:600; transition:border-color .2s ease,color .2s ease; }
        .btn-outline:hover { border-color:var(--brand); color:var(--brand); }
        .prose { color:var(--ink); }
        .prose h2,.prose h3 { color:var(--green); font-family:'Manrope',sans-serif; }
        .prose blockquote { border-left-color:var(--brand); color:var(--muted); }
        .prose a { color:var(--brand-dark); }
        .prose strong { color:var(--ink); }
        .line-clamp-2 { display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; }
    </style>
    @stack('styles')
</head>

<body class="min-h-screen antialiased">
    <nav class="site-nav sticky top-0 z-50" x-data="{ mobileOpen: false }">
        <div class="mx-auto flex h-18 max-w-6xl items-center justify-between px-5 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5" aria-label="GadgetHub beranda">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-[#244e42] text-sm font-extrabold text-white">G.</span>
                <span>
                    <span class="block text-xl font-extrabold leading-5 tracking-tight">Gadget<span style="color:var(--brand)">Hub</span></span>
                    <span class="mt-1 hidden text-[9px] font-bold uppercase tracking-[.16em] text-slate-500 sm:block">Catatan teknologi</span>
                </span>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" class="text-sm font-semibold hover:text-orange-700">Beranda</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold hover:text-orange-700">Artikel</a>
                <div class="hidden h-5 border-l lg:block" style="border-color:var(--line)"></div>
                <div class="hidden items-center gap-5 lg:flex" aria-label="Kategori artikel">
                    <a href="{{ route('blog.index', ['category' => 'HP']) }}" class="text-sm font-medium text-slate-500 hover:text-orange-700">HP</a>
                    <a href="{{ route('blog.index', ['category' => 'Laptop']) }}" class="text-sm font-medium text-slate-500 hover:text-orange-700">Laptop</a>
                    <a href="{{ route('blog.index', ['category' => 'Wearables']) }}" class="text-sm font-medium text-slate-500 hover:text-orange-700">Wearables</a>
                </div>
                @auth
                    <a href="{{ route('admin.blogs.index') }}" class="text-sm font-semibold hover:text-orange-700">Dashboard</a>
                @endauth
            </div>

            <div class="flex items-center gap-3">
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="hidden md:block">
                        @csrf
                        <button type="submit" class="text-sm font-semibold text-slate-500 hover:text-orange-700">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="hidden rounded-lg border px-4 py-2 text-sm font-semibold hover:border-orange-400 hover:text-orange-700 md:inline-flex" style="border-color:var(--line)">Login Admin</a>
                @endauth
                <button type="button" @click="mobileOpen = !mobileOpen" class="rounded-lg border p-2 md:hidden" style="border-color:var(--line)" aria-label="Buka navigasi">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path x-show="mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="mobileOpen" x-cloak x-transition class="border-t px-5 py-3 md:hidden" style="border-color:var(--line)">
            <a href="{{ route('home') }}" class="block py-2 text-sm font-semibold">Beranda</a>
            <a href="{{ route('blog.index') }}" class="block py-2 text-sm font-semibold">Artikel</a>
            <div class="my-2 border-t pt-3" style="border-color:var(--line)">
                <p class="mb-2 text-[10px] font-bold uppercase tracking-[.14em] text-slate-500">Jelajahi kategori</p>
                <div class="flex flex-wrap gap-2">
                    @foreach(['HP', 'Laptop', 'Wearables', 'Accessories'] as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}" class="rounded-full border px-3 py-1.5 text-xs font-semibold" style="border-color:var(--line)">{{ $category }}</a>
                    @endforeach
                </div>
            </div>
            @auth
                <a href="{{ route('admin.blogs.index') }}" class="block py-2 text-sm font-semibold">Dashboard</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="py-2 text-sm font-semibold text-red-700">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="block py-2 text-sm font-semibold">Login Admin</a>
            @endauth
        </div>
    </nav>

    <main>
        @if(session('success'))
            <div x-data="{ show:true }" x-show="show" x-cloak x-transition x-init="setTimeout(() => show = false, 4000)"
                 class="fixed right-5 top-20 z-50 flex items-center gap-3 rounded-lg border border-emerald-200 bg-white px-4 py-3 text-sm text-emerald-700 shadow-lg">
                <span>{{ session('success') }}</span>
                <button type="button" @click="show = false" aria-label="Tutup">×</button>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer mt-16">
        <div class="mx-auto grid max-w-6xl gap-7 px-5 py-9 sm:grid-cols-2 sm:items-end lg:px-8">
            <div>
                <a href="{{ route('home') }}" class="text-lg font-extrabold">Gadget<span style="color:var(--brand)">Hub</span></a>
                <p class="mt-2 max-w-md text-sm leading-6 text-slate-500">Ulasan independen dan panduan praktis untuk membantu Anda memilih teknologi yang benar-benar cocok.</p>
            </div>
            <div class="sm:text-right">
                <a href="{{ route('blog.index') }}" class="text-sm font-semibold hover:text-orange-700">Jelajahi artikel</a>
                <p class="mt-3 text-xs text-slate-500">&copy; {{ date('Y') }} GadgetHub. Dibuat untuk pengguna gadget.</p>
            </div>
        </div>
    </footer>
    @stack('scripts')
</body>
</html>
