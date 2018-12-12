<template>
    <div>
        <ul class="list_4czb">
            <li v-for="member in people">
                <member-avatar :member="member" />
                <i class="_del bg-warn" v-html="emoji('✖️')" v-on:click="eject(member)"/>
            </li>
            <li>
                <button v-on:click="openPicker">
                    <i v-html="emoji('➕')" /> Add people
                </button>
            </li>
        </ul>

        <popover-picker :status="pickerStatus" :data="pickerData" v-on:submit="picked" />
    </div>
</template>

<style>
.list_4czb li {
    border: none;
    display: inline-block;
    margin-right: 5px;
}
.list_4czb li:hover ._del {
    display: block;
}
.list_4czb li:hover .ico_dc4f span {
    display: block;
}
 .list_4czb li:first-child {
    padding-top: 0.35rem;
 }
.list_4czb ._del {
    position: absolute;
    top: 50%;
    left: 0;
    transform: translate(0, -50%);
    display: none;
    width: 24px;
    height: 24px;
    padding-top: 4px;
    padding-left: 4px;
    border-radius: 50%;
    color: #fff;
}
</style>

<script>
import {emoji} from '../../helpers';
import MemberAvatar from './MemberAvatar';
import PopoverPicker from './PopoverPicker';
import MemberService from '../../services/member';

export default {
    components: {
        MemberAvatar,
        PopoverPicker,
    },

    methods: {
        emoji,
        openPicker() {
            this.pickerData = MemberService.toPickerData(MemberService.exclude(this.people)); 
            this.pickerStatus.visible = true;
        },
        picked(result) {
            let selected = MemberService.fromPickerResult(result);
            this.people = this.people.concat(selected);
        },
        eject(member) {
            this.people = MemberService.exclude([member], this.people);
        },
    },

    data() {
        return {
            people: [MemberService.one('phuongdm')],
            pickerStatus: {visible: false}, //send object to modify in child comp
            pickerData: [],
        }
    },
}
</script>