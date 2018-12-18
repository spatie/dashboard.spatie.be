<template>
    <tile :position="position">
        <div class="h-full markup flex flex-col justify-between py-4">
            <div class="w-full bg-tile z-10 mb-3">
                <div class="flex flex-row items-center">
                    <avatar 
                        v-if="jira.avatar"
                        :src="jira.avatar" :darkmode="leaveToDay !== false" />
                    <member-avatar 
                        v-else
                        :member="info" />
                    <h2 class="truncate capitalize leading-tight min-w-0">{{ firstName }}</h2>
                </div>
            </div>
            <div v-if="jira.issues && jira.issues.length">
                <span v-for="ticket in jira.issues.slice(0, 3)">
                    <jira-ticket :jkey="ticket.key" :hint="ticket.label" />
                </span>
            </div>
            <div></div>
        </div>
        <div class="abs_xws text-xs" v-if="leaveToDay !== false">
            <span>Leave <leave-status :status="leaveToDay" /></span>
            <span v-if="leaveTomorrow !== false">& <leave-status :status="leaveTomorrow" :label="'tomorrow'" /></span>
        </div>
    </tile>
</template>

<style>
.abs_xws {
    position: absolute;
    right: 1rem;
    bottom: 0.5rem;
    text-align: right;
    color: var(--text-dimmed);
}
</style>

<script>
import { emoji } from '../helpers';
import echo from '../mixins/echo';
import Avatar from './atoms/Avatar';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';
import moment from 'moment';
import MemberAvatar from './atoms/MemberAvatar';
import JiraTicket from './atoms/JiraTicket';
import LeaveHelper from '../services/leave';
import LeaveStatus from './atoms/LeaveStatus';

export default {
    components: {
        Avatar,
        Tile,
        MemberAvatar,
        JiraTicket,
        LeaveStatus,
    },

    mixins: [echo, saveState],

    props: ['info', 'position', 'jira', 'leave'],

    computed: {
        firstName() {
            return this.info.name.split(' ').pop();
        },

        isBirthDay() {
        //     let birthday = moment(this.birthday);
        //     return birthday.format('MD') === moment().format('MD');
            return false;
        },
        leaveToDay() {
            return LeaveHelper.leaveStatusToday(this.leave);
        },
        leaveTomorrow() {
            return LeaveHelper.leaveStatusTomorrow(this.leave);
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
