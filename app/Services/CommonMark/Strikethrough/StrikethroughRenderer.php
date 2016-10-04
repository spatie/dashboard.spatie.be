<?php

/*
 * Original code based on the CommonMark PHP parser (https://github.com/thephpleague/commonmark/)
 *  - (c) Colin O'Dell
 */

namespace App\Services\CommonMark\Strikethrough;

use League\CommonMark\ElementRendererInterface;
use League\CommonMark\HtmlElement;
use League\CommonMark\Inline\Element\AbstractInline;
use League\CommonMark\Inline\Renderer\InlineRendererInterface;

class StrikethroughRenderer implements InlineRendererInterface
{

    /** @inheritdoc */
    public function render(AbstractInline $inline, ElementRendererInterface $htmlRenderer)
    {
        if (!($inline instanceof Strikethrough)) {
            throw new \InvalidArgumentException(sprintf('Incompatible inline type: %s', get_class($inline)));
        }

        $attrs = [];
        foreach ($inline->getData('attributes', []) as $key => $value) {
            $attrs[$key] = $htmlRenderer->escape($value, true);
        }

        return new HtmlElement('del', $attrs, $htmlRenderer->renderInlines($inline->children()));
    }

}

