<?php

/*
 * Original code based on the CommonMark PHP parser (https://github.com/thephpleague/commonmark/)
 *  - (c) Colin O'Dell
 */

namespace App\Services\CommonMark\Strikethrough;

use League\CommonMark\Delimiter\Delimiter;
use League\CommonMark\Inline\Element\Text;
use League\CommonMark\Inline\Parser\AbstractInlineParser;
use League\CommonMark\InlineParserContext;
use League\CommonMark\Util\RegexHelper;

class StrikethroughParser extends AbstractInlineParser
{
    /**
     * @return string[]
     */
    public function getCharacters()
    {
        return ['~'];
    }

    /**
     * @param InlineParserContext $inlineContext
     *
     * @return bool
     */
    public function parse(InlineParserContext $inlineContext)
    {
        $character = $inlineContext->getCursor()->getCharacter();
        if ($character !== '~') {
            return false;
        }

        $numDelims = 0;

        $cursor = $inlineContext->getCursor();
        $charBefore = $cursor->peek(-1);
        if ($charBefore === null) {
            $charBefore = "\n";
        }

        while ($cursor->peek($numDelims) === '~') {
            ++$numDelims;
        }

        // Skip single delims
        if ($numDelims === 1) {
            return false;
        }

        $cursor->advanceBy($numDelims);

        $charAfter = $cursor->getCharacter();
        if ($charAfter === null) {
            $charAfter = "\n";
        }

        $afterIsWhitespace = preg_match('/\pZ|\s/u', $charAfter);
        $afterIsPunctuation = preg_match(RegexHelper::REGEX_PUNCTUATION, $charAfter);
        $beforeIsWhitespace = preg_match('/\pZ|\s/u', $charBefore);
        $beforeIsPunctuation = preg_match(RegexHelper::REGEX_PUNCTUATION, $charBefore);

        $leftFlanking = $numDelims > 0 && !$afterIsWhitespace &&
            !($afterIsPunctuation &&
                !$beforeIsWhitespace &&
                !$beforeIsPunctuation);

        $rightFlanking = $numDelims > 0 && !$beforeIsWhitespace &&
            !($beforeIsPunctuation &&
                !$afterIsWhitespace &&
                !$afterIsPunctuation);

        if ($character === '_') {
            $canOpen = $leftFlanking && (!$rightFlanking || $beforeIsPunctuation);
            $canClose = $rightFlanking && (!$leftFlanking || $afterIsPunctuation);
        } else {
            $canOpen = $leftFlanking;
            $canClose = $rightFlanking;
        }

        $node = new Text($cursor->getPreviousText(), [
            'delim' => true
        ]);
        $inlineContext->getContainer()->appendChild($node);

        // Add entry to stack to this opener
        $delimiter = new Delimiter($character, $numDelims, $node, $canOpen, $canClose);
        $inlineContext->getDelimiterStack()->push($delimiter);

        return true;
    }
}
