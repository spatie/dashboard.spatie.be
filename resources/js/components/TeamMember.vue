<template>
    <tile :position="position">
        <div
            class="grid gap-padding h-full markup"
            :style="tasks ? 'grid-template-rows: auto 1fr' : 'grid-template-rows: 1fr'"
        >
            <div
                class="grid gap-2 items-center w-full bg-tile z-10"
                style="grid-template-columns: auto 1fr"
            >
                <div
                    v-if="artwork != ''"
                    class="overflow-hidden w-10 h-10 rounded border border-screen"
                >
                    <component :is="trackUrl ? 'a' : 'span'" :href="trackUrl || ''" target="_blank">
                        <img :src="artwork" class="w-10 h-10" />
                    </component>
                </div>
                <div v-else>
                    <avatar :src="avatar" />
                    <div
                        v-if="isBirthDay"
                        class="absolute flex items-center jsutify-center text-3xl"
                        v-html="emoji('👑')"
                        style="top: -1rem; right: .05rem; transform:rotate(7deg);"
                    />
                </div>
                <div class="leading-tight min-w-0">
                    <h2 class="truncate capitalize">
                        {{ name }}
                        <span
                            v-if="statusEmoji != ''"
                            class="text-xl"
                            v-html="emoji(statusEmoji)"
                        />
                    </h2>
                    <component
                        v-if="currentTrack != ''"
                        class="truncate text-sm block"
                        :is="trackUrl ? 'a' : 'span'"
                        :href="trackUrl || ''"
                        target="_blank"
                    >
                        <span v-html="emoji('🎵')" /> {{ currentTrack }}
                    </component>
                </div>
            </div>
            <div class="align-self-center" v-if="tasks" v-html="tasks"></div>
        </div>
    </tile>
</template>

<script>
import { emoji } from '../helpers';
import echo from '../mixins/echo';
import Avatar from './atoms/Avatar';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';
import moment from 'moment';

export default {
    components: {
        Avatar,
        Tile,
    },

    mixins: [echo, saveState],

    props: ['name', 'avatar', 'position', 'birthday'],

    computed: {
        isBirthDay() {
            let birthday = moment(this.birthday);

            return birthday.format('MD') === moment().format('MD');
        },
    },

    data() {
        return {
            tasks: '',
            currentTrack: '',
            artwork: '',
            trackUrl: '',
            statusEmoji: '',
        };
    },

    methods: {
        emoji,

        getEventHandlers() {
            return {
                'TeamMember.TasksFetched': response => {
                    this.tasks = response.tasks[this.name];
                },

                'TeamMember.UpdateStatus': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.statusEmoji = response.statusEmoji;
                },

                'TeamMember.PlayingTrack': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack = response.trackInfo.artist;
                    this.artwork = response.trackInfo.artwork;
                    this.trackUrl = response.trackInfo.url;
                },

                'TeamMember.PlayingNothing': response => {
                    if (response.teamMemberName !== this.name) {
                        return;
                    }

                    this.currentTrack = '';
                    this.artwork = '';
                    this.trackUrl = '';
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
