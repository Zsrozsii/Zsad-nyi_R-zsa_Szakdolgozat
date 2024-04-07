<script>

import {mutation, query} from "gql-query-builder"
import { Icons } from "../utils/icons"

export default {
    name: 'AppFavouriteDrinks',

    props: {
        title: {
            type: String,
            required: true
        },
        drinks: {
            type: Array,
            required: true
        },
    },

    computed: {
        drinkMutation() {
            return mutation({
                operation: 'addDrink',
                fields: [
                    "id"
                ],
                variables: {
                    data: {
                        name: 'data',
                        type: 'DrinkInput!',
                    }
                }
            })
        },

    },

    methods: {
        async addDrink(drink) {
            const inputData = {
                data: {
                    id: drink.id,
                    volume: drink.default_ml,
                    is_custom: drink.__typename ==="CustomDrink"
                }
            }

            try {
                const { data } = await this.$apollo.mutate({
                mutation: gql`
                    ${this.drinkMutation.query}
                    `,
                    variables: inputData,
                })

                this.$emit('added:drink')
                return data
            }
            catch (error) {
                console.log(error.message)
                this.error = error
                this.$snackbar.add({
                    type: 'error',
                    text: 'Failed to add drink'
                })
            }
        },
    },

}
</script>

<template>
<v-card class="py-3 px-3 rounded-xl">
    <v-card-item>
        <v-card-title class="text-h6 mb-2">{{ title }}</v-card-title>
    </v-card-item>
    <v-card-text>
        <v-row class="justify-start">
        <v-col class="px-2 flex-grow-0" v-for="drink in drinks" :key="drink.id" >
            <v-btn
                :prepend-icon="drink.icon ?? 'mdi-cup'"
                @click="addDrink(drink)"
            >
                <template v-slot:prepend>
                    <v-img height="22" width="22" :src="Icons[drink.icon]" v-if="Icons[drink.icon]"></v-img>
                    <v-icon color="blue" v-else></v-icon>
                </template>
                {{ drink.name }}
            </v-btn>
        </v-col>
    </v-row>
    </v-card-text>
</v-card>
</template>

<style scoped>

</style>