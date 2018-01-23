import { forIn } from 'lodash';

export default {
    created() {
        forIn(this.getEventHandlers(), (handler, eventName) => {
            console.log('subscribed', eventName);
            this.$root.echo
                .private('dashboard')
                .listen(`.App.Events.${eventName}`, response => (console.log('heard', eventName), handler(response)));
        });
    },
};
