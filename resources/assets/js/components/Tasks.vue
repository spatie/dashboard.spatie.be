<template>
    <grid-area :position="position" modifiers="overflow">
        <section class="github-file">
            <h1 class="github-file__title">{{ teamMember }}</h1>
            <div class="github-file__content" v-html="tasks"></div>
        </section>
    </grid-area>
</template>

<script>
    import echo from '../mixins/echo';
    import GridArea from './atoms/GridArea';
    import saveState from 'vue-save-state';

    export default {

        components: {
            GridArea,
        },

        mixins: [echo, saveState],

        props: ['teamMember', 'position'],

        data() {
            return {
                tasks: '',
            };
        },

        methods: {
            getEventHandlers() {
                return {
                    'Tasks.TasksFetched': response => {
                        this.tasks = response.tasks[this.teamMember];
                    },
                };
            },

            getSaveStateConfig() {
                return {
                    cacheKey: `tasks-${this.teamMember}`,
                };
            },
        },
    };
</script>
