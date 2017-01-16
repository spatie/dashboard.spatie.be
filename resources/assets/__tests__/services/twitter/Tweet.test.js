import { assert } from 'chai';
import Tweet from '../../../js/services/twitter/Tweet'
import tweetText from './fixtures/tweetText';

let tweet;

describe('Mention', () => {

    beforeEach(() => {
        tweet = new Tweet(tweetText);
    });

    it('can get the author screen name', () => {
        assert.equal(tweet.authorScreenName(), 'freekmurze');
    });
});