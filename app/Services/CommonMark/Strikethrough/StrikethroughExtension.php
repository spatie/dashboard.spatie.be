<?php

/*
 * Original code based on the CommonMark PHP parser (https://github.com/thephpleague/commonmark/)
 *  - (c) Colin O'Dell
 */

namespace App\Services\CommonMark\Strikethrough;

use League\CommonMark\Extension\Extension;
use League\CommonMark\Inline\Parser\InlineParserInterface;
use League\CommonMark\Inline\Processor\InlineProcessorInterface;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class StrikethroughExtension extends Extension
{
    /**
     * Returns the name of the extension.
     *
     * @return string
     */
    public function getName()
    {
        return 'Strikethrough';
    }

    /**
     * @return InlineParserInterface[]
     */
    public function getInlineParsers()
    {
        return [
            new StrikethroughParser(),
        ];
    }

    /**
     * @return InlineProcessorInterface[]
     */
    public function getInlineProcessors()
    {
        return [
            new StrikethroughProcessor(),
        ];
    }

    /**
     * @return InlineRendererInterface[]
     */
    public function getInlineRenderers()
    {
        return [
            Strikethrough::class => new StrikethroughRenderer(),
        ];
    }
}
