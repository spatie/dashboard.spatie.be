import { forEach } from 'lodash';

export default {
    watch: {
        '$data': {
            handler() {
                this.saveState();
            },
            deep: true,
        },
    },

    created() {
        this.loadState();
    },

    methods: {
        loadState() {
            const savedState = this.getSavedState();

            if (! savedState) {
                return;
            }

            forEach(savedState, (value, key) => this.$data[key] = value);
        },
        saveState() {
            localStorage.setItem(this.getSavedStateId(), JSON.stringify(this.$data));
        },
        getSavedState() {
            let savedState = localStorage.getItem(this.getSavedStateId());

            if (savedState) {
                savedState = JSON.parse(savedState);
            }

            return savedState;
        },
    },
};
