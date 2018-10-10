<template>
    <tile :position="position">
        <section :class="currentlyPlaying ? 'playing' : 'stopped'">
            <div v-if="currentlyPlaying">
                <div :class="!hasCover ? 'empty' : ''"
                     v-bind:style="{ backgroundImage: 'url(' + cover + ')' }">
                </div>
                <div>
                    <div>
                        {{ artist }}
                    </div>
                    <div>
                        {{ trackName }}
                    </div>
                    <span>
                        <span></span>
                        {{ userName }}
                    </span>
                </div>
            </div>
            <div v-if="currentlyPlaying"
                 v-bind:style="{ backgroundImage: 'url(' + cover + ')' }"></div>
        </section>
    </tile>
</template>

<script>
import echo from '../mixins/echo';
import Tile from './atoms/Tile';
import saveState from 'vue-save-state';

export default {
    components: {
        Tile,
    },

    mixins: [echo, saveState],

    props: ['position'],

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
            return !!this.artist;
        },
        hasCover() {
            return !!this.artwork;
        },
        cover() {
            return this.artwork || '/images/music__cover.jpg';
        },
    },

    methods: {
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
