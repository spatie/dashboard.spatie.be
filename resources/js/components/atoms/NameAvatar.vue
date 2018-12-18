<template>
    <i class="ico_dc4f mr-3">
        <img :src="avatar" width="28" height="28" class="rounded-full" :alt="name" />
        <span>{{ hint }}</span>
    </i>
</template>

<style>
.ico_dc4f {
    position: relative;
    display: inline-block;
}
.ico_dc4f span {
    position: absolute;
    top: 0;
    left: 50%;
    transform: translate(-50%, -115%);
    display: none;
    white-space: nowrap;
    padding: 2px 4px;
    background: rgba(0, 0, 0, 0.9);
    color: #fff;
    font-size: 10px;
}
.ico_dc4f:hover span {
    display: block;
}
</style>

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
    props: ['name', 'gender', 'hint'],

    data() {
        return {
            avatar: getAvatar(this.name, this.gender),
        }
    },
}
</script>