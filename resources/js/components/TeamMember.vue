<template>
    <tile :position="position">
        <div class="grid h-full markup" style="--template-rows: auto 1fr; grid-gap: .75rem">
            <div class="flex items-center w-full bg-tile z-10" style="top: -.2rem">
                <avatar :src="avatar" />
                <div class="flex-grow leading-tight ml-2">
                    <div class="font-medium text-sm truncate capitalize" v-html="name" />
                    <div v-if="currentTrack != ''" class="truncate text-xs text-dimmed">
                        <span v-html="renderMusicIcon()"></span> {{ currentTrack }}</div>
                </div>
            </div>
            <div v-html="formatTasks()"></div>
        </div>
    </tile>
</template>

<script>
import twemoji from 'twemoji';
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
            currentTrack: '',
        };
    },

    methods: {
        formatTasks() {
            return this.tasks.replace(/\(/g, '<span class="ml-1 text-dimmed text-xs">').replace(/\)/g, '</span>')
        },

        getEventHandlers() {
            return {
                'Tasks.TasksFetched': response => {
                    this.tasks = response.tasks[this.name];
                },
                'Music.TeamMemberPlayingTrack': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack =  `${response.trackInfo.artist} - ${response.trackInfo.trackName}`;
                },
                'Music.TeamMemberPlayingNothing': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack = '';
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `tasks-${this.name}`,
            };
        },

        renderMusicIcon() {
            return twemoji.parse('🎧');
        },
    },
};
</script>
