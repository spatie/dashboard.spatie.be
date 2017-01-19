import _ from 'lodash';
import moment from 'moment';

export default class {

    constructor(tweetProperties) {
        this.tweetProperties = tweetProperties;
    }

    get authorScreenName() {
        return this.tweetProperties['user']['screen_name'];
    }

    get authorName() {
        return this.tweetProperties['user']['name'];
    }

    get authorAvatar() {
        return this.tweetProperties['user']['profile_image_url_https'];
    }

    get image() {
        return _.get(this.tweetProperties, 'extended_entities.media[0].media_url_https', '');
    }

    get date() {
        return moment(this.tweetProperties['created_at'], 'dd MMM DD HH:mm:ss ZZ YYYY');
    }

    get text() {
        return this.tweetProperties['text'];
    }

    get displayClass() {
        return 'default'; //medium, small
    }
}