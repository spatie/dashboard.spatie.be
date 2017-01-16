<template>
    <grid :position="grid" modifiers="overflow padded blue">
        <section>
            <h1>Twitter</h1>
            <div>user: {{ twitterUsername }}  tweet: {{ tweetText }}</div>
        </section>
    </grid>
</template>

<script>
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';
import Tweet from '../services/twitter/Tweet';
import moment from 'moment';
import { diffInSeconds } from '../helpers';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

    data() {
        return {
            onDisplay: null,
            displayingSince: new moment(),
            waitingLine: []
        };
    },

    created() {
        setInterval(this.processWaitingLine, 1000);
    },

    methods: {
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

            this.displayNextInWaitingLine();
        },

        displayNextInWaitingLine() {
            this.onDisplay = this.waitingLine.shift();
            this.displayingSince = new moment();
        },

        getSaveStateConfig() {
            return {
                cacheKey: `twitter`,
            };
        },
    },
};
</script>
