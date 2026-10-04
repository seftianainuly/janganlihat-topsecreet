@extends('layouts.app')

@section('title', $blog->title . ' — GadgetHub')

@section('content')
<div class="article-page min-h-screen" x-data="articlePage()">

    {{-- ===== ARTICLE HERO ===== --}}
    <div class="article-banner relative overflow-hidden">
        {{-- Hero bg --}}
        <div class="article-banner-bg absolute inset-0 z-10"></div>
        @if($blog->thumbnail)
            <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}"
                 onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'"
                 class="article-banner-image absolute inset-0 w-full h-full object-cover">
        @else
            <div class="absolute inset-0"></div>
        @endif

        <div class="article-heading relative z-20 max-w-4xl mx-auto px-4 sm:px-6 pt-14 pb-12">

            {{-- Breadcrumb --}}
            <nav class="flex items-center justify-center gap-2 text-xs text-slate-500 mb-6">
                <a href="{{ route('home') }}" class="hover:text-cyan-400 transition-colors">Home</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-cyan-400 transition-colors">Blog</a>
                <span>/</span>
                <a href="{{ route('blog.index', ['category' => $blog->category]) }}" class="hover:text-cyan-400 transition-colors">{{ $blog->category }}</a>
            </nav>

            <span class="category-pill mb-4 inline-block">{{ $blog->category }}</span>

            <h1 class="font-orbitron font-bold text-2xl sm:text-3xl md:text-4xl text-white leading-tight mb-6">
                {{ $blog->title }}
            </h1>

            {{-- Meta --}}
            <div class="flex flex-wrap items-center justify-center gap-4 text-sm text-slate-400">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-linear-to-br from-indigo-500 to-violet-500 flex items-center justify-center shrink-0">
                        <span class="text-white text-xs font-bold">{{ strtoupper(substr($blog->author->name, 0, 1)) }}</span>
                    </div>
                    <span>{{ $blog->author->name }}</span>
                </div>
                <span>·</span>
                <span>{{ $blog->formatted_date }}</span>
                <span>·</span>
                <span class="flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $blog->read_time }} menit baca
                </span>
            </div>
        </div>
    </div>

    {{-- ===== ARTICLE BODY ===== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-20">
        <div class="flex gap-8 lg:gap-12">

            {{-- ===== TABLE OF CONTENTS (Sticky) ===== --}}
            <aside class="hidden lg:block w-64 shrink-0">
                <div class="sticky top-24">
                    <div class="glass-card rounded-2xl p-5 border border-slate-800">
                        <h3 class="font-orbitron font-bold text-xs tracking-widest text-cyan-400 uppercase mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h12M4 18h8"/>
                            </svg>
                            Daftar Isi
                        </h3>
                        <template x-if="headings.length === 0">
                            <p class="text-xs text-slate-500 italic">Tidak ada heading ditemukan.</p>
                        </template>
                        <nav class="space-y-1">
                            <template x-for="(h, i) in headings" :key="i">
                                <a :href="'#' + h.id"
                                   @click.prevent="scrollToHeading(h.id)"
                                   :class="{ 'text-cyan-400 border-l-2 border-cyan-400 pl-2': activeHeading === h.id, 'text-slate-500 hover:text-slate-300 pl-2': activeHeading !== h.id }"
                                   class="block text-xs transition-all py-1 leading-relaxed" x-text="h.text"></a>
                            </template>
                        </nav>
                    </div>

                    {{-- Share --}}
                    <div class="glass-card rounded-2xl p-5 border border-slate-800 mt-4">
                        <h3 class="font-orbitron font-bold text-xs tracking-widest text-cyan-400 uppercase mb-3">Share</h3>
                        <div class="flex gap-2">
                            <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="flex-1 py-2 rounded-lg glass-card text-center text-xs text-slate-400 hover:text-cyan-400 hover:border-cyan-400/50 transition-all">𝕏</a>
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                               target="_blank" rel="noopener noreferrer"
                               class="flex-1 py-2 rounded-lg glass-card text-center text-xs text-slate-400 hover:text-cyan-400 hover:border-cyan-400/50 transition-all">FB</a>
                            <button @click="copyLink()"
                                    class="flex-1 py-2 rounded-lg glass-card text-center text-xs transition-all"
                                    :class="copied ? 'text-emerald-400 border-emerald-400/50' : 'text-slate-400 hover:text-cyan-400 hover:border-cyan-400/50'"
                                    x-text="copied ? 'Copied!' : 'Copy'"></button>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- ===== ARTICLE CONTENT ===== --}}
            <div class="flex-1 min-w-0">

                {{-- Thumbnail --}}
                @if($blog->thumbnail)
                    <div class="rounded-2xl overflow-hidden mb-8 aspect-video">
                        <img src="{{ $blog->thumbnail_url }}" alt="{{ $blog->title }}"
                             onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'"
                             class="w-full h-full object-cover">
                    </div>
                @endif

                {{-- Excerpt --}}
                @if($blog->excerpt)
                    <div class="glass-card rounded-xl p-5 border-l-4 border-cyan-400 mb-8">
                        <p class="text-slate-300 leading-relaxed italic">{{ $blog->excerpt }}</p>
                    </div>
                @endif

                {{-- Article content --}}
                <div id="article-content" class="article-content prose prose-lg max-w-none">
                    {!! $blog->content !!}
                </div>

                {{-- Tags / category --}}
                <div class="mt-10 pt-6 border-t border-slate-800 flex flex-wrap gap-3">
                    <span class="text-sm text-slate-500">Kategori:</span>
                    <a href="{{ route('blog.index', ['category' => $blog->category]) }}"
                       class="category-pill">{{ $blog->category }}</a>
                </div>

                {{-- Mobile Share --}}
                <div class="mt-6 lg:hidden">
                    <p class="text-xs text-slate-500 mb-3 font-semibold uppercase tracking-wider">Bagikan Artikel:</p>
                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn-outline text-sm py-2 px-4">Share 𝕏</a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" rel="noopener noreferrer" class="btn-outline text-sm py-2 px-4">Share FB</a>
                        <button @click="copyLink()"
                                class="btn-outline text-sm py-2 px-4"
                                x-text="copied ? '✓ Copied!' : 'Copy Link'"></button>
                    </div>
                </div>

                {{-- ===== COMMENTS ===== --}}
                <div class="mt-12" x-data="commentSystem()">
                    <h2 class="font-orbitron font-bold text-lg text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Komentar (<span x-text="comments.length"></span>)
                    </h2>

                    {{-- Comment Form --}}
                    <div class="glass-card rounded-2xl p-6 mb-6 border border-slate-800">
                        <h3 class="font-semibold text-white text-sm mb-4">Tinggalkan Komentar</h3>
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <input type="text" x-model="newComment.name" placeholder="Nama Anda *"
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition-colors">
                                <input type="email" x-model="newComment.email" placeholder="Email (tidak ditampilkan)"
                                       class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition-colors">
                            </div>
                            <textarea x-model="newComment.body" placeholder="Tulis komentar Anda..." rows="4"
                                      class="w-full px-4 py-2.5 rounded-xl bg-slate-800 border border-slate-700 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-cyan-400 transition-colors resize-none"></textarea>
                            <div class="flex items-center gap-3">
                                <button @click="submitComment()" class="btn-primary text-sm py-2.5 px-6">
                                    Kirim Komentar →
                                </button>
                                <p x-show="commentError" x-text="commentError" class="text-red-400 text-sm" x-cloak></p>
                            </div>
                        </div>
                    </div>

                    {{-- Comments List --}}
                    <div class="space-y-4">
                        <template x-for="(comment, i) in comments" :key="i">
                            <div class="glass-card rounded-xl p-5 border border-slate-800 flex gap-4">
                                <div class="w-9 h-9 rounded-full bg-linear-to-br from-indigo-500 to-violet-500 flex items-center justify-center shrink-0">
                                    <span class="text-white text-xs font-bold" x-text="comment.name.charAt(0).toUpperCase()"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-semibold text-white text-sm" x-text="comment.name"></span>
                                        <span class="text-xs text-slate-500" x-text="comment.time"></span>
                                    </div>
                                    <p class="text-slate-400 text-sm leading-relaxed" x-text="comment.body"></p>
                                </div>
                            </div>
                        </template>

                        <template x-if="comments.length === 0">
                            <div class="text-center py-10 glass-card rounded-xl border border-slate-800">
                                <span class="text-4xl mb-3 block">💬</span>
                                <p class="text-slate-500 text-sm">Belum ada komentar. Jadilah yang pertama!</p>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- ===== RELATED ARTICLES ===== --}}
                @if($relatedBlogs->isNotEmpty())
                    <div class="mt-14 pt-10 border-t border-slate-800">
                        <h2 class="font-orbitron font-bold text-lg text-white mb-6">
                            Artikel <span class="gradient-text">Terkait</span>
                        </h2>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                            @foreach($relatedBlogs as $related)
                                <a href="{{ route('blog.show', $related->slug) }}"
                                   class="group glass-card rounded-xl overflow-hidden hover:border-cyan-400/40 transition-all">
                                    <div class="aspect-video overflow-hidden">
                                        <img src="{{ $related->thumbnail_url }}" alt="{{ $related->title }}"
                                             onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="p-4">
                                        <span class="category-pill text-xs mb-2 inline-block">{{ $related->category }}</span>
                                        <h3 class="text-sm font-semibold text-white group-hover:text-cyan-400 transition-colors leading-snug line-clamp-2">
                                            {{ $related->title }}
                                        </h3>
                                        <p class="text-xs text-slate-500 mt-2">{{ $related->read_time }} min baca</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function articlePage() {
    return {
        headings: [],
        activeHeading: null,
        copied: false,

        init() {
            this.$nextTick(() => {
                const content = document.getElementById('article-content');
                if (content) {
                    const hs = content.querySelectorAll('h2, h3, h4');
                    hs.forEach((h, i) => {
                        if (!h.id) h.id = 'heading-' + i;
                        this.headings.push({ id: h.id, text: h.textContent.trim(), level: h.tagName });
                    });
                }
                this.setupScrollSpy();
            });
        },

        setupScrollSpy() {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        this.activeHeading = entry.target.id;
                    }
                });
            }, { rootMargin: '-80px 0px -60% 0px', threshold: 0 });

            this.headings.forEach(h => {
                const el = document.getElementById(h.id);
                if (el) observer.observe(el);
            });
        },

        scrollToHeading(id) {
            const el = document.getElementById(id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                window.scrollBy(0, -90);
            }
        },

        copyLink() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                this.copied = true;
                setTimeout(() => this.copied = false, 2000);
            });
        },
    }
}

