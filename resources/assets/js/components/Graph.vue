<template>
    <canvas class="graph"></canvas>
</template>

<script>
import Chart from 'chart.js';

export default {

    props: {
        labels: {
            type: Array,
            required: true,
        },
        values: {
            type: Array,
            required: true,
        },
        lineColor: {
            type: String,
            required: true,
        },
        backgroundColor: {
            type: String,
            required: true,
        },
    },

    mounted() {
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
