<template>
    <tile :position="position" :class="{ 'opacity-50': worksFromHome }">
        <div
            class="grid gap-padding h-full markup"
            :style="tasks != '' ? 'grid-template-rows: auto 1fr' : 'grid-template-rows: 1fr'"
        >
            <div class="flex items-center w-full bg-tile z-10">
                <div
                    v-if="artwork != ''"
                    class="flex-none overflow-hidden w-12 h-12 -my-6 -ml-3 rounded border-2 border-screen"
                >
                    <img :src="artwork" class="w-12 h-12" />
                </div>
                <avatar v-else :src="avatar" class="mr-1" />
                <div class="flex-grow leading-tight ml-2">
                    <h2 class="truncate capitalize" v-html="name" />
                    <div v-if="currentTrack != ''" class="truncate text-xs text-dimmed">
                        <span v-html="emoji('🎵')" /> {{ currentTrack }}
                    </div>
                </div>
            </div>
            <div v-if="tasks != ''" v-html="formatTasks()"></div>
        </div>
    </tile>
</template>

<script>
import { emoji } from '../helpers';
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
            artwork: '',
            worksFromHome: false,
        };
    },

    methods: {
        emoji,

        formatTasks() {
            return this.tasks
                .replace(/\(/g, '<span class="ml-1 text-dimmed text-xs">')
                .replace(/\)/g, '</span>');
        },

        getEventHandlers() {
            return {
                'TeamMember.TasksFetched': response => {
                    this.tasks = response.tasks[this.name];
                },

                'TeamMember.UpdateStatus': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.worksFromHome = response.worksFromHome;
                },

                'TeamMember.PlayingTrack': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack = response.trackInfo.artist;
                    this.artwork = response.trackInfo.artwork;
                },
                'TeamMember.PlayingNothing': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack = '';
                    this.artwork = '';
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
