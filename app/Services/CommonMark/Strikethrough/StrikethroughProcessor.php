<?php

/*
 * Original code based on the CommonMark PHP parser (https://github.com/thephpleague/commonmark/)
 *  - (c) Colin O'Dell
 */

namespace App\Services\CommonMark\Strikethrough;

use League\CommonMark\Delimiter\Delimiter;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Delimiter\DelimiterStack;
use League\CommonMark\Inline\Processor\InlineProcessorInterface;

class StrikethroughProcessor implements InlineProcessorInterface
{
    public function processInlines(DelimiterStack $delimiterStack, Delimiter $stackBottom = null)
    {
        $callback = function (Delimiter $opener, Delimiter $closer, DelimiterStack $stack) {
            $useDelims = 2;

            /** @var Text $openerInline */
            $openerInline = $opener->getInlineNode();
            /** @var Text $closerInline */
            $closerInline = $closer->getInlineNode();

            // Remove used delimiters from stack elts and inlines
            $opener->setNumDelims($opener->getNumDelims() - $useDelims);
            $closer->setNumDelims($closer->getNumDelims() - $useDelims);
            $openerInline->setContent(substr($openerInline->getContent(), 0, -$useDelims));
            $closerInline->setContent(substr($closerInline->getContent(), 0, -$useDelims));

            $strike = new Strikethrough();
            $openerInline->insertAfter($strike);
            while (($node = $strike->next()) !== $closerInline) {
                $strike->appendChild($node);
            }

            // Remove elts btw opener and closer in delimiters stack
            $tempStack = $closer->getPrevious();
            while ($tempStack !== null && $tempStack !== $opener) {
                $nextStack = $tempStack->getPrevious();
                $stack->removeDelimiter($tempStack);
                $tempStack = $nextStack;
            }
            // If opener less than 2 delims, remove it and the inline
            if ($opener->getNumDelims() < 2) {
                if ($opener->getNumDelims() === 0) {
                    $openerInline->detach();
                }
                $stack->removeDelimiter($opener);
            }
            if ($closer->getNumDelims() < 2) {
                if ($closer->getNumDelims() === 0) {
                    $closerInline->detach();
                }
                $tempStack = $closer->getNext();
                $stack->removeDelimiter($closer);

                return $tempStack;
            }

            return $closer;
        };

        // Process the emphasis characters
        $delimiterStack->iterateByCharacters('~', $callback, $stackBottom);
    }
}
