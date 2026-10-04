@extends('layouts.app')

@section('title', 'Blog — GadgetHub')

@section('content')
<div class="blog-index min-h-screen py-12 px-4 sm:px-6">
    <div class="max-w-7xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-9">
            <p class="mb-2 text-sm font-semibold uppercase tracking-wider text-indigo-600">Artikel & ulasan</p>
            <h1 class="text-3xl font-bold tracking-tight md:text-4xl">Wawasan teknologi</h1>
            <p class="mt-3 max-w-xl text-slate-500">Temukan ulasan gadget terpercaya, tips praktis, dan berita teknologi terbaru.</p>
        </div>

        {{-- Search & Filter --}}
        <div class="glass-card rounded-xl p-4 mb-7">
            <form method="GET" action="{{ route('blog.index') }}"
                x-data="{ search: '{{ $search ?? '' }}', category: '{{ $category ?? '' }}' }">

                <div class="flex flex-col md:flex-row gap-3">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text"
                            name="search"
                            x-model="search"
                            placeholder="Cari artikel..."
                            class="w-full pl-10 pr-4 py-2.5 border rounded-lg text-sm focus:outline-none focus:border-indigo-400 transition-colors"
                            style="background:var(--surface);color:var(--ink);border-color:var(--line)">
                    </div>

                    {{-- Category Filter --}}
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('blog.index', array_filter(['search' => $search])) }}"
                           class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ !$category ? 'filter-active' : 'glass-card text-slate-400 hover:text-indigo-600 hover:border-indigo-300' }}">
                            Semua
                        </a>
                        @foreach($categories as $cat)
                            <a href="{{ route('blog.index', array_filter(['category' => $cat, 'search' => $search])) }}"
                               class="px-4 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $category === $cat ? 'filter-active' : 'glass-card text-slate-400 hover:text-indigo-600 hover:border-indigo-300' }}">
                                {{ $cat }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Search Button --}}
                    <button type="submit" class="btn-primary px-5 py-2.5 whitespace-nowrap">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                </div>
            </form>
        </div>

        {{-- Result info --}}
        @if($search || $category)
            <div class="flex items-center justify-between mb-6">
                <p class="text-sm text-slate-400">
                    Menampilkan <span class="text-white font-semibold">{{ $blogs->total() }}</span> artikel
                    @if($search) untuk "<span class="text-cyan-400">{{ $search }}</span>" @endif
                    @if($category) dalam kategori "<span class="text-cyan-400">{{ $category }}</span>" @endif
                </p>
                <a href="{{ route('blog.index') }}" class="text-sm text-slate-500 hover:text-cyan-400 transition-colors">
                    ✕ Reset Filter
                </a>
            </div>
        @endif

        {{-- Blog Grid --}}
        @if($blogs->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
                @foreach($blogs as $blog)
                    <article class="group glass-card rounded-2xl overflow-hidden flex flex-col hover:border-cyan-400/40 transition-all duration-300 hover:-translate-y-1">

                        {{-- Thumbnail --}}
                        <a href="{{ route('blog.show', $blog->slug) }}" class="relative overflow-hidden aspect-video block">
                            <img src="{{ $blog->thumbnail_url }}"
                                onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'"
                                alt="{{ $blog->title }}"
                                loading="lazy"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            <div class="absolute inset-0 bg-linear-to-t from-slate-950/70 to-transparent"></div>
                            <span class="absolute top-3 left-3 category-pill">{{ $blog->category }}</span>
                        </a>

                        {{-- Content --}}
                        <div class="p-5 flex flex-col flex-1">
                            <h2 class="font-bold text-base text-white leading-snug mb-2 group-hover:text-orange-700 transition-colors line-clamp-2">
                                <a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a>
                            </h2>
                            <p class="text-sm text-slate-400 line-clamp-2 mb-4 flex-1">{{ $blog->excerpt }}</p>

                            <div class="flex items-center justify-between mt-auto pt-3 border-t border-slate-800/80">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-linear-to-br from-cyan-400 to-violet-500 flex items-center justify-center shrink-0">
                                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($blog->author->name, 0, 1)) }}</span>
                                    </div>
                                    <span class="text-xs text-slate-500 truncate max-w-24">{{ $blog->author->name }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-xs text-slate-500 shrink-0">
                                    <span>{{ $blog->formatted_date }}</span>
                                    <span>·</span>
                                    <span>{{ $blog->read_time }}m</span>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="flex justify-center">
                {{ $blogs->links('vendor.pagination.cyber') }}
            </div>

        @else
            <div class="text-center py-20 glass-card rounded-2xl">
                <span class="text-6xl mb-4 block">🔍</span>
                <h3 class="font-orbitron font-bold text-xl text-white mb-2">Artikel tidak ditemukan</h3>
                <p class="text-slate-400 text-sm mb-6">Coba ubah kata kunci atau filter kategori Anda.</p>
                <a href="{{ route('blog.index') }}" class="btn-primary">Lihat Semua Artikel</a>
            </div>
        @endif

    </div>
</div>
@endsection

@push('styles')
<style>
    .blog-index .filter-active { background:#244e42; color:#fff !important; }
    .blog-index .glass-card { border-radius:.8rem; }
    .blog-index article { border-radius:.85rem; border-color:#e6e6de; }
    .blog-index article h2 a:hover { color:#c9512d; }
    .blog-index article .category-pill { background:#fff0e8; color:#a34426; }
    .blog-index article .rounded-full.bg-linear-to-br { background:linear-gradient(135deg,#e86c43,#244e42); }
    .blog-index .bg-linear-to-t { background-image:linear-gradient(to top,rgb(32 40 32 / 16%),transparent); }
    .blog-index .text-cyan-400,.blog-index .hover\:text-cyan-400:hover { color:#c9512d !important; }
    .blog-index .border-cyan-400\/40 { border-color:#efc2ae !important; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush
