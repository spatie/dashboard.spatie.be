<template>
    <grid :position="grid" modifiers="overflow padded blue">
        <section class="github-file">
            <h1 class="github-file__title">{{ teamMember }}</h1>
            <div class="github-file__content" v-html="tasks"></div>
        </section>
    </grid>
</template>

<script>
    import echo from '../mixins/echo';
    import Grid from './atoms/Grid';
    import saveState from 'vue-save-state';

    export default {

        components: {
            Grid,
        },

        mixins: [echo, saveState],

        props: ['teamMember', 'grid'],

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
