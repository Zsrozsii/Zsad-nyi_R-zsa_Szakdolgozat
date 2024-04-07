<script>
import {defineComponent} from 'vue'
import {mutation, query} from "gql-query-builder";

export default defineComponent({
  name: "AppCustomDrinksList",

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
      deleteDialog: false,
      isLoading:false,
      newDrink: null,
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
    customDrinksQuery() {
      return query({
        operation: 'customDrinks',
        variables: {},
        fields: [
            'id',
            'name',
            'default_ml',
            'caffeine',
            'sugar',
            'effectiveness',
            'is_favourite' ,
            'icon',
        ]
      })
    },

    deleteDrinkMutation() {
      return mutation({
        operation: "deleteCustomDrink",
        variables: {
          id: {
            name: "id",
            type: "ID!",
            value: this.selected[0],
          }
        },
        fields: [
            'id'
        ]
      })
    },

    updateCustomDrinkMutation() {
      return mutation({
        operation: "updateCustomDrink",
        variables: {
          id: {
            name: "id",
            type: "ID!",
          },
          data: {
            name: 'data',
            type: 'UpdateCustomDrinkInput!',
          }
        },
        fields: [
            'id'
        ]
      })
    }
  },

  methods: {
    closeSelection() {
      this.selected = []
    },

    addNewDrink() {
      this.drinks.push({
        id: "New",
        name: "New Drink"
      })
      this.newDrink = this.drinks.length-1
      this.selected = ["New"]
    },

    cancelCreate() {
      if(!!this.newDrink) {
        delete this.drinks.splice(this.newDrink, 1)
      }
      this.newDrink = null
    },

    async setFavourite(id, value) {
      const variables = {
        id: id,
        data: {
          is_favourite: value
        }
      }

      try {
        const { data } = await this.$apollo.mutate({
          mutation: gql`
          ${this.updateCustomDrinkMutation.query}
        `,
          variables: variables,
        })
        await this.$apollo.queries.customDrinks.refetch()
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

    async updatedDrink() {
      this.isLoading=true
      await this.$apollo.queries.customDrinks.refetch()
      this.closeSelection()
      this.isLoading=false
    },

    async createdDrink() {
      this.isLoading=true
      await this.$apollo.queries.customDrinks.refetch()
      this.closeSelection()
      this.isLoading=false
      this.newDrink = null
    },

    async deleteDrink() {

      if(!Array.isArray(this.selected) && !this.selected.length) {
        return
      }

      try {
        const { data } = await this.$apollo.mutate({
          mutation: gql`
          ${this.deleteDrinkMutation.query}
        `,
          variables: this.deleteDrinkMutation.variables,
        })
        await this.$apollo.queries.customDrinks.refetch()
        this.deleteDialog = false
        this.selected = []
        return data
      }
      catch (error) {
        console.log(error)
        this.error = error
        this.deleteDialog = false
        this.$snackbar.add({
            type: 'error',
            text: 'Failed to delete drink'
        })
      }
    }
  },

  watch: {
    customDrinks(value) {
      this.drinks = [...value]
    }
  },

  apollo: {
    customDrinks: {
      query() {
        return gql`
        ${this.customDrinksQuery.query}
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
                <app-custom-drink-form
                :custom_drink="item"
                @delete:drink="deleteDialog=true"
                @updated:drink="updatedDrink"
                @cancel:create="cancelCreate()"
                @cancel:update="closeSelection()"
                @created:drink="createdDrink()"
                >
                </app-custom-drink-form>
              </td>
            </tr>
          </template>
        </v-data-table-virtual>
    </v-card-text>
    <v-card-actions>
      <v-btn
          color="blue-darken-1"
          variant="text"
          @click="addNewDrink()"
          :disabled="isLoading || !!this.newDrink"

      >
        Add new
      </v-btn>
    </v-card-actions>
  </v-card>

  <v-dialog
      v-model="deleteDialog"
      width="auto"
  >
    <v-card>
      <v-card-title>
        Delete?
      </v-card-title>
      <v-card-text>
        Are you sure?
      </v-card-text>
      <v-card-actions>
        <v-btn
            color="red"
            class="ma-2"
            @click="deleteDrink()"
            :disabled="isLoading"
        >
          Delete
        </v-btn>
        <v-btn
            color="primary"
            variant="text"
            @click="deleteDialog = false"
            :disabled="isLoading"
        >
          Cancel
        </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>

</template>

<style scoped>
.v-card__text {
    min-height: 70svh;
    overflow-y: auto;
    box-sizing: border-box;
}
</style>