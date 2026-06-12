<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Web Development',
                'description' => 'Learn modern web development technologies including HTML, CSS, JavaScript, and frameworks.',
            ],
            [
                'name' => 'Mobile Development',
                'description' => 'Build mobile applications for iOS and Android using native and cross-platform technologies.',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Master data analysis, machine learning, and statistical modeling techniques.',
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Learn user interface and user experience design principles and tools.',
            ],
            [
                'name' => 'DevOps',
                'description' => 'Understand deployment, CI/CD, cloud infrastructure, and system administration.',
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Protect systems and networks from digital attacks and security threats.',
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
            ]);
        }
    }
}
