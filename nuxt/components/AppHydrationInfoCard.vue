<script>
import bar from "../assets/img/dashboard/bar.png"

export default {

    props: {
        currentLevels: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            bar: bar,
        }
    },

    computed: {
        currentHydrationText() {
            const current = this.currentLevels.current.toLocaleString() + "ml"
            return current
        },

        remainingHydrationText() {
            const remaining = Math.max(this.currentLevels.goal - this.currentLevels.current, 0).toLocaleString() + "ml"
            return remaining
        },

        hydrationGoalText() {
            return this.currentLevels.goal.toLocaleString() + "ml"
        },
    }
}
</script>

<template>
    <v-card class="pa-4 h-100 rounded-xl" >
        <v-row>
            <v-col>
                <v-card-title>Today's water intake</v-card-title>
                <v-card-text class="mt-6 mb-2">
                    <v-row class="d-flex align-center justify-center flex-wrap mx-auto">
                        <v-col class="pa-0 ma-0">
                            <p class="font-weight-regular">
                                <span class="text-h4">{{ currentHydrationText }}</span>
                                <span class="ml-2 text-body-1 text-grey-lighten-1"> / {{ hydrationGoalText }}</span>
                            </p>
                            <p class="mt-2 text-body-1 font-weight-bold">
                                <span>{{ remainingHydrationText }}</span>
                                <span class="ml-2 text-grey-lighten-1">remaining</span>
                            </p>
                        </v-col>
                        <!-- <v-col cols="3" class="pa-0 ma-0 text-right" >
                            <v-img height="100" :src="bar">
                            </v-img>
                            <v-icon
                                class="mx-auto align-center"
                                color="blue"
                                icon="mdi-cup-water"
                                size="80"
                            ></v-icon>
                        </v-col> -->
                    </v-row>
                </v-card-text>
            </v-col>
            <v-col cols="3">
                <v-img height="140" :src="bar">
                            </v-img>
            </v-col>
        </v-row>
        
        </v-card>
</template>