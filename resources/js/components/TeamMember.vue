<template>
    <tile :position="position">
        <div class="grid gap-padding h-full markup" style="--template-rows: auto 1fr;">
            <div class="flex items-center w-full bg-tile z-10">
                <avatar :src="avatar" />
                <div class="flex-grow leading-tight ml-2">
                    <div class="font-medium text-sm truncate capitalize" v-html="name" />
                    <div class="truncate text-xs text-dimmed"><span>♫</span> Artist – Album</div>
                </div>
            </div>
            <div v-html="formatTasks()"></div>
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
        formatTasks() {
            return this.tasks.replace(/\(/g, '<span class="text-dimmed text-xs">').replace(/\)/g, '</span>')
        },

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
