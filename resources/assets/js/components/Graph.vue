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
        this.renderChart();

        this.$watch('labels', this.updateChart);
        this.$watch('values', this.updateChart);
    },

    data() {
        return {
            options: {
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
            },
        };
    },

    computed: {
        dataset() {
            return {
                data: this.values,
                lineTension: 0.4,
                borderColor: this.lineColor,
                backgroundColor: this.backgroundColor,
                borderWidth: 2,
                pointRadius: 0,
            };
        },
    },

    methods: {
        renderChart() {
            this.chart = new Chart(
                this.$el.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: this.labels,
                        datasets: [this.dataset],
                    },
                    options: this.options,
                }
            );
        },

        updateChart() {
            this.chart.data.labels = this.labels;
            this.chart.data.datasets[0] = this.dataset;

            this.chart.update();
        },
    },
};
</script>
