<script>
import moment from 'moment-timezone';

export default {
    props: {
        on: {
            type: String,
        },
        off: {
            type: String,
        },
        timeFormat: {
            type: String,
            default: 'HH:mm',
        },
    },

    data() {
        return {
            visibility: false,
        };
    },

    created() {
        this.checkVisibility();
        setInterval(this.checkVisibility, 60000);
    },

    methods: {
        checkVisibility() {
            this.visibility = moment().isBetween(moment(this.on, this.timeFormat), moment(this.off, this.timeFormat));
        },
    },

    render() {
        return this.visibility ? this.$slots.default[0] : null;
    },
};
</script>
