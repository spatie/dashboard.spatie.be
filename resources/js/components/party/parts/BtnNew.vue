<template>
    <button class="ux-3rtx" v-on:click="openNew">
        <i class="text-2xl leading-none" v-html="emoji('➕')" />
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
import { emoji } from '../../../helpers';
import EventBus from '../../../services/event-bus';
import routes from '../services/route';

export default {
    methods: {
      emoji,
      openNew() {
          EventBus.$emit('party:navigate', routes.NEW)
      },
    },
}
</script>