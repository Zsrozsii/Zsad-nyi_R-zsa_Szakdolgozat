<script>
import {mutation} from "gql-query-builder";
import {minLength, required, validEmail, gte, lte} from "~/utils/validations.js";

export default {
  data() {
    return {
      valid: false,
      name: '',
      email: '',
      password: '',
      repeat_password: '',
      genders: [
        "Male",
        "Female",
      ],
      height: null,
      weight: null,
      gender: null,

      formBusy: false,
      error: null,
    }
  },

  methods: {
    async handleRegister() {
      if(!this.valid) {
        return
      }

      try {
        const result = await this.$apollo.mutate({
          mutation: gql`
            ${this.registerMutation.query}
          `,
          variables: this.registerMutation.variables,
        })

        if(result['data']['register']){
          await navigateTo('/login')
        }
      }
      catch(error) {
        console.log(error.message)
        this.error = error
      }

      
    }
  },

  computed: {
    rules() {
      return {
        name: [required, value => minLength(value, 3)],
        email: [required, validEmail],
        password: [required, value => minLength(value, 8)],
        repeat_password: [required, value => (value === this.password) || "Passwords must match"],
        height: [required, value => gte(value, 100), value => lte(value, 250)],
        weight: [required, value => gte(value, 30), value => lte(value, 200)],
        gender: [required],
      }
    },


    registerData() {
      let genderData = false

      if (this.gender === "Male") {
        genderData = true
      }

      return {
       name: this.name,
       email: this.email,
       password: this.password,
       gender: genderData,
       height: parseInt(this.height),
       weight: parseInt(this.weight)
     }
    },

    registerMutation() {
      return mutation({
        operation: 'register',
        fields: [],
        variables: {
          data: {
            name: 'data',
            type: 'RegisterInput!',
            value: this.registerData
          }
        }
      })
    }
  }
}
</script>
<template>
  <div class="bg">
    <v-form v-model="valid" @submit.prevent="handleRegister">
      <v-container>
        <v-card class="pb-4 px-4 pt-6 mt-10 justify-center mx-auto rounded-xl" max-width="800px">
          <v-card-title class="mb-6">
            <v-icon large left> mdi-account-circle </v-icon>
            <span class=" pl-3 text-h5 font-weight-light">
              Sign up
            </span>
          </v-card-title>
          <v-card-text>
            <v-row>
              <v-col
                  cols="12"
                  md="6"
              >
                <v-text-field
                    v-model="name"
                    :rules="rules.name"
                    label="Name*"
                    required
                ></v-text-field>
              </v-col>
              <v-col
                  cols="12"
                  md="6"
              >
                <v-text-field
                    v-model="email"
                    :rules="rules.email"
                    label="E-mail*"
                    required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col
                  cols="12"
                  md="6"
              >
                <v-text-field
                    v-model="password"
                    :rules="rules.password"
                    label="Password*"
                    type="password"
                    required
                ></v-text-field>
              </v-col>
              <v-col
                  cols="12"
                  md="6"
              >
                <v-text-field
                    v-model="repeat_password"
                    :rules="rules.repeat_password"
                    label="Repeat password*"
                    type="password"
                    required
                ></v-text-field>
              </v-col>
            </v-row>
            <v-row>
              <v-col cols="12">
                <v-select
                    v-model="gender"
                    :rules="rules.gender"
                    :items="genders"
                    label="Gender*"
                ></v-select>
              </v-col>
              <v-col cols="12">
                <v-text-field
                    v-model="height"
                    :rules="rules.height"
                    label="Height*"
                    required
                    type="number"
                ></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field
                    v-model="weight"
                    :rules="rules.weight"
                    label="Weight*"
                    required
                    type="number"
                ></v-text-field>
              </v-col>
            </v-row>
            <small>*indicates required field</small>
          </v-card-text>
          <div class="alert-wrapper pt-4">
            <v-alert
                v-if="error"
                color="red lighten-2"
                dark
            >
              {{ error.message }}
            </v-alert>
          </div>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              class="mt-4"
              color="blue"
              variant="elevated"
              :disabled="formBusy"
              type="submit"
              large
              depressed>
              Register
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-container>
    </v-form>
  </div>
</template>

<style scoped>
.bg {
  min-height: 100%;
  background: url('../assets/img/dashboard/bg.jpg');
  background-size: cover;
}
</style>