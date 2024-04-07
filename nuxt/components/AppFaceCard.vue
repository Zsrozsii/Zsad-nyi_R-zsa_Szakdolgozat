<script>

import fej1 from "../assets/img/dashboard/fej/1.png"
import fej2 from "../assets/img/dashboard/fej/2.png"
import fej3 from "../assets/img/dashboard/fej/3.png"
import fej4 from "../assets/img/dashboard/fej/4.png"

export default {
    name: 'AppFaceCard',

    props: {
        score: {
            type: Number,
            required: true
        }
    },

    data() {
        return {
            fej1: fej1,
            fej2: fej2,
            fej3: fej3,
            fej4: fej4,
        }
    },

    computed: {
        image() {
            if(this.score >= 5) {
                return {
                    'img': fej1,
                    'color': 'green-lighten-1'
                }
            }

            else if(this.score >= 3) {
                return {
                    'img': fej2,
                    'color': 'light-blue-darken-3'
                }
            }
            else if(this.score >= 1) {
                return {
                    'img': fej3,
                    'color': 'yellow-darken-2'
                }
            }
            else {
                return {
                    'img': fej4,
                    'color': 'red'
                }
            }
        },

        icon() {
            if(this.score >= 5) {
                return {
                    'icon': 'mdi-emoticon-excited-outline',
                    'color': 'green'
                }
            }

            else if(this.score >= 3) {
                return {
                    'icon': 'mdi-emoticon-happy-outline',
                    'color': 'blue'
                }
            }
            else if(this.score >= 1) {
                return {
                    'icon': 'mdi-emoticon-neutral-outline',
                    'color': 'yellow'
                }
            }
            else {
                return {
                    'icon': 'mdi-emoticon-sad-outline',
                    'color': 'red'
                }
            }
        }
    }
}

</script>

<template>
<v-card class="pa-2 text-center rounded-xl" :color="image.color" >
    <v-card-text class="my-auto h-100">
        <!-- <v-icon
            class="h-100"
            :color="icon.color"
            :icon="icon.icon"
            size="120"
            >
        </v-icon> -->

        <v-img
            class="faceimg"
            :src="image.img"
        >
        </v-img>
    </v-card-text>
</v-card>

</template>

<style scoped>
    .faceimg {
        height: 140px

    }
</style>