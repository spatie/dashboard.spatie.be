import { assert } from 'chai';
import Tweet from '../../../js/services/twitter/Tweet';
import tweetProperties from './fixtures/tweetText';
import tweetWithImageProperties from './fixtures/tweetWithImage';
import tweetWithQuoteProperties from './fixtures/tweetWithQuote';

let tweet;
let tweetWithImage;
let tweetWithQuote;

describe('Mention', () => {
    beforeEach(() => {
        tweet = new Tweet(tweetProperties);
        tweetWithImage = new Tweet(tweetWithImageProperties);
        tweetWithQuote = new Tweet(tweetWithQuoteProperties);
    });

    it('can get the author screen name', () => {
        assert.equal(tweet.authorScreenName, '@freekmurze');
    });

    it('can get the author name', () => {
        assert.equal(tweet.authorName, 'Freek Van der Herten');
    });

    it('can get the text of a tweet', () => {
        assert.equal(tweet.text, '@jarenduren testtweet #cool');
        assert.equal(tweetWithImage.text, '@jarenduren tweet with image');
    });

    it('can get the html of a tweet', () => {
        assert.equal(
            tweet.html,
            '<span class="tweet__body__handle">@jarenduren</span> testtweet <span class="tweet__body__hashtag">#cool</span>'
        );
    });

    it('can get the avatar of the author', () => {
        assert.equal(
            tweet.authorAvatar,
            'https://pbs.twimg.com/profile_images/613846262383652864/3UKkvJFq_normal.jpg'
        );
    });

    it('can get the image of the tweet', () => {
        assert.equal(tweet.image, '');
        assert.equal(tweetWithImage.image, 'https://pbs.twimg.com/media/C2UbzZtXgAEU7Ht.jpg');
    });

    it('can get the date of a tweet', () => {
        assert.equal(tweet.date.format('YYYY-MM-DD'), '2017-01-16');
    });

    it('can determine the display class of a tweet', () => {
        assert.equal(tweet.displayClass, 'large');
    });

    it('can determine whether a tweet has a quote', () => {
        assert.isFalse(tweet.hasQuote);
        assert.isTrue(tweetWithQuote.hasQuote);
    });

    it('cant have quotes in quotes', () => {
        assert.isFalse(tweetWithQuote.quote.hasQuote);
        assert.isNull(tweetWithQuote.quote.quote);
    });

    it('can get the quoted tweet', () => {
        assert.isNull(tweet.quote);
        assert.instanceOf(tweetWithQuote.quote, Tweet);
    });
});
