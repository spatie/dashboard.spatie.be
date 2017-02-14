<template>
    <grid :position="grid" modifiers="overflow padded">
       <section class="new-relic">
           <h1 class="new-relic__title">Servers</h1>
           <ul class="new-relic__servers">
               <li v-for="server in servers"  class="new-relic__server">
                   <h4 class="new-relic__server__title"><span class="new-relic__server__status" v-bind:class="[server.health]"></span> {{ server.name }}</h4>
                   <div class="new-relic__server__data"><small>CPU</small> {{ server.cpu }}% | Mem: {{ server.memory }}%</div>
                   <div class="new-relic__server__data"><small>IO</small> {{ server.disk_io }}% | Disk: {{ server.fullest_disk }}%</div>
               </li>
           </ul>
       </section>
    </grid>
</template>

<script>
import { relativeDate } from '../helpers';
import echo from '../mixins/echo';
import Grid from './Grid';
import saveState from 'vue-save-state';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

    data() {
        return {
            servers: [],
        };
    },

    methods: {
        relativeDate,

        getEventHandlers() {
            return {
                'Relic.ServerListFetched': response => {
                    this.servers = response.servers;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'new-relic',
            };
        },
    },
};
</script>
