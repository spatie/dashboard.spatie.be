<?php

function gravatar(string $name): string
{
    if ($name === 'seb@spatie.be') {
        $name = 'sebastian@spatie.be';
    }

    $gravatarId = md5(strtolower(trim($name)));

    return 'https://gravatar.com/avatar/'.$gravatarId.'?s=240';
}

function formatNaturalNumber(int $number): string
{
    return number_format($number, 0, '.', ' ');
}
