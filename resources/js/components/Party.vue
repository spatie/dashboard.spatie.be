<template>
    <tile :position="position" :noFade="true">
        <scr-empty-event v-if="screen === routes.EMPTY" />
        <scr-new-event v-else-if="screen === routes.NEW" />
    </tile>
</template>

<script>
import { emoji } from '../helpers';
import Tile from './atoms/Tile';
import routes from './party/services/route';
import EventBus from '../services/event-bus';
import ScrEmptyEvent from './party/EmptyEvent';
import ScrNewEvent from './party/NewEvent';

export default {
    components: {
        Tile,
        ScrEmptyEvent,
        ScrNewEvent,
    },

    props: ['position'],

    methods: {
        emoji,
    },

    data () {
        return {
            routes: routes,
            screen: routes.NEW,
        }
    },

    created() {
        let self = this;
        EventBus.$on('party:navigate', function(nextScreen){
            self.screen = nextScreen;
        })
    }
};
</script>
