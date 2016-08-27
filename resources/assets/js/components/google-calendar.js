import Echo from '../mixins/echo';
import Grid from './grid';
import SaveState from '../mixins/save-state';

export default {

    template: `
             <grid :position="grid" modifiers="overflow padded blue">
                <section class="google-calendar">
                    <h1>Upcoming</h1>
                    <ul class="google-calendar__events">
                        <li v-for="event in events"  class="google-calendar__event">
                            <h2 class="google-calendar__event__title">{{ event.name }}</h2>
                            <div class="google-calendar__event__date">{{ event.date | relative-date }}</div>
                        </li>
                    </ul>
                </section>
             </grid>
    `,

    components: {
        Grid,
    },

    mixins: [Echo, SaveState],

    props: ['grid'],

    data() {
        return {
            events: [],
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'GoogleCalendar.EventsFetched': response => {
                    this.events = response.events;
                },
            };
        },

        getSavedStateId() {
            return 'google-calendar';
        },
    },
};
