@extends('layouts.app')

@section('title', 'GadgetHub — Catatan Teknologi')

@section('content')
<section class="home-intro">
    <div class="mx-auto grid max-w-6xl items-center gap-12 px-5 py-16 sm:py-20 lg:grid-cols-[1.05fr_.95fr] lg:px-8 lg:py-24">
        <div>
            <p class="eyebrow"><span class="eyebrow-mark"></span> CATATAN GADGET <span class="text-slate-400">/ VOL. 01</span></p>
            <h1 class="mt-7 max-w-2xl text-4xl font-extrabold leading-[1.08] tracking-tight sm:text-5xl lg:text-[3.65rem]">
                Biar spesifikasi bicara. <span class="headline-accent">Biar pengalaman yang memutuskan.</span>
            </h1>
            <p class="mt-6 max-w-xl text-base leading-7 text-slate-500 sm:text-lg">
                Ulasan gadget dari sudut pandang pemakaian sehari-hari. Apa yang terasa, apa yang penting, dan mana yang layak dibeli.
            </p>
            <div class="mt-8 flex flex-wrap items-center gap-3">
                <a href="{{ route('blog.index') }}" class="btn-primary">Baca catatan terbaru <span aria-hidden="true">↗</span></a>
                <a href="#pilihan-editor" class="home-text-link">Lihat pilihan editor <span aria-hidden="true">↓</span></a>
            </div>
            <div class="mt-12 flex flex-wrap gap-x-8 gap-y-3 border-t pt-5 text-sm text-slate-500" style="border-color:var(--line)">
                <span><strong class="text-slate-800">{{ $latestBlogs->count() }}+</strong> ulasan terkurasi</span>
                <span><strong class="text-slate-800">{{ count($categories) }}</strong> kategori gadget</span>
                <span>Ditulis untuk pemakaian nyata</span>
            </div>
        </div>

        <div class="hero-still-life" aria-label="Ilustrasi orisinal aneka perangkat gadget">
            <span class="still-life-note">GADGET / FIELD NOTES</span>
            <span class="still-life-index">01—04</span>
            <div class="device device-phone"><span></span><i></i><b></b></div>
            <div class="device device-watch"><span></span><i></i></div>
            <div class="device device-earbud device-earbud-left"></div>
            <div class="device device-earbud device-earbud-right"></div>
            <span class="still-life-caption">Pilihan tepat<br>dimulai dari informasi.</span>
            <span class="still-life-orbit orbit-one"></span>
            <span class="still-life-orbit orbit-two"></span>
        </div>
    </div>
</section>

<section class="mx-auto max-w-6xl px-5 py-14 lg:px-8">
    <div class="category-heading">
        <div>
            <p class="eyebrow">TELUSURI BERDASARKAN PERANGKAT</p>
            <h2 class="mt-2 text-2xl font-bold tracking-tight">Apa yang sedang Anda cari?</h2>
        </div>
        <span class="hidden text-sm text-slate-500 md:block">Pilih topik, temukan jawabannya.</span>
    </div>
    <div class="category-list">
        @foreach($categories as $index => $category)
            <a href="{{ route('blog.index', ['category' => $category]) }}" class="category-row">
                <span class="category-number">0{{ $index + 1 }}</span>
                <span class="category-name">{{ $category }}</span>
                <span class="category-arrow" aria-hidden="true">↗</span>
            </a>
        @endforeach
    </div>
</section>

<section id="pilihan-editor" class="editor-section">
    <div class="mx-auto max-w-6xl px-5 py-16 lg:px-8">
        <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="eyebrow">DIPILIH OLEH EDITOR</p>
                <h2 class="mt-2 text-2xl font-bold tracking-tight sm:text-3xl">Bacaan yang layak dibuka</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="home-text-link">Semua artikel <span aria-hidden="true">↗</span></a>
        </div>

        @if($latestBlogs->isNotEmpty())
            <div class="editor-grid">
                @foreach($latestBlogs as $index => $blog)
                    <article class="editor-card {{ $index === 0 ? 'editor-card-featured' : '' }}">
                        <a href="{{ route('blog.show', $blog->slug) }}" class="editor-image-wrap">
                            <img src="{{ $blog->thumbnail_url }}" onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'" alt="{{ $blog->title }}" loading="lazy" class="editor-image">
                            <span class="editor-image-label">{{ $blog->category }}</span>
                        </a>
                        <div class="editor-card-body">
                            <div class="editor-meta"><span>{{ $blog->formatted_date }}</span><span aria-hidden="true">·</span><span>{{ $blog->read_time }} menit baca</span></div>
                            <h3><a href="{{ route('blog.show', $blog->slug) }}">{{ $blog->title }}</a></h3>
                            <p>{{ $blog->excerpt }}</p>
                            <div class="editor-author">
                                <span class="author-initial">{{ strtoupper(substr($blog->author->name, 0, 1)) }}</span>
                                <span>{{ $blog->author->name }}</span>
                                <a href="{{ route('blog.show', $blog->slug) }}" aria-label="Baca {{ $blog->title }}">→</a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed p-10 text-center text-slate-500" style="border-color:var(--line)">Belum ada artikel yang dipublikasikan.</div>
        @endif
    </div>
