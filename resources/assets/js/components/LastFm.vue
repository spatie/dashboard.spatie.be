<template>
    <grid :position="grid" modifiers="transparent">
        <section :class="addClassModifiers('last-fm', currentlyPlaying ? 'playing' : 'stopped')">
            <div class="last-fm__content" v-if="currentlyPlaying">
                <div :class="addClassModifiers('last-fm__cover', !hasCover ? 'empty' : '')" v-bind:style="{ backgroundImage: 'url(' + cover + ')' }">
                </div>
                <div class="last-fm__text">
                    <div class="last-fm__artist">
                        {{ artist }}
                    </div>
                    <div class="last-fm__track">
                        {{ trackName }}
                    </div>
                    <span class="last-fm__user">
                        {{ userName }}
                    </span>
                </div>
            </div>
            <div class="last-fm__background" v-if="currentlyPlaying" v-bind:style="{ backgroundImage: 'url(' + cover + ')' }"></div>

            <div class="last-fm__icon" v-if="!currentlyPlaying"></div>
        </section>
    </grid>
</template>

<script>
import echo from '../mixins/echo';
import Grid from './Grid';
import { addClassModifiers } from '../helpers';
import saveState from 'vue-save-state';

export default {

    components: {
        Grid,
    },

    mixins: [echo, saveState],

    props: ['grid'],

    data() {
        return {
            artist: '',
            trackName: '',
            artwork: '',
            userName: '',
        };
    },

    computed: {
        currentlyPlaying() {
            return !! this.artist;
        },
        hasCover() {
            return !! this.artwork;
        },
        cover() {
            return this.artwork || '/images/last-fm__cover.jpg';
        }
    },

    methods: {
        addClassModifiers,

        getEventHandlers() {
            return {
                'LastFm.NothingPlaying': () => {
                    this.artist = '';
                },
                'LastFm.TrackIsPlaying': response => {
                    this.artist = response.trackInfo.artist;
                    this.trackName = response.trackInfo.trackName;
                    this.artwork = response.trackInfo.artwork;
                    this.userName = response.userName;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'last-fm',
            };
        },
    },
};
</script>
