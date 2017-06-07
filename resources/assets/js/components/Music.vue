<template>
    <grid :position="grid" modifiers="transparent">
        <section :class="addClassModifiers('music', currentlyPlaying ? 'playing' : 'stopped')">
            <div class="music__content" v-if="currentlyPlaying">
                <div :class="addClassModifiers('music__cover', !hasCover ? 'empty' : '')" v-bind:style="{ backgroundImage: 'url(' + cover + ')' }">
                </div>
                <div class="music__text">
                    <div class="music__artist">
                        {{ artist }}
                    </div>
                    <div class="music__track">
                        {{ trackName }}
                    </div>
                    <span class="music__user">
                        {{ userName }}
                    </span>
                </div>
            </div>
            <div class="music__background" v-if="currentlyPlaying" v-bind:style="{ backgroundImage: 'url(' + cover + ')' }"></div>

            <div class="music__icon" v-if="!currentlyPlaying"></div>
        </section>
    </grid>
</template>

<script>
import echo from '../mixins/echo';
import Grid from './atoms/Grid';
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
            return this.artwork || '/images/music__cover.jpg';
        }
    },

    methods: {
        addClassModifiers,

        getEventHandlers() {
            return {
                'Music.NothingPlaying': () => {
                    this.artist = '';
                },
                'Music.TrackIsPlaying': response => {
                    this.artist = response.trackInfo.artist;
                    this.trackName = response.trackInfo.trackName;
                    this.artwork = response.trackInfo.artwork;
                    this.userName = response.userName;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: 'music',
            };
        },
    },
};
</script>
