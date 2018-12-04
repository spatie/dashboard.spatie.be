<?php

function gravatar(string $name): string
{
    $gravatarId = md5(strtolower(trim($name)));

    return 'https://gravatar.com/avatar/' . $gravatarId . '?s=240';
}
