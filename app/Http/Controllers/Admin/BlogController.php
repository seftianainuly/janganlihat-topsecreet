<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display listing of all blog posts.
     */
    public function index(Request $request): View
    {
        $query = Blog::with('author')->latest();

        if ($request->filled('search')) {
            $query->search($request->input('search'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        $blogs = $query->paginate(10)->withQueryString();

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show form for creating a new blog post.
     */
    public function create(): View
    {
        $categories = ['HP', 'Laptop', 'Wearables', 'Accessories'];

        return view('admin.blogs.form', compact('categories'));
    }

    /**
     * Store a newly created blog post.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'required|string|unique:blogs,slug|max:255',
            'category'  => 'required|string|max:100',
            'excerpt'   => 'nullable|string|max:500',
            'content'   => 'required|string',
            'status'    => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        Blog::create([
            'user_id'   => Auth::id(),
            'title'     => $validated['title'],
            'slug'      => $validated['slug'],
            'category'  => $validated['category'],
            'excerpt'   => $validated['excerpt'],
            'content'   => $validated['content'],
            'status'    => $validated['status'],
            'thumbnail' => $thumbnailPath,
            'read_time' => Blog::estimateReadTime($validated['content']),
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil ditambahkan!');
    }

    /**
     * Show form for editing an existing blog post.
     */
    public function edit(Blog $blog): View
    {
        $categories = ['HP', 'Laptop', 'Wearables', 'Accessories'];

        return view('admin.blogs.form', compact('blog', 'categories'));
    }

    /**
     * Update an existing blog post.
     */
    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'title'     => 'required|string|max:255',
            'slug'      => 'required|string|max:255|unique:blogs,slug,' . $blog->id,
            'category'  => 'required|string|max:100',
            'excerpt'   => 'nullable|string|max:500',
            'content'   => 'required|string',
            'status'    => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $thumbnailPath = $blog->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($blog->thumbnail) {
                Storage::disk('public')->delete($blog->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
        }

        $blog->update([
            'title'     => $validated['title'],
            'slug'      => $validated['slug'],
            'category'  => $validated['category'],
            'excerpt'   => $validated['excerpt'],
            'content'   => $validated['content'],
            'status'    => $validated['status'],
            'thumbnail' => $thumbnailPath,
            'read_time' => Blog::estimateReadTime($validated['content']),
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Delete a blog post and its thumbnail.
     */
    public function destroy(Blog $blog): RedirectResponse
    {
        if ($blog->thumbnail) {
            Storage::disk('public')->delete($blog->thumbnail);
        }

        $blog->delete();

        return redirect()->route('admin.blogs.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Toggle the publish/draft status of a blog post (AJAX-friendly).
     */
    public function toggleStatus(Blog $blog): RedirectResponse
    {
        $blog->update([
            'status' => $blog->status === 'published' ? 'draft' : 'published',
        ]);

        $label = $blog->status === 'published' ? 'dipublikasikan' : 'dijadikan draft';

        return redirect()->back()->with('success', "Artikel berhasil {$label}!");
    }
}

