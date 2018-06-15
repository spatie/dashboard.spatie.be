import axios from 'axios';

class WorldCup {

    async todaysMatches(city) {
        const endpoint = `matches/today`;

        const response = await this.performRequest(endpoint);

        return response.data;
    }

    async performRequest(endpoint) {
        const baseUrl = `http://worldcup.sfg.io/`;

        return await axios.get(baseUrl+endpoint);
    }
}

export default new WorldCup();
