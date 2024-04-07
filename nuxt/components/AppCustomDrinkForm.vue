<script>
import { defineComponent } from 'vue'
import { mutation } from "gql-query-builder";
import { gt, gte, lte, minLength, required } from "~/utils/validations.js";

import { Icons } from "../utils/icons"

export default defineComponent({
    name: "AppCustomDrinkForm",

    props: {
        custom_drink: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            icons: Icons,
            drink: {
                ...this.custom_drink,
                effectiveness: (this.custom_drink.effectiveness ? this.custom_drink.effectiveness * 100 : 100)
            },
            form: null,
            isLoading: false,
        }
    },

    computed: {
        isNew() {
            return this.drink.id === "New"
        },

        rules() {
            return {
                name: [required, value => minLength(value, 3)],
                volume: [required, value => gt(value, 0)],
                caffeine: [required, value => gte(value, 0)],
                sugar: [required, value => gte(value, 0)],
                effectiveness: [required, value => gte(value, 0), value => lte(value, 100)],
            }
        },

        inputData() {
            return {
                "name": this.drink.name,
                "default_ml": parseInt(this.drink.default_ml),
                "sugar": parseFloat(this.drink.sugar),
                "caffeine": parseInt(this.drink.caffeine),
                "effectiveness": parseInt(this.drink.effectiveness) / 100,
                "icon": this.drink.icon,
            }
        },

        createCustomDrinkMutation() {
            return mutation({
                operation: "createCustomDrink",
                variables: {
                    data: {
                        name: "data",
                        type: "CreateCustomDrinkInput!",
                        value: this.inputData
                    }
                },
                fields: [
                    'id'
                ]
            })
        },

        iconKeys() {
            return Object.keys(this.icons)
        },

        updateCustomDrinkMutation() {
            return mutation({
                operation: "updateCustomDrink",
                variables: {
                    id: {
                        name: 'id',
                        type: 'ID!',
                        value: this.drink.id
                    },
                    data: {
                        name: 'data',
                        type: 'UpdateCustomDrinkInput!',
                        value: this.inputData
                    }
                },
                fields: [
                    'id'
                ]
            })
        }
    },

    methods: {

        async save() {
            if (!this.isNew) {
                await this.updateCustomDrink()
            }
            else {
                await this.createCustomDrink()
            }
        },

        cancel() {
            if (this.isNew) {
                this.$emit('cancel:create')
            }
            else {
                this.$emit('cancel:update')
            }
        },

        deleteDrink() {
            this.$emit('delete:drink', this.drink)
        },

        async createCustomDrink() {
            if (!this.form) {
                return
            }

            this.isLoading = true
            try {
                const { data } = await this.$apollo.mutate({
                    mutation: gql`
                        ${this.createCustomDrinkMutation.query}
                    `,
                    variables: this.createCustomDrinkMutation.variables,
                })
                this.$emit('created:drink')
                this.isLoading = false
                return data
            }
            catch (error) {
                console.log(error)
                this.error = error
                this.isLoading = false
                this.$snackbar.add({
                    type: 'error',
                    text: 'Failed to create drink'
                })
            }

        },

        async updateCustomDrink() {
            if (!this.form) {
                return
            }

            this.isLoading = true
            try {
                const { data } = await this.$apollo.mutate({
                    mutation: gql`
          ${this.updateCustomDrinkMutation.query}
        `,
                    variables: this.updateCustomDrinkMutation.variables,
                })
                this.$emit('updated:drink')
                this.isLoading = false
                return data
            }
            catch (error) {
                console.log(error)
                this.error = error
                this.isLoading = false
                this.$snackbar.add({
                    type: 'error',
                    text: 'Failed to update drink'
                })
            }
        }
    },
})
</script>

<template>
    <v-form ref="form" @submit.prevent v-model="form">
        <div class="px-0 my-2">
            <v-row>
                <v-col cols="12" sm="4" md="3">
                    <v-text-field label="Drink name" required v-model="drink.name" :rules="rules.name"
                        :disabled="isLoading"></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" md="3">
                    <v-text-field label="Default volume [ml]" required type="number" v-model="drink.default_ml"
                        :rules="rules.volume" :disabled="isLoading"></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" md="3">
                    <v-text-field label="Caffeine [mg]*" required type="number" v-model="drink.caffeine"
                        :rules="rules.caffeine" :disabled="isLoading"></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" md="3">
                    <v-text-field label="Sugar [g]*" required type="number" v-model="drink.sugar" :rules="rules.sugar"
                        :disabled="isLoading"></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" md="3">
                    <v-text-field label="Effectiveness" required type="number" v-model="drink.effectiveness" min="0"
                        max="100" :rules="rules.effectiveness" :disabled="isLoading"></v-text-field>
                </v-col>
                <v-col cols="12" sm="4" md="3">
                    <v-select label="Icon" :items="iconKeys" v-model="drink.icon">
                        <template v-slot:selection="{ item, index }">
                            <v-img height="25" width="25" :src="icons[item.title]"></v-img>
                        </template>
                        <template v-slot:item="{ item, index, props }">
                            <!-- <v-img height="50" width="50" :src="icons[item.title]"></v-img>  -->
                            <v-list-item v-bind="props" title="">
                                <v-img height="50" width="50" :src="icons[item.title]"></v-img> 
                            </v-list-item>
                        </template>
                    </v-select>
                </v-col>
            </v-row>
            <small>* In 100 ml</small>
            <v-row>
                <v-col cols="2">
                    <v-btn v-if="!isNew" color="red" variant="text" @click="deleteDrink()" :disabled="isLoading">
                        Delete
                    </v-btn>
                </v-col>
                <v-col cols="10" class="text-right">
                    <v-btn color="blue-darken-1" variant="text" :disabled="isLoading" @click="cancel()">
                        Cancel
                    </v-btn>
                    <v-btn color="blue-darken-1" variant="text" type="submit" :disabled="isLoading" @click="save()">
                        Save
                    </v-btn>
                </v-col>
            </v-row>

        </div>
    </v-form>
</template>

<style scoped></style>