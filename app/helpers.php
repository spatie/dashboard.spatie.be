<?php

use League\CommonMark\CommonMarkConverter;

function markdownToHtml(string $markdown)
{
    $converter = new CommonMarkConverter();

    return $converter->convertToHtml($markdown);
}
