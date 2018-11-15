<?php

function usingNodeServer(): bool
{
    return config('broadcasting.default') === 'laravel-echo-server';
}

function gravatar(string $name)
{
    $gravatarId = md5(strtolower(trim($name)));

    return 'https://gravatar.com/avatar/' . $gravatarId . '?s=240';
}
