import { assert } from 'chai';
import { Tweet } from '../../../js/services/twitter/Tweet'

let tweet;

describe('Mention', () => {

    beforeEach(() => {
        tweet = new Tweet({})
    });

    it('can get the author screen name', () => {
        assert.equal(tweet.authorScreenName, 'freekmurze');
    });
});