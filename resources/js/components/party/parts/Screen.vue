<template>
    <div>
        <div class="flex flex-start mb-5">
            <button class="btn-mn29 mr-2">
                <div v-html="emoji('🔙')" v-on:click="$emit('close')"></div>
            </button>
            <slot name="header"></slot>
        </div>
        <div>
            <slot></slot>
        </div>
    </div>
</template>

<style>
.btn-mn29 .emoji{
    height: 1.5em;
}
.btn-mn29 {
    height: 1.5em;
    line-height: 1;
}
</style>

<script>
import { emoji } from '../../../helpers';

export default {
    components: {
    },

    methods: {
      emoji,
    },
}
</script>