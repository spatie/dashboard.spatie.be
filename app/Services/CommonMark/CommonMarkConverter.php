<?php

namespace App\Services\CommonMark;
use App\Services\CommonMark\Strikethrough\StrikethroughExtension;
use League\CommonMark\Converter;
use League\CommonMark\DocParser;
use League\CommonMark\Environment;
use League\CommonMark\HtmlRenderer;

/**
 * Converts CommonMark-compatible Markdown to HTML.
 */
class CommonMarkConverter extends Converter
{
    /**
     * Create a new commonmark converter instance.
     *
     * @param array            $config
     * @param Environment|null $environment
     */
    public function __construct(array $config = [], Environment $environment = null)
    {
        if ($environment === null) {
            $environment = Environment::createCommonMarkEnvironment();
            $environment->addExtension(new StrikethroughExtension());
        }

        $environment->mergeConfig($config);

        parent::__construct(new DocParser($environment), new HtmlRenderer($environment));
    }
}
