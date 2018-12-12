const STORE_KEY = '_members';

export default {
    all() {
        let data = localStorage.getItem(STORE_KEY);
        return data ? JSON.parse(data) : [];
    },

    one(trac) {
        let members = this.all();

        for (let one of members) {
            if (one.trac === trac) return one;
        }

        return null;
    },

    store(members) {
        localStorage.setItem(STORE_KEY, JSON.stringify(members));
    },

    exclude(skipMembers, dataSource = null) {
        let skipTracs = [];

        for (let one of skipMembers) {
            skipTracs.push(one.trac);
        }

        let members = [];
        if (!dataSource) dataSource = this.all();

        for (let one of dataSource) {
            if (skipTracs.indexOf(one.trac) === -1) {
                members.push(one);
            }
        }
        
        return members;
    },

    toPickerData(members) {
        let arr = [];

        for (let one of members) {
            arr.push({
                value: one.trac,
                label: one.name,
                checked: false,
            });
        }

        return arr;
    },

    fromPickerResult(result) {
        let members = [];
        let tracs = [];

        for(let one of result) {
            tracs.push(one.value);
        }

        for (let one of this.all()) {
            if (tracs.indexOf(one.trac) !== -1) {
                members.push(one);
            }
        }

        return members;
    },
}
