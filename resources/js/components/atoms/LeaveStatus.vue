<template>
    <span>
        <i v-if="status === 'AM'">◓</i>
        <i v-else-if="status === 'PM'">◒</i>
        <i v-else>◉</i>
        <span>{{ label }}</span>
    </span>
</template>

<script>
export default {
    props: {
        status: {default: 'FULL'},
        label: {default: 'today'},
    }
}
</script>