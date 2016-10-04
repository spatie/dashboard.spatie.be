<?php

use App\Services\CommonMark\CommonMarkConverter;

function markdownToHtml(string $markdown)
{
    $converter = new CommonMarkConverter();

    return $converter->convertToHtml($markdown);
}
