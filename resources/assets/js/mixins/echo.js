import { forIn } from 'lodash';

export default {
    created() {

        forIn(this.getEventHandlers(), (handler, eventName) => {

            let fullyQualifiedEventName = `.App.Events.${eventName}`;

            Echo.private('dashboard')
                .listen(fullyQualifiedEventName, (eventName) => {
                    handler(eventName);
                });
        });
    },
};
