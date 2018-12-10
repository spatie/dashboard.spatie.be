<template>
    <modal>
        <div slot="header" class="grid" style="grid-template-columns: 1fr auto ;">
            <input type="text" name="event_name" placeholder="Event name" 
                id="input_98fu" />
            <button title="Pick template" class="btn_24hy">
                <div v-html="emoji('🗒️')"></div>
            </button>
        </div>
        <div>
            <add-people />
        </div>
    </modal>
</template>

<style>
#input_98fu {
    border-bottom: 1px solid #8795A1;
}
.btn_24hy {
    width: 1.2em;
}
.btn_24hy .emoji {
    height: 1.2em;
}
</style>

<script>
import {emoji} from '../../helpers';
import Modal from './Modal';
import AddPeople from './AddPeople';

export default {
    components: {
        Modal,
        AddPeople,
    },

    methods: {
        emoji,
    },
}
</script>