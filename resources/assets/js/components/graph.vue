<template>
    <canvas class="graph"></canvas>
</template>

<script>
import Chart from 'chart.js';

export default {

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
        const data = {
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

        const options = {
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
                        beginAtZero: true,
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
</script>
