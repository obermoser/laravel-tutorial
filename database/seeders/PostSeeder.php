<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Number of posts to create.
     */
    private const COUNT = 100;

    /**
     * Seed the posts table on its own.
     *
     * Fully self-contained and faker-free: run it standalone with
     *   php artisan db:seed --class=PostSeeder --force
     * It ensures the categories and an author user exist first, so it works
     * even on an empty database, without migrate:fresh or the DatabaseSeeder.
     * Safe to re-run (everything uses firstOrCreate / updateOrCreate).
     */
    public function run(): void
    {
        $categoryIds = $this->ensureCategories();
        $userIds = $this->ensureUsers();

        for ($i = 1; $i <= self::COUNT; $i++) {
            $title = $this->title($i);
            $slug = Str::slug($title).'-'.$i;

            Post::updateOrCreate(
                ['slug' => $slug],
                [
                    'image' => "https://picsum.photos/seed/post{$i}/640/480",
                    'title' => $title,
                    'content' => $this->content($i),
                    'category_id' => $categoryIds[($i - 1) % count($categoryIds)],
                    'user_id' => $userIds[($i - 1) % count($userIds)],
                    // Publish ~80%; leave the rest as drafts (null).
                    'published_at' => $i % 5 === 0 ? null : now()->subDays($i),
                ]
            );
        }

        $this->command->info(self::COUNT.' posts seeded.');
    }

    /**
     * Make sure the categories exist and return their ids.
     *
     * @return array<int, int>
     */
    private function ensureCategories(): array
    {
        $names = ['Technology', 'Health', 'Science', 'Sports', 'Politics', 'Entertainment'];

        foreach ($names as $name) {
            Category::firstOrCreate(['name' => $name]);
        }

        return Category::query()->pluck('id')->all();
    }

    /**
     * Make sure at least one author user exists and return the user ids.
     *
     * @return array<int, int>
     */
    private function ensureUsers(): array
    {
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password'),
            ]
        );

        return User::query()->pluck('id')->all();
    }

    /**
     * Build a varied, human-readable title from static word pools.
     */
    private function title(int $i): string
    {
        $adjectives = [
            'The Complete', 'A Practical', 'Rethinking', 'Understanding', 'Inside',
            'The Future of', 'Mastering', 'A Beginner Guide to', 'Lessons From', 'Why',
        ];

        $topics = [
            'Modern Web Development', 'Healthy Habits', 'Space Exploration',
            'Endurance Training', 'Public Policy', 'Independent Film',
            'Clean Code', 'Mindful Living', 'Renewable Energy', 'Data Privacy',
            'Remote Work', 'Artificial Intelligence', 'Urban Gardening', 'Open Source',
        ];

        $adjective = $adjectives[($i - 1) % count($adjectives)];
        $topic = $topics[($i - 1) % count($topics)];

        return "{$adjective} {$topic}";
    }

    /**
     * Assemble a few paragraphs of readable placeholder prose.
     */
    private function content(int $i): string
    {
        $sentences = [
            'This article explores the topic in depth and shares practical takeaways.',
            'We look at common pitfalls and how to avoid them in everyday work.',
            'The ideas here are drawn from real projects and hard-won experience.',
            'Each section builds on the last, so you can follow along step by step.',
            'By the end you should feel confident applying these principles yourself.',
            'There is no single right answer, but a few patterns consistently work well.',
            'Small, deliberate improvements add up to meaningful results over time.',
            'We close with a short checklist you can keep nearby for reference.',
        ];

        $paragraphs = [];

        for ($p = 0; $p < 3; $p++) {
            $chunk = [];

            for ($s = 0; $s < 4; $s++) {
                $chunk[] = $sentences[($i + $p + $s) % count($sentences)];
            }

            $paragraphs[] = implode(' ', $chunk);
        }

        return implode("\n\n", $paragraphs);
    }
}
