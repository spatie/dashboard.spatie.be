<template>
    <ul class="list_4m8x grid">
        <li></li>
        <li>
            <button>
                <i v-html="emoji('➕')" /> Add people
            </button>
        </li>
    </ul>
</template>

<style>
.list_4m8x li {
    border: none;
}
.list_4m8x {
    grid-template-rows: auto;
}
</style>

<script>
import {emoji} from '../../../helpers';
import NameAvatar from '../../atoms/NameAvatar';

export default {
    components: {
        NameAvatar,
    },

    methods: {
        emoji,
    },
}
</script>