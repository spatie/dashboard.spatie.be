<template>
    <screen :noBack="true">
        <div class="flex w-full justify-between" slot="header">
            <div class="flex items-center">
                <i class="text-3xl leading-none -mt-1" v-html="emoji('🍿')" />
                <span>Upcoming events</span>
            </div>
            <btn-new />
        </div>

        <list-empty v-if="!events.length" />

        <div v-if="events.length">
            <div v-for="event in events">
                <event :data="event" />
            </div>
        </div>
    </screen>
</template>

<script>
import axios from 'axios';
import {emoji} from '../../helpers';
import Screen from '../atoms/Screen';
import BtnNew from './parts/BtnNew';
import ListEmpty from './parts/ListEmpty';
import Event from './parts/Event';

export default {
    components: {
        Screen,
        BtnNew,
        ListEmpty,
        Event,
    },

    methods: {
        emoji,
        fetch() {
            axios.get('/party').then(res => {
                this.events = res.data;
            });
        },
    },

    data() {
        return {
            events: [],
        }
    },

    created() {
        this.fetch();
    },
}    
</script>