<template>
    <tile :position="position">
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
                <div v-else>
                    <avatar :src="avatar" class="mr-1" />
                    <div
                        v-if="isBirthDay"
                        class="absolute flex items-center jsutify-center text-3xl"
                        v-html="emoji('👑')"
                        style="top: -1rem; right: .05rem; transform:rotate(7deg);"
                    />
                </div>
                <div class="flex-grow leading-tight ml-2">
                    <h2 class="truncate capitalize">
                        {{ name }}
                        <span
                            v-if="statusEmoji != ''"
                            class="text-xl"
                            v-html="emoji(statusEmoji)"
                        />
                    </h2>
                    <div v-if="currentTrack != ''" class="truncate text-xs text-dimmed">
                        <span v-html="emoji('🎵')" /> {{ currentTrack }}
                    </div>
                </div>
            </div>
            <div v-if="tasks != ''" v-html="tasks"></div>
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

            console.log(birthday.format('MD'), moment().format('MD'));
            return birthday.format('MD') === moment().format('MD');
        },
    },

    data() {
        return {
            tasks: '',
            currentTrack: '',
            artwork: '',
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
