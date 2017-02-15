<?php

namespace App\Console;

use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Events\Twitter\Mentioned;
use Illuminate\Foundation\Inspiring;

class SendFakeTweet extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:fake-tweet {text?} {--Q|quote : Attach a quote to the tweet}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a fake tweet (optionally with a quote).';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $text = $this->argument('text') ?? Inspiring::quote();
        $quote = $this->option('quote') ? Inspiring::quote() : '';
        event(new Mentioned($this->getFakeTweetProperties($text, $quote)));
    }

    protected function getFakeTweetProperties(string $text, string $quote): array
    {
        if (! empty($quote)) {
            return $this->getFakeTweetWithQuoteProperties($text, $quote);
        }

        return json_decode('{"created_at":"' . Carbon::now()->subHour()->format('D M d H:i:s +0000 Y') .'","id":821093087715987456,"id_str":"821093087715987456","text":"' . $text . '","display_text_range":[0,' . strlen($text).'],"source":"<a href=\"http:\/\/tapbots.com\/software\/tweetbot\/mac\" rel=\"nofollow\">Tweetbot for Mac<\/a>","truncated":false,"in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":443972916,"in_reply_to_user_id_str":"443972916","in_reply_to_screen_name":"jarenduren","user":{"id":97178022,"id_str":"97178022","name":"Freek Van der Herten","screen_name":"freekmurze","location":"Antwerp, Belgium","url":"https:\/\/murze.be","description":"PHP developer, Laravel enthousiast at https:\/\/spatie.be, blogging at https:\/\/murze.be, package creator: http:\/\/spatie.be\/opensource, co-organizing @phpantwerp","protected":false,"verified":false,"followers_count":3913,"friends_count":1156,"listed_count":275,"favourites_count":6422,"statuses_count":15598,"created_at":"Wed Dec 16 09:56:14 +0000 2009","utc_offset":3600,"time_zone":"Brussels","geo_enabled":true,"lang":"en","contributors_enabled":false,"is_translator":false,"profile_background_color":"FFFFFF","profile_background_image_url":"http:\/\/pbs.twimg.com\/profile_background_images\/435349478783320064\/EvvoYi0J.jpeg","profile_background_image_url_https":"https:\/\/pbs.twimg.com\/profile_background_images\/435349478783320064\/EvvoYi0J.jpeg","profile_background_tile":false,"profile_link_color":"909090","profile_sidebar_border_color":"FFFFFF","profile_sidebar_fill_color":"FFFFFF","profile_text_color":"2C2C2C","profile_use_background_image":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/613846262383652864\/3UKkvJFq_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/613846262383652864\/3UKkvJFq_normal.jpg","profile_banner_url":"https:\/\/pbs.twimg.com\/profile_banners\/97178022\/1435187942","default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null},"geo":null,"coordinates":null,"place":{"id":"2a8a74486cd0d519","url":"https:\/\/api.twitter.com\/1.1\/geo\/id\/2a8a74486cd0d519.json","place_type":"city","name":"Antwerp","full_name":"Antwerp, Belgium","country_code":"BE","country":"Belgium","bounding_box":{"type":"Polygon","coordinates":[[[4.218084,51.143464],[4.218084,51.377649],[4.498296,51.377649],[4.498296,51.143464]]]},"attributes":[]},"contributors":null,"is_quote_status":false,"retweet_count":0,"favorite_count":0,"entities":{"hashtags":[],"urls":[],"user_mentions":[{"screen_name":"jarenduren","name":"jarenduren","id":443972916,"id_str":"443972916","indices":[0,11]}],"symbols":[],"media":[{"id":821093076953432065,"id_str":"821093076953432065","indices":[29,52],"media_url":"\/images\/fake-tweet.jpg","media_url_https":"\/images\/fake-tweet.jpg","url":"https:\/\/t.co\/YDzJA2e9vu","display_url":"pic.twitter.com\/YDzJA2e9vu","expanded_url":"https:\/\/twitter.com\/freekmurze\/status\/821093087715987456\/photo\/1","type":"photo","sizes":{"medium":{"w":1200,"h":745,"resize":"fit"},"large":{"w":2048,"h":1272,"resize":"fit"},"thumb":{"w":150,"h":150,"resize":"crop"},"small":{"w":680,"h":422,"resize":"fit"}}}]},"extended_entities":{"media":[{"id":821093076953432065,"id_str":"821093076953432065","indices":[29,52],"media_url":"\/fake-tweet.jpg","media_url_https":"\/images\/fake-tweet.jpg","url":"https:\/\/t.co\/YDzJA2e9vu","display_url":"pic.twitter.com\/YDzJA2e9vu","expanded_url":"https:\/\/twitter.com\/freekmurze\/status\/821093087715987456\/photo\/1","type":"photo","sizes":{"medium":{"w":1200,"h":745,"resize":"fit"},"large":{"w":2048,"h":1272,"resize":"fit"},"thumb":{"w":150,"h":150,"resize":"crop"},"small":{"w":680,"h":422,"resize":"fit"}}}]},"favorited":false,"retweeted":false,"possibly_sensitive":false,"filter_level":"low","lang":"en","timestamp_ms":"1484598821940"}', true);
    }

    protected function getFakeTweetWithQuoteProperties(string $text, string $quote): array
    {
        return json_decode('{"created_at":"' . Carbon::now()->subHour()->format('D M d H:i:s +0000 Y') .'","id":829639681142845442,"id_str":"829639681142845442","text":"' . $text . '","truncated":false,"entities":{"hashtags":[],"symbols":[],"user_mentions":[],"urls":[{"url":"https:\/\/t.co\/vfYGmD3wVe","expanded_url":"https:\/\/twitter.com\/spatie_be\/status\/829391006067982337","display_url":"twitter.com\/spatie_be\/stat\u2026","indices":[5,28]}]},"source":"<a href=\"http:\/\/twitter.com\" rel=\"nofollow\">Twitter Web Client<\/a>","in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":50056622,"id_str":"50056622","name":"Alex","screen_name":"AlexVanderbist","location":"Antwerp, Belgium","url":null,"description":"Student & web developer, 21 years old.","protected":false,"followers_count":216,"friends_count":385,"listed_count":5,"created_at":"Tue Jun 23 18:27:12 +0000 2009","favourites_count":1311,"utc_offset":3600,"time_zone":"Brussels","geo_enabled":true,"verified":false,"statuses_count":1559,"lang":"nl","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"EBEBEB","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme7\/bg.gif","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme7\/bg.gif","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/744852765525028864\/g2MphyFz_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/744852765525028864\/g2MphyFz_normal.jpg","profile_banner_url":"https:\/\/pbs.twimg.com\/profile_banners\/50056622\/1466421788","profile_link_color":"D62B4E","profile_sidebar_border_color":"DFDFDF","profile_sidebar_fill_color":"F3F3F3","profile_text_color":"333333","profile_use_background_image":true,"default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null,"translator_type":"none"},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":true,"quoted_status_id":829391006067982337,"quoted_status_id_str":"829391006067982337","quoted_status":{"created_at":"Wed Feb 08 18:06:39 +0000 2017","id":829391006067982337,"id_str":"829391006067982337","text":"' . $quote . '","truncated":false,"entities":{"hashtags":[],"symbols":[],"user_mentions":[],"urls":[{"url":"https:\/\/t.co\/ytqUv2wxVE","expanded_url":"http:\/\/git-awards.com\/users?language=php","display_url":"git-awards.com\/users?language\u2026","indices":[103,126]}]},"source":"<a href=\"http:\/\/tapbots.com\/tweetbot\" rel=\"nofollow\">Tweetbot for i\u039fS<\/a>","in_reply_to_status_id":null,"in_reply_to_status_id_str":null,"in_reply_to_user_id":null,"in_reply_to_user_id_str":null,"in_reply_to_screen_name":null,"user":{"id":92947501,"id_str":"92947501","name":"Spatie","screen_name":"spatie_be","location":"Antwerp, Belgium","url":"http:\/\/spatie.be","description":"Spatie is a webdesign agency based in Antwerp, Belgium. We specialize in @laravelphp development and create a lot of OSS https:\/\/spatie.be\/opensource","protected":false,"followers_count":1423,"friends_count":474,"listed_count":47,"created_at":"Fri Nov 27 10:59:27 +0000 2009","favourites_count":243,"utc_offset":3600,"time_zone":"Brussels","geo_enabled":false,"verified":false,"statuses_count":3968,"lang":"en","contributors_enabled":false,"is_translator":false,"is_translation_enabled":false,"profile_background_color":"007698","profile_background_image_url":"http:\/\/abs.twimg.com\/images\/themes\/theme14\/bg.gif","profile_background_image_url_https":"https:\/\/abs.twimg.com\/images\/themes\/theme14\/bg.gif","profile_background_tile":false,"profile_image_url":"http:\/\/pbs.twimg.com\/profile_images\/758257719656742912\/VFt0XpKH_normal.jpg","profile_image_url_https":"https:\/\/pbs.twimg.com\/profile_images\/758257719656742912\/VFt0XpKH_normal.jpg","profile_banner_url":"https:\/\/pbs.twimg.com\/profile_banners\/92947501\/1469617720","profile_link_color":"0D58DB","profile_sidebar_border_color":"FFFFFF","profile_sidebar_fill_color":"EFEFEF","profile_text_color":"333333","profile_use_background_image":true,"default_profile":false,"default_profile_image":false,"following":null,"follow_request_sent":null,"notifications":null,"translator_type":"none"},"geo":null,"coordinates":null,"place":null,"contributors":null,"is_quote_status":false,"retweet_count":0,"favorite_count":0,"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"},"retweet_count":0,"favorite_count":0,"favorited":false,"retweeted":false,"possibly_sensitive":false,"lang":"en"}', true);
    }
}
