<template>
    <div class="grid -ml-1" style="grid-template-columns: auto 1fr auto;">
        <div class="text-3xl leading-none -mt-1" v-html="emoji('🍿')" />
        <span><slot>Upcoming events</slot></span>
        <btn-new />
    </div>
</template>

<script>
import { emoji } from '../../../helpers';
import BtnNew from './BtnNew';

export default {
    components: {
        BtnNew,
    },

    methods: {
      emoji,
    },
}
</script>