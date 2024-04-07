<script>
import {mutation} from "gql-query-builder";
import { reloadNuxtApp } from "nuxt/app";

export default {
  data() {
    return {
      form: {
        email: '',
        password: '',
      },

      formBusy: false,
      error: "",
    }
  },

  computed: {
    loginMutation() {
      return mutation({
        operation: 'login',
        fields: [],
        variables: {
          email: {
            name: 'email',
            type: 'String!',
            value: this.form.email,
          },
          password: {
            name: 'password',
            type: 'String!',
            value: this.form.password,
          },
        }
      })
    },
  },

  methods: {
    async handleLoginSubmit() {
      const { onLogin } = useApollo()
      let token = await this.login()

      if(token) {
        const res = await onLogin(token)
        // await navigateTo('/')
        reloadNuxtApp({
          path: "/dashboard",
          ttl: 1000, // default 10000
        });
      }

    },

    async login() {
      try {
        const { data: { login }  }= await this.$apollo.mutate({
          mutation: gql`
          ${this.loginMutation.query}
        `,
          variables: this.loginMutation.variables,
        })
        return login
      }
      catch (error) {
        console.log(error.message)
        this.error = error
      }
    }
  }
}

</script>
<template>
  <div class="bg">
    <v-container>
      <v-card class="pb-4 px-4 pt-12 mt-10 justify-center mx-auto rounded-xl" max-width="400px">
      <v-card-title>
        <v-icon large left> mdi-account-circle </v-icon>
        <span class=" pl-3 text-h5 font-weight-light">
                    {{ 'login' }}
                </span>
      </v-card-title>
      <v-card-text>
        <v-form ref="loginForm" @submit.prevent="handleLoginSubmit">
          <v-text-field
              v-model="form.email"
              label="email"
              name="email"
              prepend-icon="mdi-email"
              type="email"
              outlined
          >
          </v-text-field>

          <v-text-field
              v-model="form.password"
              label="password"
              name="password"
              prepend-icon="mdi-lock"
              type="password"
              outlined
          >
          </v-text-field>

          <div class="alert-wrapper pt-4">
            <v-alert
                v-if="error"
                color="red lighten-2"
                dark
            >
              {{ error.message }}
            </v-alert>
          </div>
          
          <v-btn
              class="mt-4"
              color="primary"
              :disabled="formBusy"
              type="submit"
              large
              depressed
          >
            {{ 'login' }}
          </v-btn>
        </v-form>
      </v-card-text>
    </v-card>
    </v-container>
  
  </div>

</template>

<style scoped>
.bg {
  min-height: 100%;
  background: url('../assets/img/dashboard/bg.jpg');
  background-size: cover;
}
</style>