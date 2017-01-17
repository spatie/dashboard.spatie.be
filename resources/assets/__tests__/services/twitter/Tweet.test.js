import { assert } from 'chai';
import Tweet from '../../../js/services/twitter/Tweet'
import tweetProperties from './fixtures/tweetText';
import tweetWithImageProperties from './fixtures/tweetWithImage';

let tweet;
let tweetWithImage;

describe('Mention', () => {

    beforeEach(() => {
        tweet = new Tweet(tweetProperties);
        tweetWithImage = new Tweet(tweetWithImageProperties);
    });

    it('can get the author screen name', () => {
        assert.equal(tweet.authorScreenName(), 'freekmurze');
    });

    it('can get the author name', () => {
        assert.equal(tweet.authorName(), 'Freek Van der Herten');
    });

    it('can get the avatar of the author', () => {
        assert.equal(tweet.authorAvatar(), 'https://pbs.twimg.com/profile_images/613846262383652864/3UKkvJFq_normal.jpg');
    });

    it('can get the image of the tweet', () => {
        assert.equal(tweet.image(), '');
        assert.equal(tweetWithImage.image(), 'https://pbs.twimg.com/media/C2UbzZtXgAEU7Ht.jpg');
    });

    it('can get the date of a tweet', () => {
        assert.equal(tweet.date().format('YYYY-MM-DD'), '2017-01-16')
    });
});