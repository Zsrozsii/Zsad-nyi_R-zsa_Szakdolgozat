<script>

import { mutation } from "gql-query-builder";
import { gt, required } from "~/utils/validations.js";

export default {
    name: "AppAddDrinkDialog",

    props: {
        value: {
            type: Boolean,
            default: false,
        },
        drinks: {
            type: Array,
            required: true,
        },
        customDrinks: {
            type: Array,
            required: true,
        },
    },

    data() {
        return {
            form: true,
            drink: null,
            dialog: this.value,
            volume: 200,
            error: null
        }
    },

    computed: {
        rules() {
            return {
                drink: [required],
                volume: [required, value => gt(value, 0)],
            }
        },

        allDrinks() {
            return this.drinks.concat(this.customDrinks)
        },

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
        async addDrink() {
            this.$refs.form.validate()

            if (!this.form) {
                return
            }

            const inputData = {
                data: {
                    id: this.drink.id,
                    volume: parseInt(this.volume),
                    is_custom: this.drink.__typename == "CustomDrink" ? true : false
                }
            }

            try {
                const { data } = await this.$apollo.mutate({
                    mutation: gql`
                ${this.drinkMutation.query}
              `,
                    variables: inputData,
                })

                this.dialog = false
                this.$emit('update:drink')
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

        closeDialog() {
            this.dialog = false
            this.$emit('close')
        }
    },

    watch: {
        value(value) {
            this.dialog = value
        }
    },
}


</script>

<template>
    <v-dialog persistent v-model="dialog" width="auto" >
        <v-card >
            <v-card-title>Add drink</v-card-title>
            <v-divider></v-divider>
            <v-card-text>
                <v-form ref="form" @submit.prevent v-model="form" >
                    <div class="mb-7" >
                        <v-radio-group v-model="drink" column :rules="rules.drink" class="drink-select" >
                            <v-radio v-for="drink in allDrinks" :label="drink.name" :value="drink"></v-radio>
                        </v-radio-group>
                    </div>
                    <div>
                        <v-text-field v-model="volume" class="bottom" label="Volume" hide-details="auto" type="number"
                            :rules="rules.volume"></v-text-field>
                    </div>
                </v-form>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn color="blue-darken-1" variant="text" @click="closeDialog()">
                    Close
                </v-btn>
                <v-btn color="blue-darken-1" variant="text" @click="addDrink()">
                    Add
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>


<style scoped>
.drink-select {
    height: 10%;
    max-height: 200px;
    overflow: auto;
}
</style>