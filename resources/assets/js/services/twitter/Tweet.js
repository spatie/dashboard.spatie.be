import moment from 'moment';
import twemoji from 'twemoji';
import { get, last } from 'lodash';
import { substring } from 'stringz';

class Tweet {
    constructor(tweetProperties) {
        this.tweetProperties = tweetProperties;

        if (this.hasQuote) {
            this.quotedTweet = new Tweet(this.tweetProperties.quoted_status);
        }
    }

    get authorScreenName() {
        return '@' + this.tweetProperties['user']['screen_name'];
    }

    get authorName() {
        return twemoji.parse(this.tweetProperties['user']['name']);
    }

    get authorAvatar() {
        return this.tweetProperties['user']['profile_image_url_https'];
    }

    get image() {
        return get(this.tweetProperties, 'extended_entities.media[0].media_url_https', '');
    }

    get date() {
        return moment(this.tweetProperties['created_at'], 'dd MMM DD HH:mm:ss ZZ YYYY');
    }

    get isRetweet() {
        return this.tweetProperties.hasOwnProperty('retweeted_status');
    }

    get hasQuote() {
        return (
            this.tweetProperties['is_quote_status'] &&
            this.tweetProperties['quoted_status'] !== null
        );
    }

    get quote() {
        return this.quotedTweet || null;
    }

    get text() {
        let text = this.tweetProperties['text'];

        text = get(this.tweetProperties, 'extended_entities.media', [])
            .map(media => media.url)
            .reduce((text, mediaUrl) => text.replace(mediaUrl, ''), text);

        if (this.tweetProperties.hasOwnProperty('display_text_range')) {
            text = substring(text, ...this.tweetProperties['display_text_range']);
        }

        if (this.hasQuote) {
            text = text.replace(last(this.tweetProperties.entities.urls).url, '');
        }

        return text;
    }

    get html() {
        // http://stackoverflow.com/a/38383605/999733
        return twemoji
            .parse(this.text)
            .replace(
                /(#\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/g,
                '<span class="tweet__body__hashtag">$1</span>'
            )
            .replace(
                /(@\w*[0-9a-zA-Z]+\w*[0-9a-zA-Z])/g,
                '<span class="tweet__body__handle">$1</span>'
            );
    }

    get displayClass() {
        if (this.text.length < 30) {
            return 'large';
        }

        if (this.text.length < 60) {
            return 'medium';
        }

        if (this.text.length < 100) {
            return 'default';
        }

        return 'small';
    }
}

export default Tweet;
