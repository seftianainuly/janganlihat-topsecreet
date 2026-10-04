<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Landing Page — shows latest published posts & hero.
     */
    public function landing(): View
    {
        $latestBlogs = Blog::published()
            ->with('author')
            ->latest()
            ->take(6)
            ->get();

        $categories = ['HP', 'Laptop', 'Wearables', 'Accessories'];

        return view('landing', compact('latestBlogs', 'categories'));
    }

    /**
     * Public Blog Index — paginated, searchable, filterable.
     */
    public function index(Request $request): View
    {
        $search   = $request->input('search');
        $category = $request->input('category');

        $query = Blog::published()->with('author')->latest();

        if ($search) {
            $query->search($search);
        }

        if ($category) {
            $query->byCategory($category);
        }

        $blogs      = $query->paginate(9)->withQueryString();
        $categories = Blog::getCategories();

        return view('blogs.index', compact('blogs', 'search', 'category', 'categories'));
    }

    /**
     * Public Blog Detail — show single published post.
     */
    public function show(string $slug): View
    {
        $blog = Blog::published()
            ->with('author')
            ->where('slug', $slug)
            ->firstOrFail();

        // Related posts — same category, different slug
        $relatedBlogs = Blog::published()
            ->where('category', $blog->category)
            ->where('slug', '!=', $slug)
            ->latest()
            ->take(3)
            ->get();

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }
}

