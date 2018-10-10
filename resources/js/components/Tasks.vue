<template>
    <tile :position="position">
        <div class="flex items-center -mt-2 mb-4 w-full">
            <div class="flex-none overflow-hidden w-8 h-8 rounded-full">
                <img class="block rounded-full" :src="avatar" :alt="teamMember">
            </div>
            <div class="flex-grow flex items-center text-xs text-dimmed ml-2">
                <span class="truncate">Artist — Album</span>
                <span class="ml-1">♫</span>
            </div>
        </div>
        <div v-html="tasks" class="tile-list"></div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['teamMember', 'avatar', 'position'],

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
