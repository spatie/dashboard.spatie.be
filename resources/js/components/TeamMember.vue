<template>
    <tile :position="position">
        <div
            class="grid gap-padding h-full markup"
            style="grid-template-columns: 100%"
            :style="tasks ? 'grid-template-rows: auto 1fr' : 'grid-template-rows: 1fr'"
        >
            <div class="grid gap-2 items-center w-full bg-tile z-10" style="grid-template-columns: auto 1fr">
                <div v-if="artwork != ''" class="overflow-hidden w-10 h-10 rounded border border-screen">
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
                        {{ displayName || name }}
                        <span v-if="statusEmoji != ''" class="text-xl" v-html="emoji(statusEmoji)" />
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
            <div class="align-self-center" v-if="tasks.length">
                <ul>
                    <li v-for="task in largeTasks" :key="task.id">
                        <div class="truncate">
                            <p class="truncate">
                                {{ task.project }}
                            </p>
                            <p v-if="task.name" class="flex-1 truncate text-xs text-dimmed">
                                {{ upperFirst(task.name) }}
                            </p>
                        </div>
                        <p class="ml-2 font-bold variant-tabular">{{ task.formatted_time }}</p>
                    </li>
                </ul>
                <ul class="text-xs border-t-2 border-screen pt-1">
                    <li v-for="task in smallTasks" :key="task.id">
                        <p class="truncate" :data-id="task.id">
                            {{ task.project }} <span v-if="task.name" class="text-dimmed">{{ lowerFirst(task.name) }}</span>
                        </p>
                    </li>
                </ul>
            </div>
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
import { upperFirst, lowerFirst } from 'lodash';

export default {
    components: {
        Avatar,
        Tile,
    },

    mixins: [echo, saveState],

    props: ['name', 'displayName', 'avatar', 'position', 'birthday'],

    data() {
        return {
            tasks: [],
            currentTrack: '',
            artwork: '',
            trackUrl: '',
            statusEmoji: '',
        };
    },

    computed: {
        isBirthDay() {
            let birthday = moment(this.birthday);

            return birthday.format('MD') === moment().format('MD');
        },

        largeTasks() {
            return this.tasks.filter(task => task.hours >= 8);
        },

        smallTasks() {
            return this.tasks.filter(task => {
                if (task.hours >= 8) {
                    return false;
                }

                if (task.project === 'Open source / Eigen werk' && task.name === '') {
                    return false;
                }

                return true;
            });
        },
    },

    methods: {
        lowerFirst,
        upperFirst,
        emoji,

        getEventHandlers() {
            return {
                'TeamMember.TasksFetched': response => {
                    this.tasks = response.tasks[this.name] || [];
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
