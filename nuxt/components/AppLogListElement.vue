<script setup>

definePageMeta({ middleware: 'auth' }) 

</script>

<script>
    import {mutation, query} from "gql-query-builder";

    export default {
        name: "AppLogListElement",

        data() {
            return {
                drinkLogElements: null,
                drinkLogElementsData: null,
                error: null,
                currentPage:1,

                loading: false
            }
        },

        mounted() {
            this.$apollo.queries.drinkLogElements.refetch()
        },

        computed: {
            drinkLogElementsQuery() {
                return query({
                    operation: 'drinkLogElements',
                    variables: {
                        first: {
                            name: 'first',
                            type: 'Int!',
                            value: 10,
                        },
                        page: {
                            name: 'page',
                            type: 'Int!',
                            value: this.currentPage,
                        },

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

            deleteDrinkLogElementMutation() {
                return mutation({
                    operation: 'deleteDrinkLogElement',
                    variables: {
                        id: {
                            name: 'id',
                            type: 'ID!'
                        },
                    },
                    fields: [
                        'id'
                    ]
                })
            }
        },

        methods: {
            async load ({ done }) {
                if(!this.drinkLogElements) {
                    return
                }

                if(this.drinkLogElementsData.paginatorInfo.currentPage === this.drinkLogElementsData.paginatorInfo.lastPage) {
                    done('empty')
                }
                else {
                    await this.queryNextPage()
                    done('ok')
                }
            },

            async queryNextPage() {

                const inputData = {
                    page: this.drinkLogElementsData.paginatorInfo.currentPage + 1,
                    first: 10
                }

                try {
                    const { data } = await this.$apollo.query({
                        query: gql`
                        ${this.drinkLogElementsQuery.query}
                        `,
                        variables: inputData,
                    })

                    this.drinkLogElementsData.data.push(...data.drinkLogElements.data)
                    this.drinkLogElementsData.paginatorInfo = data.drinkLogElements.paginatorInfo

                }
                catch (error) {
                    console.log(error.message)
                    this.error = error
                }
            },

            async deleteDrinkLogElement(id) {
                this.loading=true
                const inputData = {
                    id: id
                }


                try {
                    const data = await this.$apollo.mutate({
                        mutation: gql`
                                ${this.deleteDrinkLogElementMutation.query}
                            `,
                        variables: inputData 
                    })
                    this.$snackbar.add({
                        type: 'success',
                        text: 'Element removed'
                    })

                    this.$emit('updated:log')
                    await this.$apollo.queries.drinkLogElements.refetch()
                }
                catch {
                    console.log(error.message)
                    this.error = error
                    this.$snackbar.add({
                        type: 'error',
                        text: 'Failed to delete element'
                    })
                }
                this.loading=false
            },

            formatDate(date_str) {
                const date = new Date(Date.parse(date_str))

                var options = { year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric'  };

                const date_out_str = date.toLocaleDateString("en-US", options)

                return date_out_str
            }
        },

        watch: {
            drinkLogElements() {
                const data = this.drinkLogElements.data
                const paginatorInfo = this.drinkLogElements.paginatorInfo

                this.drinkLogElementsData = {
                    data: [...data],
                    paginatorInfo: {...paginatorInfo}
                }
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
    <v-card class="rounded-xl">
        <v-card-title class="text-h5 font-weight-normal mt-5 ml-2">History</v-card-title>
        <v-card-item>
            <v-infinite-scroll v-if="drinkLogElementsData" :items="drinkLogElementsData.data" :onLoad="load">
                <template v-for="(item) in drinkLogElementsData.data" :key="item">
                    <div class="my-2">
                        <v-row class="mx-0 justify-center align-center">
                            <v-col cols="3" sm="2">
                                <span class="text-body-1">{{ item.drink_name }}</span>
                            </v-col>
                            <v-col cols="3" sm="2" class="text-center">
                                <span class="text-body-1">{{ item.volume + ' ml'}} </span>
                                <br>
                                <span class="font-weight-light text-body-2 text-grey-lighten-1">Volume</span>
                            </v-col>
                            <v-col cols="2" sm="2" class="text-center d-none d-sm-block">
                                <span class="text-body-1">{{ item.caffeine + ' mg'}}</span>
                                <br>
                                <span class="font-weight-light text-body-2 text-grey-lighten-1">Caffeine</span>
                            </v-col>
                            <v-col cols="2" sm="2" class="text-center d-none d-sm-block">
                                <span class="text-body-1">{{ item.sugar + ' g'}}</span>
                                <br>
                                <span class="font-weight-light text-body-2 text-grey-lighten-1">Sugar</span>
                            </v-col>
                            <v-col class="text-center" cols="5" sm="3">
                                <span class="text-body-1">{{ formatDate(item.created_at) }}</span>
                            </v-col>
                            <v-col cols="1" class="text-right">
                                <span class="text-body-1">
                                    <v-btn 
                                        density="compact" 
                                        icon="mdi-trash-can-outline"
                                        @click="deleteDrinkLogElement(item.id)"
                                        :disabled="loading"
                                    >
                                    </v-btn>  
                                </span>
                            </v-col>
                        </v-row>
                        <v-divider class="mx-2 mt-2"></v-divider>
                    </div>
                </template>
                <template v-slot:empty>
                    <v-alert type="warning">No more items!</v-alert>
                </template>
            </v-infinite-scroll>
        </v-card-item>
    </v-card>
</template>