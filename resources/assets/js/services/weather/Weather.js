import axios from 'axios';

class Weather {
    async conditions(city) {
        const query = `select item.condition from weather.forecast where woeid in (select woeid from geo.places(1) where text='${city}') and u='c'`;

        const response = await this.performQuery(query);

        return response.data.query.results.channel.item.condition;
    }

    async performQuery(query) {
        const endpoint = `https://query.yahooapis.com/v1/public/yql?q=${query}&format=json`;

        return await axios.get(endpoint);
    }
}

export default new Weather();
