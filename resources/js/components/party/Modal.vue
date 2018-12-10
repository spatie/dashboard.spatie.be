<template>
    <div class="grid gap-padding h-full markup" style="grid-template-rows: auto 1fr;">
        <div class="grid" style="grid-template-columns: auto 1fr;">
            <button class="btn-mn29 mr-2">
                <div v-html="emoji('🔙')" ></div>
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
import { emoji } from '../../helpers';

export default {
    components: {
    },

    methods: {
      emoji,
    },
}
</script>