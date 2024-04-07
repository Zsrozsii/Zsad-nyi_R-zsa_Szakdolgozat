<script>
import {mutation, query} from "gql-query-builder";
import {minLength, required, validEmail, gte, lte} from "~/utils/validations.js";

export default {
  name: 'AppSettingsElement',


  data() {
    return  {
      me: {},
      password: '',
      repeatPassword: '',
      userData: {},
      form: null,
      error: "",
      isLoading: false
    }
  },

  computed: {
    rules() {
      return {
        name: [required, value => minLength(value, 3)],
        email: [required, validEmail],
        password: this.password ? [value => minLength(value, 8)] : [],
        repeat_password: this.password ? [value => (value === this.password) || "Passwords must match"] : [],
        height: [required, value => gte(value, 100), value => lte(value, 250)],
        weight: [required, value => gte(value, 30), value => lte(value, 200)],
        hydration_goal: [required, value => gte(value, 0)],
        sugar_max: [required, value => gte(value, 0)],
        caffeine_max: [required, value => gte(value, 0)],
      }

    },

    meQuery() {
      return query({
        operation: 'me',
        variables: {},
        fields: [
            'name',
            'email',
            'height',
            'weight',
            'hydration_goal',
            'caffeine_max',
            'sugar_max'
        ]
      })
    },

    changeSettingsMutation() {
      return mutation({
        operation: 'changeSettings',
        variables: {
          userData: {
            name: 'data',
            type: 'UserDataInput!',
            value: this.userDataInput
          }
        },
        fields: [
          'name',
          'email',
          'height',
          'weight',
          'hydration_goal',
          'caffeine_max',
          'sugar_max'
        ]
      })
    },

    userDataInput() {
      const data =  {
        'name': this.userData.name,
        'email': this.userData.email,
        'height': parseInt(this.userData.height),
        'weight': parseInt(this.userData.weight),
        'hydration_goal': parseInt(this.userData.hydration_goal),
        'caffeine_max': parseInt(this.userData.caffeine_max),
        'sugar_max': parseInt(this.userData.sugar_max)
      }

      if (this.password && this.password === this.repeatPassword) {
        data.password = this.password
      }
      return data
    }

  },

  methods: {

    async saveSettings() {

      if(this.form === false) {
        return
      }

      this.isLoading = true
      try {
        const { data } = await this.$apollo.mutate({
          mutation: gql`
          ${this.changeSettingsMutation.query}
        `,
          variables: this.changeSettingsMutation.variables,
        })
        this.$snackbar.add({
            type: 'success',
            text: 'Settings saved'
        })
        await this.$apollo.queries.me.refetch()
      }
      catch (error) {
        console.log(error)
        this.error = error
        this.$snackbar.add({
            type: 'error',
            text: 'Failed to update settings'
        })
      }
      this.isLoading = false
    },
  },

  watch: {

    me(value) {
       this.userData = {...value}
    }
  },

  apollo: {
    me: {
      query() {
        return gql`
          ${this.meQuery.query}
        `
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
  <v-card flat>
  <v-card-text class="v-card__text">
    <div v-if="me">
      <v-form ref="form" @submit.prevent v-model="form">
      <h4 class="text-h6 mb-2">Basic info</h4>
      <v-row>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="First name*"
              required
              v-model="userData.name"
              :rules="rules.name"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Email*"
              required
              v-model="userData.email"
              :rules="rules.email"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
      </v-row>
      <v-row class="mb-6">
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Height"
              type="number"
              required
              v-model="userData.height"
              :rules="rules.height"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Weight"
              type="number"
              required
              v-model="userData.weight"
              :rules="rules.weight"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
      </v-row>
      <h4 class="text-h6 mb-2">Change password</h4>
      <v-row class="mb-6">
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Password*"
              type="password"
              required
              v-model="password"
              :rules="rules.password"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Repeat password*"
              type="password"
              required
              v-model="repeatPassword"
              :rules="rules.repeat_password"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
      </v-row>
      <h4 class="text-h6 mb-2">Drink goals</h4>
      <v-row>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Hydration goal [ml]"
              type="number"
              required
              v-model="userData.hydration_goal"
              :rules="rules.hydration_goal"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Maximum caffeine [mg]"
              type="number"
              required
              v-model="userData.caffeine_max"
              :rules="rules.caffeine_max"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
        <v-col
            cols="12"
            sm="6"
            md="4"
        >
          <v-text-field
              label="Maximum sugar [g]"
              type="number"
              required
              v-model="userData.sugar_max"
              :rules="rules.sugar_max"
              :disabled="isLoading"
          ></v-text-field>
        </v-col>
      </v-row>
      </v-form>
    </div>
    <small>*indicates required field</small>
    <div class="alert-wrapper pt-4">
      <v-alert
          v-if="error"
          color="red lighten-2"
          dark
      >
        "Invalid data"
      </v-alert>
    </div>
  </v-card-text>
  <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn
        color="blue-darken-1"
        variant="text"
        type="submit"
        @click="saveSettings()"
    >
      Save
    </v-btn>
  </v-card-actions>
</v-card>

</template>

<style scoped>


</style>