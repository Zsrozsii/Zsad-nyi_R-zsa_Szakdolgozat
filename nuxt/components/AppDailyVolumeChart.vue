<script>

import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  LineElement,
  PointElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Line } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, LineElement, PointElement, Title, Tooltip, Legend)

export default {

    components: {
        Line
    },

    props: {
        drinkLogElements: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: true,
                scales: {
                    y: {
                        min: 0,
                    }
                },
                plugins: {
                    legend: false,
                },
            }
        }
    },

    computed: {
        chartData() {
            const date = new Date()
            let volumeData = []


            for (let i = 0; i <= date.getHours(); i++) {
                volumeData[i] = 0
            }

            this.drinkLogElements.forEach((item) => {
                const logDate = new Date(Date.parse(item.created_at))
                
                volumeData[logDate.getHours()] = volumeData[logDate.getHours()] + item.volume
            })

            const chartData = {
                labels: ["0h", "1h", "2h", "3h", "4h", "5h", "6h", "7h", "8h", "9h", "10h", "11h", "12h", "13h", 
                            "14h", "15h", "16h", "17h", "18h", "19h", "20h", "21h", "22h", "23h"],
                datasets: [{
                    label: 'volume',
                    data: volumeData,
                    borderColor: '#2196F3',
                    tension: 0.3,
                    borderJoinStyle: 'round',
                }],
                
            }
            return chartData
        },
    },
}
</script>

<template>
    <v-card v-if="drinkLogElements" class="rounded-xl" >
        <v-card-title>Daily intake</v-card-title>
        <v-card-item>
            <Line class="w-100 h-auto" :data="chartData" :options="chartOptions"/>
        </v-card-item>
    </v-card>    
</template>

<style scoped>

</style>