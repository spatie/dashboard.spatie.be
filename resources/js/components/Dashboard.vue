<template>
    <div class="dashboard" :style="gridTemplate">
    	<slot></slot>
    </div>
</template>

<script>
export default {
    props: ['rows', 'columns'],

    computed: {
        gridTemplate() {
            return `grid-template: repeat(${this.rows}, 1fr) / repeat(${this.columns}, 1fr);`;
        },
    },
};
</script>