</section>

<section class="mx-auto max-w-6xl px-5 py-16 lg:px-8">
    <div class="newsletter-panel" x-data="{ email:'', sent:false }">
        <div>
            <p class="eyebrow">SURAT DARI GADGETHUB</p>
            <h2 class="mt-2 text-2xl font-bold tracking-tight">Pilihan bacaan, sesekali.</h2>
            <p class="mt-2 max-w-xl text-sm leading-6 text-slate-500">Ringkasan ulasan baru langsung ke email Anda. Tanpa spam, tanpa ribut.</p>
        </div>
        <form @submit.prevent="if ($refs.email.checkValidity()) { sent = true; email = '' }" class="newsletter-form">
            <label for="newsletter-email" class="mb-2 block text-sm font-semibold">Alamat email</label>
            <div class="flex flex-col gap-2 sm:flex-row">
                <input id="newsletter-email" x-ref="email" type="email" required x-model="email" placeholder="nama@email.com" class="newsletter-input">
                <button type="submit" class="btn-primary whitespace-nowrap">Ikut berlangganan</button>
            </div>
            <p x-show="sent" x-cloak role="status" class="mt-2 text-sm font-semibold text-emerald-700">Terima kasih, Anda sudah masuk daftar.</p>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
    .home-intro { overflow:hidden; background:#eeeee7; }
    .home-intro > div { position:relative; }
    .eyebrow { display:flex; align-items:center; gap:.55rem; color:#6d756b; font-size:.7rem; font-weight:800; letter-spacing:.13em; }
    .eyebrow-mark { width:.55rem; height:.55rem; border-radius:50%; background:#e86c43; }
    .headline-accent { color:#e86c43; }
    .home-text-link { display:inline-flex; align-items:center; gap:.5rem; color:#244e42; font-size:.9rem; font-weight:700; }
    .home-text-link:hover { color:#e86c43; }
    .hero-still-life { position:relative; min-height:390px; overflow:hidden; border-radius:1.2rem; background:#d4c9b6; isolation:isolate; }
    .hero-still-life::before { position:absolute; inset:0; z-index:-1; background:linear-gradient(135deg,#e6ddd0 0%,#cec3af 100%); content:""; }
    .still-life-note,.still-life-index,.still-life-caption { position:absolute; z-index:4; color:#465047; font-size:.65rem; font-weight:800; letter-spacing:.12em; }
    .still-life-note { top:1.2rem; left:1.3rem; }
    .still-life-index { top:1.2rem; right:1.3rem; }
    .still-life-caption { right:1.3rem; bottom:1.2rem; text-align:right; line-height:1.5; letter-spacing:.04em; }
    .device { position:absolute; z-index:2; box-shadow:0 28px 55px rgb(46 52 43 / 19%); }
    .device-phone { top:15%; left:34%; width:30%; height:61%; transform:rotate(-10deg); border:7px solid #303d36; border-radius:2rem; background:#293b37; }
    .device-phone::before { position:absolute; top:5%; left:36%; width:28%; height:3%; border-radius:5px; background:#18231f; content:""; }
    .device-phone span { position:absolute; inset:12% 6% 7%; border-radius:1.2rem; background:linear-gradient(150deg,#ef8056 0%,#df6540 43%,#f1bf7b 44%,#f6e7ca 100%); }
    .device-phone i { position:absolute; right:13%; bottom:17%; width:35%; height:35%; border:2px solid rgb(255 255 255 / 48%); border-radius:50%; }
    .device-phone b { position:absolute; right:24%; bottom:28%; width:12%; height:12%; border-radius:50%; background:#fff8e8; }
    .device-watch { bottom:13%; left:12%; width:24%; height:37%; transform:rotate(15deg); border:8px solid #46534a; border-radius:2.2rem; background:#38463d; }
    .device-watch::before,.device-watch::after { position:absolute; left:24%; width:52%; height:33%; border-radius:1rem; background:#59665a; content:""; }
    .device-watch::before { top:-29%; }
    .device-watch::after { bottom:-29%; }
    .device-watch span { position:absolute; z-index:1; inset:13%; border:2px solid #a9be8d; border-radius:1.4rem; background:#202e29; }
    .device-watch i { position:absolute; z-index:2; top:38%; left:28%; width:44%; height:24%; border-radius:50%; background:#a9be8d; }
    .device-earbud { width:16%; height:25%; border:7px solid #f7f2e7; border-radius:3rem 3rem 2rem 2rem; background:linear-gradient(145deg,#fffdf8,#d8d2c5); }
    .device-earbud::after { position:absolute; bottom:-35%; left:39%; width:22%; height:55%; border-radius:0 0 1rem 1rem; background:#eee9dd; content:""; }
    .device-earbud-left { top:21%; right:15%; transform:rotate(20deg); }
    .device-earbud-right { top:40%; right:10%; transform:rotate(37deg) scale(.78); }
    .still-life-orbit { position:absolute; z-index:1; border:1px solid rgb(255 255 255 / 40%); border-radius:50%; }
    .orbit-one { top:14%; left:20%; width:54%; height:75%; transform:rotate(25deg); }
    .orbit-two { top:24%; left:18%; width:64%; height:52%; transform:rotate(-35deg); }
    .category-heading { display:flex; align-items:end; justify-content:space-between; gap:1rem; margin-bottom:1.25rem; }
    .category-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); border-top:1px solid var(--line); }
    .category-row { display:flex; align-items:center; gap:1rem; min-height:4.5rem; padding:.8rem .5rem; border-bottom:1px solid var(--line); }
    .category-row:nth-child(odd) { margin-right:1.25rem; }
    .category-number { color:#a0a399; font-size:.7rem; font-weight:700; }
    .category-name { flex:1; font-size:1rem; font-weight:700; }
    .category-arrow { color:#e86c43; font-size:1.15rem; transition:transform .2s; }
    .category-row:hover .category-arrow { transform:translate(3px,-3px); }
    .editor-section { background:#eeeee7; }
    .editor-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:1.15rem; }
    .editor-card { overflow:hidden; border:1px solid #e3e2da; border-radius:.85rem; background:#fff; }
    .editor-card-featured { grid-column:span 2; display:grid; grid-template-columns:1.05fr .95fr; }
    .editor-image-wrap { position:relative; display:block; min-height:190px; overflow:hidden; background:#dedfd6; }
    .editor-card-featured .editor-image-wrap { min-height:100%; }
    .editor-image { width:100%; height:100%; min-height:190px; object-fit:cover; transition:transform .35s; }
    .editor-image-wrap:hover .editor-image { transform:scale(1.035); }
    .editor-image-label { position:absolute; top:.8rem; left:.8rem; padding:.35rem .65rem; border-radius:999px; background:#fff; color:#244e42; font-size:.68rem; font-weight:800; }
    .editor-card-body { display:flex; flex-direction:column; padding:1.15rem; }
    .editor-card-featured .editor-card-body { justify-content:center; padding:1.6rem; }
    .editor-meta { display:flex; gap:.5rem; color:#85897f; font-size:.7rem; }
    .editor-card h3 { margin-top:.65rem; font-size:1.05rem; font-weight:800; line-height:1.4; letter-spacing:-.02em; }
    .editor-card-featured h3 { font-size:1.45rem; }
    .editor-card h3 a:hover { color:#c9512d; }
    .editor-card-body > p { display:-webkit-box; overflow:hidden; margin-top:.5rem; color:#747b72; font-size:.82rem; line-height:1.6; -webkit-box-orient:vertical; -webkit-line-clamp:3; }
    .editor-author { display:flex; align-items:center; gap:.55rem; margin-top:auto; padding-top:1rem; color:#747b72; font-size:.72rem; }
    .author-initial { display:grid; width:1.7rem; height:1.7rem; place-items:center; border-radius:50%; background:#e8eee5; color:#244e42; font-size:.72rem; font-weight:800; }
    .editor-author a { margin-left:auto; color:#e86c43; font-size:1.1rem; font-weight:800; }
    .newsletter-panel { display:flex; flex-direction:column; align-items:flex-start; justify-content:space-between; gap:2rem; border:1px solid var(--line); border-radius:1rem; background:#fff; padding:2rem; }
    .newsletter-form { width:100%; max-width:30rem; }
    .newsletter-input { min-width:0; flex:1; border:1px solid var(--line); border-radius:.55rem; background:#fafaf8; padding:.75rem .9rem; color:var(--ink); outline:none; }
    .newsletter-input:focus { border-color:#e86c43; box-shadow:0 0 0 3px rgb(232 108 67 / 12%); }
    @media (min-width:768px) {
        .newsletter-panel { flex-direction:row; align-items:center; padding:2.5rem; }
    }
    @media (min-width:768px) {
        .home-intro > div { grid-template-columns:1.05fr .95fr; }
    }
    @media (max-width:767px) {
        .hero-still-life { min-height:330px; }
        .editor-grid { grid-template-columns:repeat(2,minmax(0,1fr)); }
        .editor-card-featured { grid-column:span 2; }
    }
    @media (max-width:520px) {
        .category-row:nth-child(odd) { margin-right:.65rem; }
        .category-name { font-size:.88rem; }
        .editor-grid { grid-template-columns:1fr; }
        .editor-card-featured { grid-column:auto; grid-template-columns:1fr; }
        .editor-card-featured .editor-image-wrap { min-height:200px; }
        .editor-card-featured h3 { font-size:1.15rem; }
    }
</style>
@endpush
