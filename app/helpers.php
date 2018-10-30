<?php

function markdownToHtml(string $markdown)
{
    return (new Parsedown)->text($markdown);
}

function formatTasks(string $html)
{
    $html = str_replace('(', '<span class="style-list-number">', $html);
    $html = str_replace(')', '</span>', $html);
    return $html;
}

function usingNodeServer(): bool
{
    return config('broadcasting.default') === 'laravel-echo-server';
}

function gravatar(string $name)
{
    $gravatarId = md5(strtolower(trim($name)));

    return 'https://gravatar.com/avatar/' . $gravatarId . '?s=240';
}
