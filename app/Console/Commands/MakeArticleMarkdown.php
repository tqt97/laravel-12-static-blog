<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Sheets\Facades\Sheets;

use function Laravel\Prompts\suggest;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;

class MakeArticleMarkdown extends Command
{
    const TAG_CACHE_KEY = 'cached.article.tags';

    const TAG_CACHE_TTL = 300; // seconds (5 minutes)

    protected $signature = 'make:article
                            {--force : Overwrite if exists}
                            {--slug= : Custom slug for filename}
                            {--status=draft : Article status (draft|published)}
                            {--dry-run : Preview markdown content instead of saving}
                        ';

    protected $description = 'Generate a new markdown article file with frontmatter';

    public function handle(): void
    {
        $title = $this->promptTitle();
        $slug = $this->generateSlug($title);
        $author = $this->promptAuthor();
        $teaser = $this->promptTeaser();
        $tags = $this->promptTags();
        $status = in_array($this->option('status'), ['draft', 'published'])
            ? $this->option('status')
            : 'draft';

        $filename = now()->format('Y-m-d').'.'.$slug.'.md';
        $path = base_path('storage/app/articles/'.$filename);

        if ($this->option('dry-run')) {
            $this->info('🔍 Previewing markdown file...');
            $this->line($this->generateMarkdown($title, $author, $teaser, $tags, $status));

            return;
        }

        if (File::exists($path) && ! $this->option('force')) {
            $this->error("❌ File already exists: {$filename}");

            return;
        }

        $this->info("📝 Creating file: {$filename}");

        $content = $this->generateMarkdown($title, $author, $teaser, $tags, $status);
        $this->info("\n📄Preview content:\n");
        $this->line($content);
        if (! $this->confirm("\n✅ Do you want to create this file?", true)) {
            $this->warn('❌ Operation cancelled.');

            return;
        }

        File::ensureDirectoryExists(dirname($path));
        File::put($path, $content);
        $this->forgetCachedTags();
        $this->info("✅ Article created at: {$path}");
        $this->openFileInEditor($path);
    }

    protected function yamlEscape(string $value): string
    {
        return str_replace('"', '\"', $value);
    }

    protected function promptTitle(): string
    {
        return text(
            label: '📌 Article title?',
            required: 'What is the article title?',
            placeholder: 'E.g. Hello, world',
            validate: fn (string $value) => match (true) {
                strlen($value) < 3 => 'The title must be at least 3 characters.',
                strlen($value) > 255 => 'The title must not exceed 255 characters.',
                default => null
            },
            hint: 'Title should be between 3 and 255 characters',
        );
    }

    protected function promptAuthor(): string
    {
        return text(
            label: '👤 Author?',
            placeholder: 'E.g. tuantq',
            default: get_current_user() ?? 'tuantq',
        );
    }

    protected function promptTeaser(): string
    {
        return textarea(
            label: '✏️ Teaser?',
            placeholder: 'Short preview of the article...',
            required: 'What is the article teaser?',
            validate: fn (string $value) => strlen($value) > 10000
                ? 'Teaser must not exceed 10000 characters.'
                : null,
            hint: 'Leave empty if not needed',
        );
    }

    protected function promptTags(): array
    {
        $existingTags = $this->getCachedTags();
        $selectedTags = [];

        while (true) {
            $tag = suggest('🏷️ Add a tag (leave blank to finish)', $existingTags);

            if (empty($tag)) {
                break;
            }

            $tag = trim(strtolower($tag));

            if (! in_array($tag, $selectedTags)) {
                $selectedTags[] = $tag;
            }
        }

        return $selectedTags;
    }

    protected function generateMarkdown(string $title, string $author, string $teaser, array $tags, string $status): string
    {
        $yaml = [
            'title' => $this->yamlEscape($title),
            'author' => $this->yamlEscape($author),
            'teaser' => $this->yamlEscape($teaser),
            'tags' => '['.implode(', ', array_map(fn ($tag) => '"'.$tag.'"', $tags)).']',
            'status' => $this->yamlEscape($status),
        ];

        $frontmatter = "---\n";
        foreach ($yaml as $key => $value) {
            $frontmatter .= "{$key}: {$value}\n";
        }
        $frontmatter .= "---\n";

        return $frontmatter."\nContent go here\n";
    }

    protected function generateSlug(string $title): string
    {
        $generatedSlug = Str::slug(iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $title));
        $customSlug = $this->option('slug');

        $slugLabel = $customSlug
            ? '🔗 Slug? (custom from --slug option)'
            : "🔗 Slug? (Auto generate from title: {$generatedSlug})";
        $hint = $customSlug ? 'You can remove this option if you want to use the auto-generated slug from title' : 'Leave unchanged if you want to use the custom slug';
        $slug = text(
            label: $slugLabel,
            default: $this->option('slug') ?? $generatedSlug,
            placeholder: 'your-custom-slug',
            validate: fn (string $value) => strlen($value) > 255
                ? 'The slug must not exceed 255 characters.'
                : null,
            hint: $hint
        );

        return $slug ?: $generatedSlug;
    }

    protected function openFileInEditor(string $path): void
    {
        $this->info('🔍 Opening file...');

        $editor = config('app.default_editor', 'code'); // or 'nano', 'vim'

        match ($editor) {
            'code' => exec("code {$path}"),
            'nano' => exec("nano {$path}"),
            'vim' => exec("vim {$path}"),
            default => match (PHP_OS_FAMILY) {
                'Darwin' => exec("open {$path}"),
                'Windows' => exec("start {$path}"),
                'Linux' => exec("xdg-open {$path}"),
                default => $this->warn('⚠️ Cannot auto-open on this OS.')
            }
        };
    }

    protected function getCachedTags(): array
    {
        return Sheets::collection('articles')
            ->all()
            ->flatMap(fn ($article) => $article->tags ?? [])
            ->unique()
            ->values()
            ->toArray();
    }

    protected function forgetCachedTags(): void
    {
        Cache::flush();
    }
}
