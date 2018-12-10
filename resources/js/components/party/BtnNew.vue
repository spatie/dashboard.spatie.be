<template>
    <button
        class="ux-3rtx"
        >
        <div class="text-3xl leading-none -mt-1" v-html="emoji('➕')" />
    </button>
</template>

<style>
.ux-3rtx img {
    transition: transform .15s; 
}
.ux-3rtx:hover img {
    transform:rotate(90deg);
}
</style>

<script>
import { emoji } from '../../helpers';

export default {
    methods: {
      emoji,
    },
}
</script>