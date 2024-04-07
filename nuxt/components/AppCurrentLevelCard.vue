<script>

import lanyvizeel from "../assets/img/main/lanyvizeel.png"

export default {
    name: 'AppCurrentLevelCard',

    props: {
        currentLevels: {
            type: Object,
            required: true,
        },
        drinks: {
            type: Array,
            required: true,
        },
        customDrinks: {
            type: Array,
            required: true
        }
    },

    data() {
        return {
            addDrinkDialog: false,
            moveAnimation: false,
            rippleAnimation: false,
            lanyvizeel: lanyvizeel,
        }
    },

    computed: {
        percent() {
            const percent = Math.round((this.currentLevels.current / this.currentLevels.goal) * 100)
            return Math.min(percent, 100)
        },
    },

    methods: {
        animate() {
            this.moveAnimation = true
            setTimeout(() => {
                this.rippleAnimation = true
                setTimeout(() => {
                    this.moveAnimation = false
                    this.rippleAnimation = false
                }, 1000)
            }, 300)
        },

        drinkAdded() {
            this.addDrinkDialog = false
            this.$emit('update:drink')
        }
    },

    watch: {
        currentLevels(newVal, oldVal) {
            if (newVal.current > oldVal.current) {
                this.animate()
            }
        }
    }
}
</script>

<template>
    <div class="h-100">
        <v-card class="pa-6 h-100 rounded-xl">
            <v-row>
                <!-- <v-col cols="3">
                    <v-img class="lanyviz" :src="lanyvizeel" ></v-img>
                </v-col> -->
                <v-col>
                    <v-icon color="blue" icon="mdi-water" size="60" class="anim-water-drop"
                        :class="{ move: moveAnimation }">
                    </v-icon>
                    <v-card-title class="text-h4 text-center">
                        Current level
                    </v-card-title>
                    <v-card-text class="mx-auto justify-center text-center">
                        <v-progress-circular :model-value="percent" :size="192" :width="24" color="blue"
                            class="mx-auto mt-4">
                            <span class="text-h5">{{ percent }}%</span>
                            <div class="anim-water-vol" :class="{ ripple: rippleAnimation }"></div>
                        </v-progress-circular>
                        <v-btn size="x-large" @click="addDrinkDialog = true" class="mx-auto mt-6" style="display:block;">
                            Add drink
                        </v-btn>
                    </v-card-text>
                </v-col>
            </v-row>

        </v-card>
        <app-add-drink-dialog :value="addDrinkDialog" :customDrinks="customDrinks" :drinks="drinks"
            @close="addDrinkDialog = false" @update:drink="drinkAdded()"></app-add-drink-dialog>
    </div>
</template>

<style scoped>


.anim-water-drop {
    position: absolute;
    left: 0;
    right: 0;
    margin-left: auto;
    margin-right: auto;
    opacity: 0;
}

.anim-water-vol {
    background-color: #01579B;
    border-radius: 50%;
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: -1;
    opacity: 0;
}

.ripple {
    animation: ripple 1.5s ease-in-out;
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 100;
    }

    100% {
        transform: scale(1);
        opacity: 0;
    }

}

.move {
    animation: move 0.7s ease-in-out;
}

@keyframes move {
    from {
        translate: 0 0;
        opacity: 0;
    }

    to {
        translate: 0px 140px;
        opacity: 80;
    }
}
</style>