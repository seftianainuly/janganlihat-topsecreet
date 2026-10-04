<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'thumbnail',
        'excerpt',
        'content',
        'status',
        'read_time',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'read_time' => 'integer',
    ];

    // ==========================================
    // RELATIONSHIPS
    // ==========================================

    /**
     * Get the author of this blog post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ==========================================
    // QUERY SCOPES
    // ==========================================

    /**
     * Scope: only published posts.
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Scope: only published posts (legacy alias for active posts).
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->active();
    }

    /**
     * Scope: filter by category.
     */
    public function scopeByCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope: search by title or excerpt.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
                ->orWhere('excerpt', 'like', "%{$term}%");
        });
    }

    // ==========================================
    // ACCESSORS / HELPERS
    // ==========================================

    /**
     * Auto-generate a slug from title if slug is not provided.
     */
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = static::where('slug', 'like', "{$slug}%")->count();

        return $count > 0 ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get formatted published date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d M Y');
    }

    /**
     * Get thumbnail URL (public storage or fallback placeholder).
     */
    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            return asset('storage/'.$this->thumbnail);
        }

        return asset('images/gadget-placeholder.svg');
    }

    /**
     * Check if post is published.
     */
    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published';
    }

    /**
     * Calculate estimated read time based on content word count.
     */
    public static function estimateReadTime(string $content): int
    {
        $wordCount = str_word_count(strip_tags($content));

        return max(1, (int) ceil($wordCount / 200));
    }

    /**
     * Get all distinct categories used in blogs.
     */
    public static function getCategories(): array
    {
        return static::published()
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }
}
