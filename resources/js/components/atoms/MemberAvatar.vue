<template>
    <name-avatar :name="member.trac" :gender="member.gender" :hint="member.name" />
</template>

<script>
import NameAvatar from './NameAvatar';

export default {
    components: {
        NameAvatar,
    },
    
    props: ['member'],
}
</script>