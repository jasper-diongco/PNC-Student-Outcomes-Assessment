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
        setInterval(() => {
           this.renderPieChart(this.data, this.options); 
       }, 100);
        
    },
    watch: {
        data: function() {
            this.renderPieChart(this.data, this.options);
        }
    }
};
</script>
