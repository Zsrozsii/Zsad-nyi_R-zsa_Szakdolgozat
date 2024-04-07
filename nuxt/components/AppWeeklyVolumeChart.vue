<script>
import {
  Chart as ChartJS,
  Title,
  Tooltip,
  Legend,
  BarElement,
  CategoryScale,
  LinearScale
} from 'chart.js'
import { Bar } from 'vue-chartjs'

ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend)

export default {
    name: 'AppWeeklyVolumeChart',

    components: {
        Bar
    },

    props: {
        drinkLogElements: {
            type: Object,
            required: true,
        },
        historyLength: {
            type: Number,
            required: true,
        },
    },

    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: false,
                },
            }
        }
    },

    computed: {
        labels() {
            const today = new Date()
            const labels = []

            for (let i = 0; i < this.historyLength - 2; i++) {
                const current_date = this.offsetDate(today, -(this.historyLength - i-1))
                labels[i] = (current_date.getMonth()+1 + "." + current_date.getDate())
            }
            labels[this.historyLength - 2] = 'Yesterday'
            labels[this.historyLength - 1] = 'Today'

            return labels
        },

        volumeData() {
            const today = new Date()
            const volumeSum = [0, 0, 0, 0, 0, 0, 0]
            this.drinkLogElements.data.forEach((item) => {
                const logDate = Date.parse(item.created_at)
                const diffDays = this.getDateDiffInDays(new Date(logDate), today)
                volumeSum[this.historyLength-diffDays-1] = volumeSum[this.historyLength-diffDays-1] + item.volume
            })

            return {
                labels: this.labels,
                datasets: [
                    {
                        label: 'Volume',
                        backgroundColor: '#2196F3',
                        data: volumeSum,
                    }
                ]
            }
        },

    },

    methods: {
        getDateDiffInDays(a, b) {
            const _MS_PER_DAY = 1000 * 60 * 60 * 24;
            const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
            const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());

            return Math.floor((utc2 - utc1) / _MS_PER_DAY);
        },

        offsetDate(date, days) {
            const newDate = new Date(date)
            newDate.setDate(date.getDate() + days)
            return newDate 
        },
    },
}

</script>

<template>
    <v-card v-if="drinkLogElements" class="rounded-xl">
        <v-card-title>{{ historyLength }} day history</v-card-title>
        <v-card-item>
            <Bar class="h-auto w-100" :data="volumeData" :options="chartOptions"/>
        </v-card-item>
    </v-card>    
</template>

<style scoped>

</style>