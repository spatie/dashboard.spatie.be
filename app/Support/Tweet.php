<?php

namespace App\Support;

use Illuminate\Support\Arr;

class Tweet
{
    private array $tweetProperties;

    private ?Tweet $quotedTweet;

    public function __construct(array $tweetProperties)
    {
        $this->tweetProperties = $tweetProperties;

        if ($this->hasQuote() && $this->tweetProperties['quoted_status'] ?? false) {
            $this->quotedTweet = new Tweet($this->tweetProperties['quoted_status']);
        }
    }

    public function authorScreenName(): string
    {
        return "@{$this->tweetProperties['user']['screen_name']}";
    }

    public function authorName(): string
    {
        return $this->tweetProperties['user']['name'];
    }

    public function authorAvatar(): string
    {
        return $this->tweetProperties['user']['profile_image_url_https'];
    }

    public function image(): string
    {
        return Arr::get($this->tweetProperties, 'extended_entities.media.0.media_url_https', '');
    }

    public function date(): string
    {
        return $this->tweetProperties['created_at'];
    }

    public function isRetweet(): bool
    {
        return Arr::has($this->tweetProperties, 'retweeted_status');
    }

    public function bySpatie(): bool
    {
        return $this->authorScreenName() === '@spatie.be';
    }

    public function hasQuote(): bool
    {
        if (! $this->tweetProperties['is_quote_status']) {
            return false;
        }

        if (! Arr::has($this->tweetProperties, 'quoted_status')) {
            return false;
        }

        return true;
    }

    public function quote(): ?Tweet
    {
        return $this->quotedTweet;
    }

    public function text()
    {
        $text = $this->tweetProperties['text'];

        $media = Arr::get($this->tweetProperties, 'extended_entities.media', []);

        $text = collect($media)
            ->map(fn (array $media) => $media['url'])
            ->reduce(fn ($text, $mediaUrl) => str_replace($mediaUrl, '', $text), $text);

        $displayTextRange = $this->tweetProperties['display_text_range'] ?? false;
        if ($displayTextRange) {
            $text = substr($text, $displayTextRange[0], $displayTextRange[1]);
        }

        return $text;
    }

    public function html(): string
    {
        /*
         * TODO: add
         *    .replace(/(#\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/g, '<span class="tweet__body__hashtag">$1</span>')
            .replace(/(@\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/g, '<span class="tweet__body__handle">$1</span>');
         */

        return $this->text();
    }

    public function displayClass(): string
    {
        $textLenght = strlen($this->text());

        if ($textLenght < 30) {
            return 'text-lg';
        }

        if ($textLenght < 60) {
            return 'text-md';
        }

        if ($textLenght < 100) {
            return 'text-base';
        }

        return 'text-sm';
    }
}
