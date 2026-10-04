<?php

namespace Database\Seeders;

use App\Models\Blog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ===========================
        // Create Admin User
        // ===========================
        $admin = User::updateOrCreate(
            ['email' => 'admin@gadgethub.com'],
            [
                'name'     => 'Admin GadgetHub',
                'password' => Hash::make('password123'),
            ]
        );

        // ===========================
        // Dummy Blog Data
        // ===========================
        $blogs = [
            [
                'title'    => 'iPhone 16 Pro Max Review: The Ultimate Smartphone of 2025',
                'category' => 'HP',
                'excerpt'  => 'Apple\'s flagship device pushes boundaries with its A18 Pro chip, titanium build, and groundbreaking camera system. Is it worth the upgrade?',
                'content'  => $this->getContent('iphone'),
                'status'   => 'published',
            ],
            [
                'title'    => 'Samsung Galaxy S25 Ultra vs Google Pixel 9 Pro: Camera Battle',
                'category' => 'HP',
                'excerpt'  => 'We pit two of Android\'s finest against each other in an epic camera shootout across multiple scenarios and lighting conditions.',
                'content'  => $this->getContent('camera-battle'),
                'status'   => 'published',
            ],
            [
                'title'    => 'MacBook Pro M4: Redefining Laptop Performance',
                'category' => 'Laptop',
                'excerpt'  => 'Apple Silicon continues to dominate. The M4 chip brings unprecedented performance per watt that makes Windows laptops sweat.',
                'content'  => $this->getContent('macbook'),
                'status'   => 'published',
            ],
            [
                'title'    => 'ASUS ROG Zephyrus G16 (2025) — Gaming Laptop Benchmark Deep Dive',
                'category' => 'Laptop',
                'excerpt'  => 'With RTX 5080 and AMD Ryzen AI 9, this gaming monster obliterates every title at 4K. But does battery life suffer?',
                'content'  => $this->getContent('rog'),
                'status'   => 'published',
            ],
            [
                'title'    => 'Apple Watch Ultra 3 Review: The Adventure Companion',
                'category' => 'Wearables',
                'excerpt'  => 'Designed for extreme athletes and adventurers, the Watch Ultra 3 now brings satellite SOS and 96-hour battery life to your wrist.',
                'content'  => $this->getContent('watch'),
                'status'   => 'published',
            ],
            [
                'title'    => 'Top 10 Must-Have Accessories for Remote Workers in 2025',
                'category' => 'Accessories',
                'excerpt'  => 'From ergonomic keyboards to 4K webcams and smart lighting — here\'s every gadget that will transform your home office setup.',
                'content'  => $this->getContent('accessories'),
                'status'   => 'published',
            ],
            [
                'title'    => 'OnePlus 13 Review: Flagship Killer Returns',
                'category' => 'HP',
                'excerpt'  => 'OnePlus is back with a vengeance. Hasselblad cameras, 100W charging, and Snapdragon 8 Elite — all for under $900.',
                'content'  => $this->getContent('oneplus'),
                'status'   => 'published',
            ],
            [
                'title'    => 'The Best Budget Laptops Under $500 in 2025',
                'category' => 'Laptop',
                'excerpt'  => 'You don\'t need to break the bank for a capable laptop. We tested 12 budget picks and ranked the best value-for-money options.',
                'content'  => $this->getContent('budget'),
                'status'   => 'draft',
            ],
        ];

        foreach ($blogs as $blogData) {
            $slug      = Str::slug($blogData['title']);
            $readTime  = Blog::estimateReadTime($blogData['content']);

            Blog::updateOrCreate(
                ['slug' => $slug],
                [
                    'user_id'   => $admin->id,
                    'title'     => $blogData['title'],
                    'slug'      => $slug,
                    'category'  => $blogData['category'],
                    'excerpt'   => $blogData['excerpt'],
                    'content'   => $blogData['content'],
                    'status'    => $blogData['status'],
                    'read_time' => $readTime,
                    'thumbnail' => null,
                ]
            );
        }

        $this->command->info('✅ Admin user created: admin@gadgethub.com / password123');
        $this->command->info('✅ ' . count($blogs) . ' blog posts seeded successfully.');
    }

    // ===========================
    // Dummy Rich Content Generator
    // ===========================
    private function getContent(string $type): string
    {
        $templates = [
            'default' => '<h2>Overview</h2><p>This is a comprehensive review of one of the most exciting gadgets released this year. We tested it extensively across multiple scenarios to bring you the most honest and detailed assessment possible.</p><h2>Design & Build Quality</h2><p>The build quality is exceptional, featuring premium materials that feel luxurious in hand. The ergonomics have been carefully considered, making it comfortable to use for extended periods without fatigue.</p><h2>Performance</h2><p>Under the hood, you get cutting-edge internals that deliver blazing-fast performance in both everyday tasks and demanding workflows. Benchmarks consistently place it at the top of its class.</p><h2>Display</h2><p>The display is nothing short of stunning — vibrant colors, deep blacks, and adaptive refresh rate technology ensure smooth, beautiful visuals whether you\'re scrolling feeds or watching 4K content.</p><h2>Battery Life</h2><p>Battery performance is excellent, easily lasting a full day of heavy usage. Fast charging support means you can top up quickly during breaks, getting 50% charge in under 30 minutes.</p><h2>Camera System</h2><p>The camera system is a standout feature, delivering professional-quality photos and videos in all lighting conditions. Night mode performance is particularly impressive, capturing detail where other devices fail.</p><h2>Verdict</h2><p>This is a premium gadget that justifies its price tag. If you\'re in the market for a top-tier device and want the absolute best experience, this should be at the top of your list.</p><blockquote><strong>Rating: 9.2/10</strong> — An exceptional piece of technology that sets a new benchmark for the industry.</blockquote>',
        ];

        return $templates['default'];
    }
}

