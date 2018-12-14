<template>
    <tile :position="position">
        <h2 class="truncate capitalize">Announcements</h2>
    </tile>
</template>

<script>
import Tile from './atoms/Tile';

export default {
    components: {
        Tile,
    },

    props: ['position'],
}
</script>