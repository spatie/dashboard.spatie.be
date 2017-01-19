<template>
    <grid :position="grid" modifiers="overflow transparent">
        <section class="tweets">

            <!-- <div>user: {{ twitterUsername }}  tweet: {{ tweetText }}</div> -->
            <div class="tweet" v-for="tweet in onDisplay">
                <div class="tweet__header">
                    <div class="tweet__avatar"
                         :style="'background-image: url('+ tweet.authorAvatar +')'"></div>
                    <div class="tweet__user">
                        <div class="tweet__user__name">
                            {{ tweet.authorName }}
                        </div>
                        <div class="tweet__user__handle">
                            {{ tweet.authorScreenName }}
                        </div>
                    </div>
                </div>
                <div :class="addClassModifiers('tweet__body', tweet.displayClass)">
                    {{ tweet.text }}
                </div>
                <div class="tweet__meta">
                    <relative-date :moment="tweet.date"></relative-date>
                </div>

                <div v-if="tweet.image" class="tweet__attachment">
                    <img :src="tweet.image" />
                </div>
            </div>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './Grid';
    import RelativeDate from './RelativeDate';
    import saveState from 'vue-save-state';
    import Tweet from '../services/twitter/Tweet';
    import moment from 'moment';
    import {diffInSeconds, addClassModifiers} from '../helpers';

    export default {

        components: {
            Grid,
            RelativeDate,
        },

        mixins: [echo],

        props: ['grid'],

        data() {
            return {
                displayingTopTweetSince: new moment(),
                onDisplay: [],
                waitingLine: [],
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

                if (diffInSeconds(this.displayingTopTweetSince) < 5) {
                    return;
                }

                this.onDisplay.unshift(this.waitingLine.shift());

                this.onDisplay = this.onDisplay.slice(0,5);

                this.displayingTopTweetSince = new moment();
            },

            getSaveStateConfig() {
                return {
                    cacheKey: `twitter`,
                };
            },
        },
    };
</script>
