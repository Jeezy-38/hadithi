<?php
namespace Database\Seeders;
use App\Models\Collection;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Collection::updateOrCreate(['slug' => 'bukhari'], ['name' => 'Sahih al-Bukhari', 'name_ar' => 'صحيح البخاري']);
        Collection::updateOrCreate(['slug' => 'muslim'], ['name' => 'Sahih Muslim', 'name_ar' => 'صحيح مسلم']);
        Collection::updateOrCreate(['slug' => 'tirmidhi'], ['name' => 'Jami\' at-Tirmidhiy', 'name_ar' => 'جامع الترمذي']);
        Collection::updateOrCreate(['slug' => 'abudawud'], ['name' => 'Sunan Abu Dawud', 'name_ar' => 'سنن أبي داود']);
        Collection::updateOrCreate(['slug' => 'ahmad'], ['name' => 'Musnad Ahmad', 'name_ar' => 'مسند أحمد']);
        Collection::updateOrCreate(['slug' => 'nasai'], ['name' => 'Sunan an-Nasa\'i', 'name_ar' => 'سنن النسائي']);
        Collection::updateOrCreate(['slug' => 'ibnmajah'], ['name' => 'Sunan Ibn Majah', 'name_ar' => 'سنن ابن ماجه']);
        Collection::updateOrCreate(['slug' => 'nawawi40'], ['name' => 'Hadithi 40 za An-Nawawi', 'name_ar' => 'الأربعون النووية']);
        Collection::updateOrCreate(['slug' => 'riyadhadussalihin'], ['name' => 'Riyadh as-Salihin', 'name_ar' => 'رياض الصالحين']);

        $this->call(DuaSeeder::class);
        $this->call(QuranSeeder::class);
    }
}
