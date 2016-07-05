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
            let savedState = this.getSavedState();

            if (!savedState) {
                return;
            }

            this.$data = savedState;
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


