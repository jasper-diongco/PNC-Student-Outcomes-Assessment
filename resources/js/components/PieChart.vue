<script>
import { Pie } from "vue-chartjs";

export default {
    extends: Pie,
    props: ["data"],
    data() {
        return {
            // data: {
            //     datasets: [
            //         {
            //             data: [10, 20, 55],
            //             backgroundColor: ["red", "orange", "yellow"]
            //         }
            //     ],
            //     labels: ["Red", "Orange", "Yellow"]
            // },
            options: {
                animation: false,
                responsive: true,
                legend: {
                    position: "top"
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                },
                layout: {
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                plugins: {
                    labels: [
                        {
                            render: "percentage",
                            position: "border",
                            precision: 2
                        },
                        {
                            render: "label",
                            precision: 2
                        }
                    ]
                }
            }
        };
    },
    computed: {
        chartData: function() {
            return this.data;
        }
    },
    methods: {
        renderPieChart() {
            this.renderChart(this.data, this.options);
        }
    },
    mounted() {
        this.renderPieChart(this.data, this.options);
    },
    watch: {
        data: function() {
            this._chart.destroy();
            //this.renderChart(this.data, this.options);
            this.renderPieChart();
        }
    }
};
</script>
