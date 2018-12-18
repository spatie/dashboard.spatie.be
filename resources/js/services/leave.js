import moment from 'moment';

export default {
    leaveStatusToday(leaveInfo) {
        let today = moment().format('YYYY-MM-DD');

        return this.leaveStatus(leaveInfo, today);
    },

    leaveStatusTomorrow(leaveInfo) {
        let tomorrow = moment().add(1, 'days').format('YYYY-MM-DD');

        return this.leaveStatus(leaveInfo, tomorrow);
    },

    leaveStatus(leaveInfo, targetDate) {
        for(let request of leaveInfo) {
            let [date, half] = request.split(' ');
            
            if (date === targetDate) {
                return half === 'undefined' ? 'FULL' : half;
            }
        }

        return false;
    }
}