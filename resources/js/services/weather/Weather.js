import axios from 'axios';

class Weather {
    async forCity(city) {
        const key = window.dashboard.openWeatherMapKey;

        const response = await axios.get(
            `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${key}&units=metric`
        );

        return response.data;
    }

    getEmoji(weatherId, isNight) {
        const group = parseInt(weatherId.toString().charAt(0));

        if (group === 2) {
            return '⛈';
        }

        if (group === 3) {
            return '☔';
        }

        if (group === 5) {
            return '☔';
        }

        if (group === 6) {
            return '☃';
        }

        if (weatherId >= 700 && weatherId <= 762) {
            return '🌫';
        }

        if (weatherId === 781) {
            return '🌪';
        }

        if (weatherId === 771) {
            return '💨';
        }

        if (weatherId === 800) {
            return isNight ? '🌌' : '☀';
        }

        if (weatherId === 801) {
            return '⛅';
        }

        if (group === 8) {
            return '☁';
        }

        return '🧐';
    }
}

export default new Weather();
