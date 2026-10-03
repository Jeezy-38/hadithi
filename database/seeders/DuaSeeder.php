<?php

namespace Database\Seeders;

use App\Models\Dua;
use App\Models\DuaCategory;
use Illuminate\Database\Seeder;

class DuaSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/duas-starter.json');
        if (! file_exists($path)) {
            return;
        }

        $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        $categoryMap = [];
        foreach ($data['categories'] ?? [] as $catData) {
            $cat = DuaCategory::updateOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name_sw' => $catData['name_sw'],
                    'name_en' => $catData['name_en'],
                    'name_ar' => $catData['name_ar'],
                    'icon' => $catData['icon'] ?? null,
                    'order' => $catData['order'] ?? 0,
                ]
            );
            $categoryMap[$catData['slug']] = $cat->id;
        }

        foreach ($data['duas'] ?? [] as $duaData) {
            $catSlug = $duaData['category_slug'] ?? null;
            if (! isset($categoryMap[$catSlug])) {
                continue;
            }

            Dua::updateOrCreate(
                [
                    'category_id' => $categoryMap[$catSlug],
                    'title_sw' => $duaData['title_sw'],
                ],
                [
                    'title_en' => $duaData['title_en'] ?? null,
                    'title_ar' => $duaData['title_ar'] ?? null,
                    'arabic' => $duaData['arabic'],
                    'transliteration' => $duaData['transliteration'] ?? null,
                    'swahili' => $duaData['swahili'],
                    'english' => $duaData['english'] ?? null,
                    'reference' => $duaData['reference'] ?? null,
                    'virtue_sw' => $duaData['virtue_sw'] ?? null,
                    'virtue_en' => $duaData['virtue_en'] ?? null,
                    'target_count' => $duaData['target_count'] ?? 1,
                    'order' => $duaData['order'] ?? 0,
                    'is_published' => true,
                ]
            );
        }
    }
}
