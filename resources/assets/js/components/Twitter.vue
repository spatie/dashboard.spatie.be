<template>
    <grid :position="grid" modifiers="overflow transparent">
        <section class="tweets">

            <!-- <div>user: {{ twitterUsername }}  tweet: {{ tweetText }}</div> -->
            <div class="tweet" v-for="tweet in onDisplay">
                <div class="tweet__header">
                    <div class="tweet__avatar"
                         style="background-image: url(https://www.gravatar.com/avatar/205e460b479e2e5b48aec07710c08d50?s=512)"></div>
                    <div class="tweet__user">
                        <div class="tweet__user__name">
                            {{ tweet.screenName }}
                        </div>
                        <div class="tweet__user__handle">
                            @averylongusernamethatneedscutoff
                        </div>
                    </div>
                </div>
                <div :class="addClassModifiers('tweet__body', 'medium')">
                    <span class="tweet__body__handle">@username</span> Donec mi nibh, consectetur sit amet suscipit
                    vitae, fringilla quis orci <span class="tweet__body__hashtag">#topic</span>
                </div>
                <div class="tweet__meta">
                    2 minutes ago
                </div>
                <div class="tweet__attachment">
                    <img src="http://placehold.it/800x600">
                </div>
            </div>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import saveState from 'vue-save-state';
    import Tweet from '../services/twitter/Tweet';
    import moment from 'moment';
    import {diffInSeconds, addClassModifiers} from '../helpers';

    export default {

        components: {
            Grid,
        },

        mixins: [echo, saveState],

        props: ['grid'],

        data() {
            return {
                displayingTopTweetSince: new moment(),
                onDisplay: []
                waitingLine: []
            };
        },

        created() {
            setInterval(this.processWaitingLine, 1000);
        },

        methods: {
            addClassModifiers,

            getEventHandlers() {
                return {
                    'Twitter.Mentioned': response => {
                        this.addToWaitingLine(new Tweet(response.tweetProperties))
                    },
                };
            },

            addToWaitingLine(tweet) {
                this.waitingLine.push(tweet)
            },

            processWaitingLine() {
                if (this.waitingLine.length === 0) {
                    return;
                }

                if (diffInSeconds(this.displayingSince) < 30) {
                    return;
                }

                this.onDisplay.unshift(this.waitingLine.shift());

                this.displayNextInWaitingLine();

                this.onDisplay = this.onDisplay.slice(0,5);
            },

            getSaveStateConfig() {
                return {
                    cacheKey: `twitter`,
                };
            },
        },
    };
</script>
