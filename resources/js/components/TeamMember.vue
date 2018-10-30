<template>
    <tile :position="position">
        <div class="grid h-full" style="--template-rows: auto 1fr;">
            <div class="flex items-center w-full">
                <avatar :src="avatar" />
                <div class="flex-grow leading-tight ml-2">
                    <div class="font-medium text-sm truncate capitalize" v-html="name" />
                    <div class="truncate text-xs text-dimmed"><span>♫</span> Artist – Album</div>
                </div>
            </div>
            <div v-html="tasks" class="mt-2 style-list"></div>
        </div>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Avatar from './atoms/Avatar';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';

export default {
    components: {
        Avatar,
        Tile,
    },

    mixins: [echo, saveState],

    props: ['name', 'avatar', 'position'],

    data() {
        return {
            tasks: '',
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'Tasks.TasksFetched': response => {
                    this.tasks = response.tasks[this.name];
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `tasks-${this.name}`,
            };
        },
    },
};
</script>
