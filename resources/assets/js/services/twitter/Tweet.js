export default class {

    constructor(tweetProperties) {
        this.tweetProperties = tweetProperties;
    }

    authorScreenName() {
        return this.tweetProperties['name'];
    }

    authorName() {
        return this.tweetProperties['user']['name'];
    }

    authorAvatar() {
        return this.tweetProperties['user']['profile_image_url_https'];
    }

    image() {
        return this.tweetProperties['extended_properties']['media'][0]['media_url_https'];
    }
}