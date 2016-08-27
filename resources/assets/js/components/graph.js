import Chart from 'chart.js';
import '../helpers/global-chart-options';

export default {
    template: '<canvas class="graph"></canvas>',

    props: {
        labels: {},
        values: {},
        lineColor: {
            type: String,
        },
        backgroundColor: {
            type: String,
        },
    },

    ready() {

        let data = {
            labels: this.labels,

            datasets: [
                {
                    data: this.values,
                    lineTension: 0.4,
                    borderColor: this.lineColor,
                    backgroundColor: this.backgroundColor,
                    borderWidth: 2,
                    pointRadius: 0,
                },
            ],
        };

        let options = {
            scales: {
                xAxes: [{
                    display: false,
                    ticks: {
                        maxTicksLimit: 6,
                    },
                }],
                yAxes: [{
                    display: false,
                    ticks: {
                        beginAtZero:true,
                        suggestedMax: 100,
                    },
                }],
            },
            responsive: true,
            maintainAspectRatio: false,
        };

        new Chart(
            this.$el.getContext('2d'), {
                type: 'line',

                data,
                options,
            }
        );
    },
};