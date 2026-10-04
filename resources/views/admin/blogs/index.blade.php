@extends('layouts.admin')

@section('title', 'Manajemen Blog')
@section('page-title', 'Manajemen Blog')
@section('page-subtitle', 'Kelola semua artikel GadgetHub')

@section('content')
<div x-data="blogAdmin()">

    {{-- Header Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            @php
                $total = \App\Models\Blog::count();
                $published = \App\Models\Blog::published()->count();
                $draft = $total - $published;
            @endphp
            <div class="flex items-center gap-3">
                <span class="text-sm text-slate-400">Total: <strong class="text-white">{{ $total }}</strong></span>
                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                <span class="text-sm text-emerald-400">Published: <strong>{{ $published }}</strong></span>
                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                <span class="text-sm text-slate-500">Draft: <strong>{{ $draft }}</strong></span>
            </div>
        </div>
        <a href="{{ route('admin.blogs.create') }}" class="btn-primary">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Artikel
        </a>
    </div>

    {{-- Filter & Search --}}
    <div class="admin-card p-4 mb-6">
        <form method="GET" action="{{ route('admin.blogs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari artikel..."
                       class="form-input pl-9">
            </div>
            <select name="status" class="form-input sm:w-40">
                <option value="">Semua Status</option>
                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft"     {{ request('status') === 'draft'     ? 'selected' : '' }}>Draft</option>
            </select>
            <button type="submit" class="btn-primary px-5 whitespace-nowrap">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.blogs.index') }}" class="flex items-center justify-center px-4 rounded-lg border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 text-sm transition-all whitespace-nowrap">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="admin-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-12">#</th>
                        <th>Judul Artikel</th>
                        <th class="hidden xl:table-cell">Kategori</th>
                        <th class="hidden 2xl:table-cell">Penulis</th>
                        <th class="hidden sm:table-cell">Status</th>
                        <th class="hidden 2xl:table-cell">Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($blogs as $blog)
                        <tr>
                            <td class="text-slate-600 text-xs">{{ $loop->iteration + ($blogs->currentPage() - 1) * $blogs->perPage() }}</td>

                            {{-- Title --}}
                            <td>
                                <div class="flex items-center gap-3">
                                    {{-- Thumbnail mini --}}
                                    <div class="w-10 h-10 rounded-lg overflow-hidden shrink-0 hidden sm:block">
                                        <img src="{{ $blog->thumbnail_url }}"
                                             onerror="this.onerror=null;this.src='{{ asset('images/gadget-placeholder.svg') }}'"
                                             alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="min-w-0">
                                        <a href="{{ route('blog.show', $blog->slug) }}" target="_blank"
                                           class="font-semibold text-white hover:text-cyan-400 transition-colors block truncate max-w-xs text-sm">
                                            {{ $blog->title }}
                                        </a>
                                        <span class="text-xs text-slate-500 font-mono">/{{ $blog->slug }}</span>
                                    </div>
                                </div>
                            </td>

                            {{-- Category --}}
                            <td class="hidden xl:table-cell">
                                <span class="category-pill text-xs">{{ $blog->category }}</span>
                            </td>

                            {{-- Author --}}
                            <td class="hidden 2xl:table-cell text-slate-400 text-sm">{{ $blog->author->name }}</td>

                            {{-- Status --}}
                            <td class="hidden sm:table-cell">
                                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $blog->status === 'published' ? 'badge-published' : 'badge-draft' }}">
                                    {{ $blog->status === 'published' ? '● Published' : '○ Draft' }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="hidden 2xl:table-cell text-slate-500 text-xs">{{ $blog->formatted_date }}</td>

                            {{-- Actions --}}
                            <td>
                                <div class="flex items-center justify-end gap-2 flex-wrap">
                                    {{-- Toggle Status --}}
                                    <form action="{{ route('admin.blogs.toggle-status', $blog) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit"
                                                class="{{ $blog->status === 'published' ? 'btn-toggle-draft' : 'btn-toggle-pub' }}"
                                                title="{{ $blog->status === 'published' ? 'Jadikan Draft' : 'Publish' }}">
                                            {{ $blog->status === 'published' ? '↓ Draft' : '↑ Publish' }}
                                        </button>
                                    </form>

                                    {{-- Edit --}}
                                    <a href="{{ route('admin.blogs.edit', $blog) }}" class="btn-edit">
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <button @click="confirmDelete({{ $blog->id }}, {{ Illuminate\Support\Js::from($blog->title) }})"
                                            class="btn-danger">
                                        Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16">
                                <div class="flex flex-col items-center gap-3">
                                    <span class="text-5xl">📝</span>
                                    <p class="text-slate-400 font-semibold">Belum ada artikel</p>
                                    <a href="{{ route('admin.blogs.create') }}" class="btn-primary text-sm">
                                        + Tambah Artikel Pertama
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($blogs->hasPages())
            <div class="px-4 py-4 border-t border-slate-800">
                {{ $blogs->withQueryString()->links() }}
            </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-show="showModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center px-4"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" @click="showModal = false"></div>

        {{-- Modal --}}
        <div class="relative admin-card rounded-2xl p-6 max-w-sm w-full border border-red-500/30"
             style="box-shadow: 0 0 40px rgba(239,68,68,0.2);"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">

            <div class="text-center mb-5">
                <span class="text-5xl block mb-3">🗑️</span>
                <h3 class="font-orbitron font-bold text-lg text-white mb-2">Hapus Artikel?</h3>
                <p class="text-slate-400 text-sm">
                    Artikel "<strong class="text-white" x-text="deleteTitle"></strong>" akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form :action="deleteAction" method="POST" class="flex gap-3">
                @csrf
                @method('DELETE')
                <button type="button" @click="showModal = false"
                        class="flex-1 py-2.5 rounded-xl border border-slate-700 text-slate-400 hover:text-white hover:border-slate-600 text-sm font-semibold transition-all">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 py-2.5 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300 hover:bg-red-500/30 text-sm font-semibold transition-all">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
function blogAdmin() {
    return {
        showModal: false,
        deleteTitle: '',
        deleteAction: '',

        confirmDelete(id, title) {
            this.deleteTitle  = title;
            this.deleteAction = `/admin/blogs/${id}`;
            this.showModal    = true;
        }
    }
}
</script>
@endpush
