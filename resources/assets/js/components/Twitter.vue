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

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

    data() {
        return {
            twitterUsername: '',
            tweetText: '',
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'Twitter.Mentioned': response => {
                    this.twitterUsername = response.twitterUsername;
                    this.tweetText = response.tweetText;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `twitter`,
            };
        },
    },
};
</script>
