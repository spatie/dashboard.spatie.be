import Echo from '../mixins/echo';
import Grid from './grid';
import moment from 'moment';

export default {

    template: `
        <grid :position="grid">
            <section :class="online? 'up': 'down' | modify-class 'internet-connection' ">
                <div class="internet-connection__icon">
                </div>
            </section>
        </grid>
    `,

    components: {
        Grid,
    },

    mixins: [Echo],

    props: ['grid'],

    data() {
        return {
            online: true,
            lastHeartBeatReceivedAt: moment(),
        };
    },

    created() {
        setInterval(this.determineConnectionStatus, 1000);
    },

    methods: {
        determineConnectionStatus() {
            const lastHeartBeatReceivedSecondsAgo = moment().diff(this.lastHeartBeatReceivedAt, 'seconds');

            this.online = lastHeartBeatReceivedSecondsAgo < 125;
        },

        getEventHandlers() {
            return {
                'InternetConnectionStatus.Heartbeat': () => {
                    this.lastHeartBeatReceivedAt = moment();
                },
            };
        },
    },
};