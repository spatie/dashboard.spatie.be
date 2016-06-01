import Grid from './grid';
import Pusher from '../mixins/pusher';
import SaveState from '../mixins/save-state';

export default {

    template: `
        <grid :position="grid" modifiers="overflow padded blue">
            <section class="gitlab-merge-requests">
                 <h1>Open merge requests</h1>
                  <ul>
                    <li v-for="user in users">
                      - {{ user.name }} {{ user.count }}
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
            users: '',
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'App\\Components\\GitLab\\Events\\MergeRequestsFetched': response => {
                    this.users = response.users;
                },
            };
        },

        getSavedStateId() {
            return `gilab-merge-requests`;
        },
    },
};
