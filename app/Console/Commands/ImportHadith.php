<?php

namespace App\Console\Commands;

use App\Models\{Book, Chapter, Collection, Hadith};
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{DB, Validator};
use Illuminate\Validation\Rule;

class ImportHadith extends Command
{
    protected $signature = 'hadith:import {file : Path to a reviewed JSON dataset}';
    protected $description = 'Import reviewed Arabic/Swahili hadith with source attribution (atomic and repeatable)';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (! is_file($path) || ! is_readable($path)) {
            $this->error('Faili halipo au haliwezi kusomwa.');
            return self::FAILURE;
        }
        try {
            $rows = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $exception) {
            $this->error('JSON si sahihi: '.$exception->getMessage());
            return self::FAILURE;
        }
        if (! is_array($rows) || ! array_is_list($rows) || $rows === []) {
            $this->error('Dataset lazima iwe orodha ya hadith isiyo tupu.');
            return self::FAILURE;
        }
        $validator = Validator::make($rows, [
            '*.collection' => ['required', Rule::in(['bukhari', 'muslim', 'tirmidhi', 'abudawud', 'ahmad'])],
            '*.reference' => ['required', 'string', 'max:255', 'distinct:strict'],
            '*.number' => ['required', 'string', 'max:255'],
            '*.book.number' => ['required', 'integer', 'min:1'],
            '*.book.title_sw' => ['required', 'string', 'max:255'],
            '*.book.title_ar' => ['required', 'string', 'max:255'],
            '*.chapter.number' => ['required', 'integer', 'min:1'],
            '*.chapter.title_sw' => ['required', 'string', 'max:2000'],
            '*.chapter.title_ar' => ['required', 'string', 'max:2000'],
            '*.arabic' => ['required', 'string'],
            '*.swahili' => ['required', 'string'],
            '*.english' => ['nullable', 'string'],
            '*.source_name' => ['required', 'string', 'max:255'],
            '*.source_url' => ['required', 'url:http,https'],
            '*.numbering_system' => ['required', 'string', 'max:255'],
            '*.translator' => ['required', 'string', 'max:255'],
            '*.translation_source_url' => ['required', 'url:http,https'],
            '*.license' => ['required', 'string', 'max:255'],
            '*.reviewed_by' => ['required', 'string', 'max:255'],
            '*.reviewed_at' => ['required', 'date_format:Y-m-d', 'before_or_equal:today'],
            '*.is_published' => ['required', 'boolean'],
            '*.title' => ['nullable', 'string', 'max:2000'],
            '*.source_record_id' => ['nullable', 'string', 'max:255'],
            '*.reference_url' => ['nullable', 'url:http,https'],
            '*.attribution' => ['nullable', 'string'],
            '*.grade' => ['nullable', 'string', 'max:255'],
            '*.content_note' => ['nullable', 'string'],
            '*.license_url' => ['nullable', 'url:http,https'],
            '*.source_fetched_at' => ['nullable', 'date'],
            '*.source_sha256' => ['nullable', 'regex:/^[a-f0-9]{64}$/'],
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }
        $collections = Collection::pluck('id', 'slug');
        foreach ($rows as $row) {
            if (! isset($collections[$row['collection']]) || ! str_starts_with($row['reference'], $row['collection'].':')) {
                $this->error('Endesha db:seed kwanza. Rejea lazima ianze na collection:, mfano bukhari:edition:1.');
                return self::FAILURE;
            }
        }
        DB::transaction(function () use ($rows, $collections) {
            foreach ($rows as $row) {
                $book = Book::updateOrCreate(
                    ['collection_id' => $collections[$row['collection']], 'number' => $row['book']['number']],
                    ['title_sw' => $row['book']['title_sw'], 'title_ar' => $row['book']['title_ar']],
                );
                $chapter = Chapter::updateOrCreate(
                    ['book_id' => $book->id, 'number' => $row['chapter']['number']],
                    ['title_sw' => $row['chapter']['title_sw'], 'title_ar' => $row['chapter']['title_ar']],
                );
                $attributes = collect($row)->only([
                    'number', 'arabic', 'swahili', 'english', 'source_name', 'source_url', 'numbering_system',
                    'translator', 'translation_source_url', 'license', 'reviewed_by', 'reviewed_at', 'is_published',
                    'title', 'source_record_id', 'reference_url', 'attribution', 'grade', 'content_note',
                    'license_url', 'source_fetched_at', 'source_sha256',
                ])->all();
                Hadith::updateOrCreate(['reference' => $row['reference']], ['chapter_id' => $chapter->id, ...$attributes]);
            }
        });
        $this->info(count($rows).' hadith zimeingizwa au kusasishwa.');
        return self::SUCCESS;
    }
}