function commentSystem() {
    return {
        comments: [],
        newComment: { name: '', email: '', body: '' },
        commentError: '',

        submitComment() {
            if (!this.newComment.name.trim()) {
                this.commentError = 'Nama tidak boleh kosong.'; return;
            }
            if (!this.newComment.body.trim()) {
                this.commentError = 'Komentar tidak boleh kosong.'; return;
            }
            this.commentError = '';
            this.comments.unshift({
                name: this.newComment.name.trim(),
                body: this.newComment.body.trim(),
                time: 'Baru saja',
            });
            this.newComment = { name: '', email: '', body: '' };
        }
    }
}
</script>
@endpush

@push('styles')
<style>
    .article-banner-bg { background:linear-gradient(180deg,#eeeee7 0%,var(--page) 100%); }
    .article-banner-image { opacity:.08; }
    .article-heading { text-align:left; }
    .article-heading nav { justify-content:flex-start; }
    .article-heading h1 { max-width:48rem; font-family:'Manrope',sans-serif; font-size:clamp(2rem,4vw,3rem); letter-spacing:-.04em; }
    .article-heading .category-pill { margin-bottom:1rem; }
    .article-page .glass-card { border-radius:.8rem; }
    .article-page .btn-outline { border-color:var(--line); }
    .article-page .article-content { color:var(--ink); }
    .article-page .text-cyan-400,.article-page .hover\:text-cyan-400:hover { color:#c9512d !important; }
    .article-page .border-cyan-400 { border-color:#e86c43 !important; }
    .article-page .border-l-4 { border-left-color:#e86c43 !important; }
    .article-page .bg-cyan-400,.article-page .bg-cyan-500 { background:#e86c43 !important; }
    .article-page .bg-linear-to-br { background:linear-gradient(135deg,#e86c43,#bd4f2d); }
    .article-page .category-pill { background:#fff0e8; color:#a34426; }
    .article-page .prose h2,.article-page .prose h3 { color:#244e42; }
    .article-page input,.article-page textarea { color:#202820 !important; background:#fff !important; border-color:#e6e6de !important; }
    .article-page input:focus,.article-page textarea:focus { border-color:#e86c43 !important; }
    .article-page .group:hover h3 { color:#c9512d; }
    .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush
