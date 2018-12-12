<template>
    <i>
        <img :src="avatar" width="24" height="24" class="rounded-full" />
    </i>
</template>

<script>
const getAvatar = function(name, gender) {
    let bgColor = '0D8ABC';

    if (typeof gender !== 'undefined' && gender === 'female') {
        bgColor = 'FF495C';
    }

    return 'https://ui-avatars.com/api/?background='+bgColor+'&color=fff&name='+name;
    // return 'images/'+charAt(name)+'.png';
}

export default {
    props: ['name', 'gender'],

    data() {
        return {
            avatar: getAvatar(this.name, this.gender),
        }
    },
}
</script>