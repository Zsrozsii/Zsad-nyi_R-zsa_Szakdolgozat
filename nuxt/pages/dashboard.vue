<script setup>
definePageMeta({ middleware: 'auth' })
</script>

<script>
import { query } from "gql-query-builder"
import AppAddDrinkDialog from "~/components/AppAddDrinkDialog.vue"
import AppFavouriteDrinks from "~/components/AppFavouriteDrinks.vue";
import AppHydrationInfoCard from "~/components/AppHydrationInfoCard.vue";
import AppInfoCard from "~/components/AppInfoCard.vue";
import AppFaceCard from "~/components/AppFaceCard.vue";
import AppCurrentLevelCard from "~/components/AppCurrentLevelCard.vue";

import confetti from "canvas-confetti";



export default {
  name: 'Dashboard',

  components: { AppAddDrinkDialog, AppFavouriteDrinks, AppHydrationInfoCard, AppInfoCard, AppFaceCard, AppCurrentLevelCard },

  data() {
    return {
      currentLevels: null,
      error: [],
      drinks: [],
      customDrinks: [],
    }
  },

  mounted() {
    if (!!this.currentLevels){
      this.refreshLevels()
    }
    
  },

  computed: {
    waterDrinks() {
      return [
      {
          id: 1,
          name: '100ml',
          default_ml: 100,
          icon: 'mdi-water-plus-outline'
        },
        {
          id: 1,
          name: '200ml',
          default_ml: 200,
          icon: 'mdi-water-plus-outline'
        },
        {
          id: 1,
          name: '400ml',
          default_ml: 400,
          icon: 'mdi-water-plus-outline'
        },
        {
          id: 1,
          name: '500ml',
          default_ml: 500,
          icon: 'mdi-water-plus-outline'
        }
      ]
    },


    currentLevelsQuery() {
      return query({
        operation: 'currentLevels',
        fields: ['current', 'goal', 'caffeine', 'caffeine_max', 'sugar', 'sugar_max', 'score']
      })
    },

    drinksQuery() {
      return query({
        operation: 'drinks',
        fields: ['id', 'name', 'default_ml', 'sugar', 'caffeine', 'effectiveness', 'is_favourite', 'icon']
      })
    },

    customDrinksQuery() {
      return query({
        operation: 'customDrinks',
        fields: ['id', 'name', 'default_ml', 'sugar', 'caffeine', 'effectiveness', 'is_favourite', 'icon']
      })
    },

    favCustomDrinks() {
      var favs = this.customDrinks.filter(function (el) {
        return el.is_favourite === true
      })
      return favs
    },

    favCentralDrinks() {
      var favs = this.drinks.filter(function (el) {
        return el.is_favourite === true
      })
      return favs
    },

    favDrinks() {
      return this.favCentralDrinks.concat(this.favCustomDrinks)
    },

    currentCaffeineText() {
      const current = (this.currentLevels.caffeine)
      const goal = (this.currentLevels.caffeine_max)
      return current + " / " + goal + "mg"
    },
    currentSugarText() {
      const current = this.currentLevels.sugar?.toFixed(1)
      const goal = this.currentLevels.sugar_max?.toFixed(1)
      return current + " / " + goal + "g"
    },
  },

  methods: {
    async refreshLevels() {
      this.addDrinkDialog = false
      await this.$apollo.queries.currentLevels.refetch()
    },

    confettiEffect() {
      confetti({
        particleCount: 150,
        spread: 60
      })
    },

    async drinkAdded() {
      const beforeHydr = this.currentLevels.current
      await this.refreshLevels()
      if (beforeHydr < 2000 && this.currentLevels.current >= 2000) {
        this.confettiEffect()
      }
    },

  },

  apollo: {
    currentLevels: {
      query() {
        return gql`
            ${this.currentLevelsQuery.query}
          `
      },
      error(error) {
        this.error = error
        console.log(error)
        this.$snackbar.add({
          type: 'error',
          text: 'Failed to fetch'
        })
      },
    },
    drinks: {
      query() {
        return gql`
          ${this.drinksQuery.query}
        `
      },
      error(error) {
        this.error = error
        console.log(error)
      }
    },

    customDrinks: {
      query() {
        return gql`
          ${this.customDrinksQuery.query}
        `
      },
      error(error) {
        this.error = error
        console.log(error)
        this.$snackbar.add({
          type: 'error',
          text: 'Failed to fetch'
        })
      }
    },
  }
}
</script>
<template>
  <v-sheet class="bg">
  <v-container v-if="currentLevels">
    <v-row class="mt-6">
      
      <v-col cols="12" sm="6">
        <AppCurrentLevelCard  :currentLevels="currentLevels" :drinks="drinks" :customDrinks="customDrinks"
          @update:drink="drinkAdded()"></AppCurrentLevelCard>
      </v-col>

      <v-col cols="12" sm="6">
        <v-row class="">
          <v-col v-if="waterDrinks.length > 0" cols="12">
            <AppFavouriteDrinks :drinks="waterDrinks" title="Water" @added:drink="drinkAdded()">
            </AppFavouriteDrinks>
          </v-col>
          <v-col v-if="favDrinks.length > 0" cols="12">
            <AppFavouriteDrinks title="Drinks" :drinks="favDrinks" @added:drink="drinkAdded()">
            </AppFavouriteDrinks>
          </v-col>
          <!-- <v-col v-if="favCustomDrinks.length > 0" cols="12" >
            <AppFavouriteDrinks title="Drinks" :drinks="favCustomDrinks" @added:drink="drinkAdded()">
            </AppFavouriteDrinks>
          </v-col> -->
          </v-row>
      </v-col>
    </v-row>

    <v-row>
      <v-col cols="12" lg="6">
        <AppHydrationInfoCard :currentLevels="currentLevels"></AppHydrationInfoCard>
      </v-col>

      <v-col cols="12" sm="4" lg="2">
        <AppFaceCard :score="currentLevels.score" class="h-100">
        </AppFaceCard>
      </v-col>

      <v-col cols="12" sm="4" lg="2">
        <AppInfoCard class="h-100" title="Sugar" :text="currentSugarText" icon="mdi-spoon-sugar">
        </AppInfoCard>
      </v-col>
      <v-col cols="12" sm="4" lg="2">
        <AppInfoCard class="h-100" title="Caffeine" :text="currentCaffeineText" icon="mdi-lightning-bolt-outline"
          color="green">
        </AppInfoCard>
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