@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex justify-between flex-1 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl bg-white text-slate-400 text-sm cursor-not-allowed">← Sebelumnya</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:text-orange-700 hover:border-orange-300 text-sm transition-all">← Sebelumnya</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:text-orange-700 hover:border-orange-300 text-sm transition-all">Berikutnya →</a>
            @else
                <span class="px-4 py-2 rounded-xl bg-white text-slate-400 text-sm cursor-not-allowed">Berikutnya →</span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-slate-500">
                    Menampilkan <span class="text-white font-semibold">{{ $paginator->firstItem() }}</span>
                    –
                    <span class="text-white font-semibold">{{ $paginator->lastItem() }}</span>
                    dari                     <span class="text-orange-700 font-semibold">{{ $paginator->total() }}</span> artikel
                </p>
            </div>

            <div>
                <span class="relative z-0 inline-flex gap-1">
                    {{-- Previous --}}
                    @if ($paginator->onFirstPage())
                        <span class="px-3 py-2 rounded-lg bg-white text-slate-400 text-sm cursor-not-allowed">←</span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-600 hover:text-orange-700 hover:border-orange-300 text-sm transition-all">←</a>
                    @endif

                    {{-- Page numbers --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="px-3 py-2 rounded-lg text-slate-600 text-sm">{{ $element }}</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span class="px-3 py-2 rounded-lg text-sm font-semibold text-white"
                                          style="background:#e86c43;">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-600 hover:text-orange-700 hover:border-orange-300 text-sm transition-all">{{ $page }}</a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-2 rounded-lg border border-slate-200 text-slate-600 hover:text-orange-700 hover:border-orange-300 text-sm transition-all">→</a>
                    @else
                        <span class="px-3 py-2 rounded-lg bg-white text-slate-400 text-sm cursor-not-allowed">→</span>
                    @endif
                </span>
            </div>
        </div>
    </nav>
@endif
