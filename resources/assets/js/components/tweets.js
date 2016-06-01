import Grid from './grid';
import Pusher from '../mixins/pusher';
import SaveState from '../mixins/save-state';

export default {

    template: `
        <grid :position="grid" modifiers="overflow padded blue">
            <section class="tweets">
                  <ul>
                    <li v-for="tweet in tweets">
                      -{{ tweet.text }}
                    </li>
                 </ul>
            </section>
        </grid>
    `,

    components: {
        Grid,
    },

    mixins: [Pusher, SaveState],

    props: ['grid'],

    data() {
        return {
            tweets: '',
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'App\\Components\\Twitter\\Events\\TweetsFetched': response => {
                    this.tweets = response.tweets;
                },
            };
        },

        getSavedStateId() {
            return `tweets`;
        },
    },
};
