<template>
    <tile class="rounded overflow-auto" :position="position" :transparent="true">
        <div class="grid h-full" style="--auto-rows: auto; --gap: 1rem">
            <div class="overflow-hidden rounded bg-tile p-gap" v-for="tweet in onDisplay">
                <div class="flex items-center -mt-1 mb-4 w-full">
                    <div class="flex-none overflow-hidden w-8 h-8 rounded-full">
                        <img class="block w-8 h-8 rounded-full object-fit-cover" :src="tweet.authorAvatar" :alt="tweet.authorScreenName">
                    </div>
                    <div class="flex-grow leading-tight ml-2">
                        <div class="text-sm truncate" v-html="tweet.authorName"></div>
                        <div class="text-xs text-dimmed truncate" v-html="tweet.authorScreenName"></div>
                    </div>
                </div>
                <div :class="tweet.displayClass" v-html="tweet.html"></div>
                <div class="mt-1 text-xs text-dimmed">
                    <relative-date :moment="tweet.date"></relative-date>
                    <span v-if="tweet.hasQuote">
                        In reply to {{ tweet.quote.authorScreenName }}
                    </span>
                </div>
                <img v-if="tweet.image" class="mt-gap max-h-48 mx-auto" :src="tweet.image" />
                <div v-if="tweet.hasQuote" class="mt-gap py-2 pl-2 text-xs text-dimmed border-l-2 border-screen" v-html="tweet.hasQuote"></div>
            </div>
        </div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import RelativeDate from './atoms/RelativeDate';
import Tweet from '../services/twitter/Tweet';
import moment from 'moment';
import { diffInSeconds } from '../helpers';

export default {
    components: {
        Tile,
        RelativeDate,
    },

    mixins: [echo],

    props: ['position', 'initialTweets'],

    data() {
        return {
            displayingTopTweetSince: moment(),
            tweets: [],
            waitingLine: [],
            ownScreenName: '@spatie_be',
        };
    },

    created() {
        this.tweets = this.initialTweets.map(tweetProperties => new Tweet(tweetProperties));

        setInterval(this.processWaitingLine, 1000);
    },

    methods: {
        getEventHandlers() {
            return {
                'Twitter.Mentioned': response => {
                    this.addToWaitingLine(new Tweet(response.tweetProperties));
                },
            };
        },

        addToWaitingLine(tweet) {
            this.waitingLine.push(tweet);
        },

        processWaitingLine() {
            if (this.waitingLine.length === 0) {
                return;
            }

            if (diffInSeconds(this.displayingTopTweetSince) < 5) {
                return;
            }

            this.tweets.unshift(this.waitingLine.shift());

            this.tweets = this.tweets.slice(0, 20);

            this.displayingTopTweetSince = moment();
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'twitter',
            };
        },
    },

    computed: {
        onDisplay() {
            return this.tweets.filter(tweet => {
                return tweet.authorScreenName !== this.ownScreenName && !tweet.isRetweet;
            });
        },
    },
};
</script>
