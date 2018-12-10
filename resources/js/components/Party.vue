<template>
    <tile :position="position">
        <div 
            class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;"
            v-if="scenario === userCases.EMPTY"
            >
            <title-text/>
            <div><empty-event-list /></div>
        </div>
        <div
            class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;" 
            v-else-if="scenario === userCases.NEW"
            >
            <scr-new-event />
        </div>
    </tile>
</template>

<script>

import { emoji } from '../helpers';
import Tile from './atoms/Tile';
import TitleText from './party/TitleText';
import EmptyEventList from './party/EmptyEventList';
import ScrNewEvent from './party/ScrNewEvent';

let USER_CASES = {
    EMPTY: 'NO_UPCOMING_EVENT',
    NEW: 'CREATE_NEW_EVENT',
};

export default {
    components: {
        Tile,
        TitleText,
        EmptyEventList,
        ScrNewEvent,
    },

    props: ['position'],

    methods: {
      emoji,
    },

    data () {
        return {
          userCases: USER_CASES,
          scenario:  USER_CASES.NEW,
        }
      }
};
</script>
