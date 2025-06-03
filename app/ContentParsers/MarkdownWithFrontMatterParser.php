<?php

namespace App\ContentParsers;

use Illuminate\Support\HtmlString;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Parser\MarkdownParser;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Spatie\Sheets\ContentParser;
use Spatie\YamlFrontMatter\YamlFrontMatter;

class MarkdownWithFrontMatterParser implements ContentParser
{
    public function parse(string $contents): array
    {
        $document = YamlFrontMatter::parse($contents);

        $htmlContents = app(MarkdownRenderer::class)->toHtml($document->body());
        $toc = self::generateTOC($document->body());

        return array_merge(
            $document->matter(),
            [
                'contents' => new HtmlString($htmlContents),
                'toc' => $toc,
            ]
        );
    }

    public static function generateTOC(string $markdown): array
    {
        $environment = new Environment;
        $environment->addExtension(new CommonMarkCoreExtension);
        $converter = new MarkdownParser($environment);

        $document = $converter->parse($markdown);

        $toc = [];

        $walker = $document->walker();
        while ($event = $walker->next()) {
            $node = $event->getNode();

            if ($node instanceof Heading && $event->isEntering()) {
                $text = $node->firstChild()?->getLiteral() ?? '';
                $level = $node->getLevel();
                $slug = str()->slug($text);

                // Set ID cho heading để có thể scroll tới
                $node->data->set('attributes', ['id' => $slug]);

                $toc[] = [
                    'level' => $level,
                    'text' => $text,
                    'id' => $slug,
                ];
            }
        }

        return $toc;
    }
}
