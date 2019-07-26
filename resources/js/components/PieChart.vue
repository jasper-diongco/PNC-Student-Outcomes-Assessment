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
                }
            }
        };
    },
    mounted() {
        this.renderChart(this.data, this.options);
    }
};
</script>
