<template>
    <tile :position="position" :noFade="true">
        <scr-list v-if="screen === routes.LIST" />
        <scr-new-event v-else-if="screen === routes.NEW" />
    </tile>
</template>

<script>
import { emoji } from '../helpers';
import Tile from './atoms/Tile';
import routes from './party/services/route';
import EventBus from '../services/event-bus';
import ScrList from './party/List';
import ScrNewEvent from './party/NewEvent';

export default {
    components: {
        Tile,
        ScrList,
        ScrNewEvent,
    },

    props: ['position'],

    methods: {
        emoji,
    },

    data () {
        return {
            routes: routes,
            screen: routes.LIST,
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
