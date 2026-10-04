<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GadgetHubTest extends TestCase
{
    use RefreshDatabase;

    public function test_landing_page_only_uses_published_articles(): void
    {
        $author = User::factory()->create();
        $this->createBlog($author, [
            'title' => 'Published gadget review',
            'status' => 'published',
        ]);
        $this->createBlog($author, [
            'title' => 'Unpublished gadget review',
            'status' => 'draft',
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Published gadget review')
            ->assertDontSee('Unpublished gadget review');
    }

    public function test_public_blog_search_and_detail_only_show_published_articles(): void
    {
        $author = User::factory()->create();
        $published = $this->createBlog($author, [
            'title' => 'Cyan phone review',
            'slug' => 'cyan-phone-review',
            'category' => 'HP',
            'status' => 'published',
            'content' => '<h2>Performance</h2><p>Fast and reliable.</p>',
        ]);
        $this->createBlog($author, [
            'title' => 'Draft phone review',
            'status' => 'draft',
        ]);

        $this->get(route('blog.index', ['search' => 'Cyan', 'category' => 'HP']))
            ->assertOk()
            ->assertSee('Cyan phone review')
            ->assertDontSee('Draft phone review');

        $this->get(route('blog.show', $published->slug))
            ->assertOk()
            ->assertSee('Cyan phone review')
            ->assertSee('Performance');

        $this->get(route('blog.show', 'draft-phone-review'))->assertNotFound();
    }

    public function test_admin_can_log_in_and_manage_blog_articles(): void
    {
        $admin = User::factory()->create([
            'email' => 'admin@gadgethub.test',
            'password' => 'password123',
        ]);

        $this->get(route('admin.blogs.index'))->assertRedirect(route('login'));

        $this->post(route('login.post'), [
            'email' => 'admin@gadgethub.test',
            'password' => 'password123',
        ])->assertRedirect(route('admin.blogs.index'));

        $this->assertAuthenticatedAs($admin);

        $this->get(route('admin.blogs.index'))->assertOk();
        $this->get(route('admin.blogs.create'))
            ->assertOk()
            ->assertSee('Tambah Artikel Baru');

        $this->post(route('admin.blogs.store'), [
            'title' => 'New "gadget" article',
            'slug' => 'new-gadget-article',
            'category' => 'Laptop',
            'excerpt' => 'A concise review.',
            'content' => '<h2>Overview</h2><p>Article body.</p>',
            'status' => 'draft',
        ])->assertRedirect(route('admin.blogs.index'));

        $blog = Blog::where('slug', 'new-gadget-article')->firstOrFail();
        $this->assertSame($admin->id, $blog->user_id);
        $this->assertSame(1, $blog->read_time);
        $this->get(route('admin.blogs.edit', $blog))->assertOk();

        $this->patch(route('admin.blogs.toggle-status', $blog))
            ->assertRedirect();
        $this->assertDatabaseHas('blogs', ['id' => $blog->id, 'status' => 'published']);

        $this->delete(route('admin.blogs.destroy', $blog))
            ->assertRedirect(route('admin.blogs.index'));
        $this->assertDatabaseMissing('blogs', ['id' => $blog->id]);

        $this->post(route('logout'))->assertRedirect(route('login'));
        $this->assertGuest();
    }

    private function createBlog(User $author, array $attributes = []): Blog
    {
        return Blog::create(array_merge([
            'user_id' => $author->id,
            'title' => 'Gadget review',
            'slug' => fake()->unique()->slug(),
            'category' => 'HP',
            'excerpt' => 'A gadget overview.',
            'content' => '<h2>Overview</h2><p>Review content.</p>',
            'status' => 'draft',
            'read_time' => 1,
        ], $attributes));
    }
}
