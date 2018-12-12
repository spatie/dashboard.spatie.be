<template>
    <div
        class="fixed pin grid gap-spacing w-screen h-screen p-spacing font-normal leading-normal text-default bg-screen"
        :class="mode"
    >
        <tile :position="'a1:c2'">
            <h3>Members</h3>
        </tile>
        <tile :position="'a3:b12'">
            <div class="block-x37t">
                <table class="table-auto w-full">
                    <tr>
                        <th class="py-4 px-6 uppercase text-sm text-invers bg-warn"
                            v-for="heading in headings" >
                            {{ heading }}
                        </th>
                    </tr>
                    <tr v-for="mem in members" class="hover:bg-screen">
                        <td class="py-4 px-6 border-b border-screen text-center">{{ mem.trac }}</td>
                        <td class="py-4 px-6 border-b border-screen text-center">{{ mem.name }}</td>
                        <td class="py-4 px-6 border-b border-screen text-center">
                            <name-avatar :name="mem.trac" :gender="mem.gender" />
                        </td>
                        <td class="py-4 px-6 border-b border-screen text-center">
                            <button>Remove</button>
                        </td>
                    </tr>
                </table>
            </div>
        </tile>
        <tile :position="'c3:c12'">
            <div>
                <button class="bg-blue hover:bg-blue-dark text-white font-bold py-2 px-4 rounded">ReSync</button>
                <span>from Staff Tool</span>
            </div>

            <p>Archived list</p>
            <table class="table-auto w-full">
                <tbody v-for="mem in archived" class="hover:bg-screen">
                    <tr>
                        <td class="py-4 px-6">
                            <name-avatar :name="mem.trac" :gender="mem.gender" /> 
                            {{ mem.trac }}
                        </td>
                    </tr>
                    <tr>
                        <td class="py-4 px-6 border-b border-screen">
                            {{ mem.name }}
                            <button>Remove</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </tile>
    </div>
</template>

<style>
.block-x37t {
    height: 100%;
    overflow-y: scroll;
}
</style>

<script>
import echo from '../mixins/echo';
import saveState from 'vue-save-state';
import {emoji} from '../helpers';
import Tile from './atoms/Tile';
import NameAvatar from './atoms/NameAvatar';

export default {
    mixins: [echo, saveState],

    components: {
        Tile,
        NameAvatar,
    },

    props: ['members', 'archived'],

    data() {
        return {
            mode: 'light-mode',
            headings: ['Trac', 'Fullname', 'Avatar', 'Action'],
        };
    },

    methods: {
        getEventHandlers() {
            return {
                'Staff.UpdateAppearance': response => {
                    this.mode = response.mode;
                },
            };
        },

        getSaveStateConfig() {
            return {
                cacheKey: `staff`,
            };
        },
    },
};
</script>
