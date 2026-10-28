<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PostSeeder extends Seeder
{
    /**
     * Number of posts to create.
     */
    private const COUNT = 100;

    /**
     * Seed the posts table.
     *
     * Faker-free on purpose: this runs in production where dev dependencies
     * (and the fake() helper) are not installed. Categories and users are read
     * from the database at runtime, so the foreign keys always point at rows
     * that actually exist in the target environment.
     */
    public function run(): void
    {
        $categoryIds = Category::query()->pluck('id')->all();
        $userIds = User::query()->pluck('id')->all();

        if (empty($categoryIds) || empty($userIds)) {
            $this->command->warn('PostSeeder skipped: seed categories and at least one user first.');

            return;
        }

        for ($i = 1; $i <= self::COUNT; $i++) {
            $title = $this->title($i);
            $slug = Str::slug($title).'-'.$i;

            // updateOrCreate keyed by the unique slug makes the seeder safe to
            // re-run: it updates the same rows instead of duplicating or hitting
            // the unique constraint.
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
