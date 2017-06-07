<?php

function markdownToHtml(string $markdown)
{
    return (new Parsedown)->text($markdown);
}

function usingNodeServer(): bool
{
    return config('broadcasting.default') === 'laravel-echo-server';
}
