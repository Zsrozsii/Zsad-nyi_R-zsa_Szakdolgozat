<script setup>

definePageMeta({ middleware: 'auth' }) 



</script>

<script>
    import AppLogListElement from "~/components/AppLogListElement.vue";
    import AppWeeklyVolumeChart from "~/components/AppWeeklyVolumeChart.vue";
    import AppDailyVolumeChart from "~/components/AppDailyVolumeChart.vue";

    import {mutation, query} from "gql-query-builder";
    
    export default {
        name: "log",

        components: {AppLogListElement, AppWeeklyVolumeChart, AppDailyVolumeChart},

        mounted() {
            this.updateDrinkLogElements()
        },

        data() {
            return {
                drinkLogElements: null,
                historyLength: 7,
            }
        },

        computed: {
            drinkLogListElementsToday() {
                var d = new Date();
                const logsToday = this.drinkLogElements.data.filter(function (el) {
                    const logDate = new Date(Date.parse(el.created_at))
                    return d.getFullYear() == logDate.getFullYear() &&
                            d.getMonth() == logDate.getMonth() &&
                            d.getDate() == logDate.getDate()
                })
                return logsToday
            },

            drinkLogElementsQuery() {
                var d = new Date();
                d.setDate(d.getDate() - this.historyLength-1);
                const fromString = d.getDate() + '-' + d.getMonth() + '-' + d.getDate()

                return query({
                    operation: 'drinkLogElements',
                    variables: {
                        first: {
                            name: 'first',
                            type: 'Int!',
                            value: 500,
                        },
                        from: {
                            name: 'from',
                            type: 'Date!',
                            value: fromString
                        }
                    },
                    fields: [
                        {
                            data: [
                                'id',
                                'drink_name',
                                'volume',
                                'sugar',
                                'caffeine',
                                'created_at'
                            ],
                            paginatorInfo: ['currentPage', 'lastPage'],
                        },
                        
                    ],
                })
            },
        },

        methods: {
            offsetDate(date, days) {
                const newDate = new Date(date)
                newDate.setDate(date.getDate() + days)
                return newDate 
            },

            updateDrinkLogElements() {
                this.$apollo.queries.drinkLogElements.refetch()
            }
        },

        
        apollo: {
            drinkLogElements: {
                query() {
                    return gql`
                        ${this.drinkLogElementsQuery.query}
                    `
                },
                variables() {
                    return this.drinkLogElementsQuery.variables
                },
                error(error) {
                    this.error = error
                    console.log(error)
                },
            }
        }
    }
</script>

<template>
    <v-sheet class="bg">
        <v-container>
            <v-row class="mt-4" v-if="drinkLogElements">
                <v-col cols="12" md="6" >
                    <AppDailyVolumeChart
                        
                        :drinkLogElements="drinkLogListElementsToday"
                    ></AppDailyVolumeChart>
                </v-col>
                <v-col cols="12" md="6" >
                    <AppWeeklyVolumeChart
                        :drinkLogElements="drinkLogElements"
                        :historyLength="historyLength" 
                    ></AppWeeklyVolumeChart>
                </v-col>
            </v-row>
            <v-row>
                <v-col>
                    <AppLogListElement
                        @updated:log="updateDrinkLogElements()"
                    ></AppLogListElement>
                </v-col>
            </v-row>
        </v-container>
    </v-sheet>
</template>

<style scoped>
.bg {
  min-height: 100%;
  background: url('../assets/img/dashboard/bg.jpg');
  background-size: cover;
}
</style>