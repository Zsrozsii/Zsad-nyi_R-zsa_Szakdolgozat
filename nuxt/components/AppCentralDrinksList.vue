<script>
import {defineComponent} from 'vue'
import {mutation, query} from "gql-query-builder";

export default defineComponent({
  name: "AppCentralDrinksList",

  props: {
    value: {
      type: Boolean,
      default: false,
    },
  },


  data() {
    return {
      dialog: this.value,
      customDrinks: [],
      drinks: [],
      selected: [],
      isLoading:false,
      headers: [
      {
          title: '',
          align: 'start',
          sortable: false,
          key: 'is_favourite',
        },
        {
          title: 'Type',
          align: 'start',
          sortable: false,
          key: 'name',
        },
        {
          title: 'Default volume',
          align: 'start',
          sortable: false,
          key: 'default_ml',
        },
        { title: '', key: 'data-table-expand' },
      ]
    }
  },

  computed: {
    drinksQuery() {
      return query({
        operation: 'drinks',
        variables: {},
        fields: [
            'id',
            'name',
            'default_ml',
            'caffeine',
            'sugar',
            'effectiveness',
            'is_favourite'
        ]
      })
    },

    setFavouriteDrinkMutation() {
      return mutation({
        operation: 'setFavouriteDrink',
        variables: {
          id: {
            name: 'id',
            type: 'ID!'
          },
          favourite: {
            name: 'favourite',
            type: 'Boolean!'
          }
        },
        fields: [
          'id',
          'is_favourite'
        ]
      })
    },
  },

  methods: {
    closeSelection() {
      this.selected = []
    },

    async setFavourite(id, value) {
      const variables = {
        id: id,
        favourite: value
      }

      try {
        const { data } = await this.$apollo.mutate({
          mutation: gql`
          ${this.setFavouriteDrinkMutation.query}
        `,
          variables: variables,
        })
        await this.$apollo.queries.drinks.refetch()
        return data
      }
      catch (error) {
        console.log(error)
        this.error = error
        this.$snackbar.add({
            type: 'error',
            text: 'Failed to process request'
        })
      }
    },

  },

  watch: {
    customDrinks(value) {
      this.drinks = [...value]
    }
  },

  apollo: {
    drinks: {
      query() {
        return gql`
        ${this.drinksQuery.query}
        `
      },
      error(error) {
        this.error = error
        console.log(error)
      },
    }
  }
})
</script>

<template>
  <v-card flat>
    <v-card-text class="v-card__text">
        <v-data-table-virtual
          v-model:expanded="selected"
          :items="drinks"
          :headers="headers"
          item-key="id"
          item-value="id"
          show-expand
        >
          <template v-slot:item.is_favourite="{ item }">
            <v-btn 
              density="compact"
              :icon="item.is_favourite ? 'mdi-star' : 'mdi-star-outline'"
              @click="setFavourite(item.id, !item.is_favourite)"
              >
            </v-btn> 
          </template>
          <template v-slot:expanded-row="{ columns, item }">
            <tr>
              <td :colspan="columns.length">
                <v-row class="my-2">
                  <v-col class="mx-2" cols="auto" sm="3" md="2">
                    <p class="text-grey-lighten-1">name:</p>
                    <p class="text-body-1">{{ item.name }}</p>
                  </v-col>
                  <v-col class="mx-2" cols="auto" sm="3" md="2">
                    <p class="text-grey-lighten-1">default volume:</p>
                    <p class="text-body-1">{{ item.default_ml }}ml</p>
                  </v-col>
                  <v-col class="mx-2" cols="auto" sm="3" md="2">
                    <p class="text-grey-lighten-1">caffeine:</p>
                    <p class="text-body-1">{{ item.caffeine }}mg</p>
                  </v-col>
                  <v-col class="mx-2" cols="auto" sm="3" md="2">
                    <p class="text-grey-lighten-1">sugar:</p>
                    <p class="text-body-1">{{ item.sugar }}g</p>
                  </v-col>
                  <v-col class="mx-2" cols="auto" sm="3" md="2">
                    <p class="text-grey-lighten-1">effectiveness:</p>
                    <p class="text-body-1">{{ item.effectiveness }}%</p>
                  </v-col>
                </v-row>
              </td>
            </tr>
          </template>
        </v-data-table-virtual>
    </v-card-text>
  </v-card>

</template>

<style scoped>
.v-card__text {
    min-height: 70svh;
    overflow-y: auto;
    box-sizing: border-box;
}
</style>