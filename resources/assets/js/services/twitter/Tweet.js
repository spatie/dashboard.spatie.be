import _ from 'lodash';
import moment from 'moment';

export default class {

    constructor(tweetProperties) {
        this.tweetProperties = tweetProperties;
    }

    authorScreenName() {
        return this.tweetProperties['user']['screen_name'];
    }

    authorName() {
        return this.tweetProperties['user']['name'];
    }

    authorAvatar() {
        return this.tweetProperties['user']['profile_image_url_https'];
    }

    image() {
        return _.get(this.tweetProperties, 'extended_entities.media[0].media_url_https', '');
    }

    date() {
        return moment(this.tweetProperties['created_at'], 'dd MMM DD HH:mm:ss ZZ YYYY');
    }

    text() {
        return this.tweetProperties['text'];
    }

    displayClass() {
        return 'default'; //medium, small

    }
}