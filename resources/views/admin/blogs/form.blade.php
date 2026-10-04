@extends('layouts.admin')

@section('title', isset($blog) ? 'Edit Artikel' : 'Tambah Artikel')
@section('page-title', isset($blog) ? 'Edit Artikel' : 'Tambah Artikel Baru')
@section('page-subtitle', isset($blog) ? 'Perbarui konten artikel' : 'Buat artikel baru untuk GadgetHub')

@section('content')

<div x-data="blogForm($el.dataset.initialSlug, $el.dataset.initialTitle, $el.dataset.initialExcerpt)"
     data-initial-slug="{{ old('slug', $blog->slug ?? '') }}"
     data-initial-title="{{ old('title', $blog->title ?? '') }}"
     data-initial-excerpt="{{ old('excerpt', $blog->excerpt ?? '') }}"
     class="max-w-5xl mx-auto">

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-xs text-slate-500 mb-6">
        <a href="{{ route('admin.blogs.index') }}" class="hover:text-cyan-400 transition-colors">Blog</a>
        <span>/</span>
        <span class="text-slate-300">{{ isset($blog) ? 'Edit' : 'Tambah Baru' }}</span>
    </div>

    <form action="{{ isset($blog) ? route('admin.blogs.update', $blog) : route('admin.blogs.store') }}"
        method="POST" enctype="multipart/form-data" @submit="submitting = true">
        @csrf
        @if(isset($blog)) @method('PUT') @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===== LEFT COLUMN (Main content) ===== --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Title --}}
                <div class="admin-card rounded-2xl p-6">
                    <div class="mb-5">
                        <label class="form-label">Judul Artikel *</label>
                        <input type="text"
                            name="title"
                            x-model="title"
                            @input="generateSlug()"
                            value="{{ old('title', $blog->title ?? '') }}"
                            placeholder="Tulis judul artikel yang menarik..."
                            class="form-input {{ $errors->has('title') ? '!border-red-500' : '' }}">
                        @error('title')
                            <p class="text-red-400 text-xs mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Slug --}}
                    <div>
                        <label class="form-label">Slug URL *</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-500 font-mono">/blog/</span>
                            <input type="text"
                                name="slug"
                                x-model="slug"
                                value="{{ old('slug', $blog->slug ?? '') }}"
                                placeholder="judul-artikel-url"
                                class="form-input pl-14 font-mono text-sm {{ $errors->has('slug') ? '!border-red-500' : '' }}">
                        </div>
                        <p class="text-xs text-slate-600 mt-1">Auto-generate dari judul. Bisa diedit manual.</p>
                        @error('slug')
                            <p class="text-red-400 text-xs mt-1">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Excerpt --}}
                <div class="admin-card rounded-2xl p-6">
                    <label class="form-label">Ringkasan / Excerpt</label>
                    <textarea name="excerpt"
                            x-model="excerpt"
                            rows="3"
                            placeholder="Tulis ringkasan singkat artikel (maks 500 karakter)..."
                            maxlength="500"
                            class="form-input resize-none {{ $errors->has('excerpt') ? '!border-red-500' : '' }}">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
                    <div class="flex justify-between mt-1.5">
                        @error('excerpt')
                            <p class="text-red-400 text-xs">⚠ {{ $message }}</p>
                        @else
                            <span></span>
                        @enderror
                        <span class="text-xs text-slate-600" x-text="excerpt.length + '/500'"></span>
                    </div>
                </div>

                {{-- Content --}}
                <div class="admin-card rounded-2xl p-6">
                    <label class="form-label">Isi Konten Artikel *</label>
                    <p class="text-xs text-slate-500 mb-3">Gunakan tag HTML: &lt;h2&gt;, &lt;h3&gt;, &lt;p&gt;, &lt;ul&gt;, &lt;blockquote&gt;, &lt;strong&gt;, &lt;em&gt;</p>
                    <textarea name="content"
                            rows="20"
                            placeholder="<h2>Pendahuluan</h2>&#10;<p>Tulis konten artikel di sini...</p>"
                            class="form-input font-mono text-sm resize-y {{ $errors->has('content') ? '!border-red-500' : '' }}">{{ old('content', $blog->content ?? '') }}</textarea>
                    @error('content')
                        <p class="text-red-400 text-xs mt-1.5">⚠ {{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- ===== RIGHT COLUMN (Settings) ===== --}}
            <div class="space-y-5">

                {{-- Publish Settings --}}
                <div class="admin-card rounded-2xl p-6">
                    <h3 class="font-orbitron font-bold text-xs tracking-widest text-cyan-400 uppercase mb-4">Publikasi</h3>

                    {{-- Status --}}
                    <div class="mb-4">
                        <label class="form-label">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="draft" class="sr-only peer"
                                    {{ old('status', $blog->status ?? 'draft') === 'draft' ? 'checked' : '' }}>
                                <div class="py-2.5 px-3 rounded-lg border border-slate-700 text-center text-sm font-semibold text-slate-400
                                            peer-checked:border-slate-500 peer-checked:bg-slate-700 peer-checked:text-white transition-all">
                                    ○ Draft
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input type="radio" name="status" value="published" class="sr-only peer"
                                    {{ old('status', $blog->status ?? '') === 'published' ? 'checked' : '' }}>
                                <div class="py-2.5 px-3 rounded-lg border border-slate-700 text-center text-sm font-semibold text-slate-400
                                            peer-checked:border-emerald-500 peer-checked:bg-emerald-900/30 peer-checked:text-emerald-300 transition-all">
                                    ● Published
                                </div>
                            </label>
                        </div>
                        @error('status')
                            <p class="text-red-400 text-xs mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Category --}}
                    <div>
                        <label class="form-label">Kategori *</label>
                        <select name="category" class="form-input" style="padding-left: 0.875rem;">
                            <option value="" disabled {{ !old('category', $blog->category ?? '') ? 'selected' : '' }}>Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $blog->category ?? '') === $cat ? 'selected' : '' }}>
                                    {{ $cat }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="text-red-400 text-xs mt-1.5">⚠ {{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Thumbnail --}}
                <div class="admin-card rounded-2xl p-6">
                    <h3 class="font-orbitron font-bold text-xs tracking-widest text-cyan-400 uppercase mb-4">Thumbnail</h3>

                    {{-- Current thumbnail --}}
                    @if(isset($blog) && $blog->thumbnail)
                        <div class="mb-3 rounded-xl overflow-hidden aspect-video">
                            <img src="{{ $blog->thumbnail_url }}" alt="Current thumbnail"
                                class="w-full h-full object-cover">
                        </div>
                        <p class="text-xs text-slate-500 mb-3">Upload baru untuk mengganti gambar di atas.</p>
                    @endif

                    {{-- Upload area --}}
                    <div x-data="imageUpload()"
                        class="border-2 border-dashed border-slate-700 rounded-xl p-6 text-center cursor-pointer hover:border-cyan-400/50 transition-all"
                        @click="$refs.fileInput.click()"
                        @dragover.prevent @drop.prevent="handleDrop($event)">

                        <input type="file" name="thumbnail" accept="image/*"
                            class="hidden" x-ref="fileInput"
                            @change="handleFile($event)">

                        <template x-if="!previewUrl">
                            <div>
                                <svg class="w-10 h-10 text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-sm text-slate-500">Klik atau drag gambar ke sini</p>
                                <p class="text-xs text-slate-600 mt-1">JPG, PNG, WEBP · Max 2MB</p>
                            </div>
                        </template>

                        <template x-if="previewUrl">
                            <div class="relative">
                                <img :src="previewUrl" class="w-full rounded-lg object-cover max-h-40">
                                <button type="button" @click.stop="previewUrl = null; $refs.fileInput.value = ''"
                                        class="absolute top-2 right-2 w-6 h-6 bg-red-500 rounded-full text-white text-xs flex items-center justify-center hover:bg-red-400">✕</button>
                            </div>
                        </template>
                    </div>
                    @error('thumbnail')
                        <p class="text-red-400 text-xs mt-2">⚠ {{ $message }}</p>
                    @enderror
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3">
                    <button type="submit" :disabled="submitting" class="btn-primary w-full justify-center py-3">
                        <span x-show="!submitting">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            {{ isset($blog) ? 'Simpan Perubahan' : 'Publikasikan Artikel' }}
                        </span>
                        <span x-show="submitting" class="flex items-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </button>

                    <a href="{{ route('admin.blogs.index') }}"
                    class="block text-center py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 text-sm font-semibold transition-all">
                        Batal
                    </a>

                    @isset($blog)
                        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                           class="flex items-center justify-center gap-1.5 py-2.5 rounded-xl border border-cyan-400/30 text-cyan-400 hover:bg-cyan-400/10 text-sm font-semibold transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                            Preview Artikel
                        </a>
                    @endisset
                </div>

            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function blogForm(initialSlug, initialTitle, initialExcerpt) {
    return {
        title: initialTitle,
        slug: initialSlug,
        excerpt: initialExcerpt,
        submitting: false,

        generateSlug() {
            const slug = this.title
                .toLowerCase()
                .trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
            this.slug = slug;
        }
    }
}

function imageUpload() {
    return {
        previewUrl: null,

        handleFile(event) {
            const file = event.target.files[0];
            if (file) this.setPreview(file);
        },

        handleDrop(event) {
            const file = event.dataTransfer.files[0];
            if (file && file.type.startsWith('image/')) {
                this.$refs.fileInput.files = event.dataTransfer.files;
                this.setPreview(file);
            }
        },

        setPreview(file) {
            const reader = new FileReader();
            reader.onload = (e) => { this.previewUrl = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}
</script>
@endpush
