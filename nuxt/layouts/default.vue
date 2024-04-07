<script>
  import {query} from "gql-query-builder";
  import AppSettingsElement from "~/components/AppSettingsElement.vue";

  import logo from "../assets/img/logo/logo.png"
  

  export default {
    components: {AppSettingsElement},
    data() {
      return {
        logo: logo,
        links: {
          'Dashboard': '/dashboard',
          'Log': '/log',
        },      }
    },

    computed: {
      isAuthenticated() {
        const token = useCookie("apollo:web.token").value

        return !!token;
      },

      meQuery() {
        return query({
          operation: 'me',
          fields: ['id', 'name']
        })
      },

      logoutQuery() {

      },
    },

    methods: {
      

      async openSettings() {
        await navigateTo('/settings')
      },

      async logout() {
        const { onLogout } = useApollo()

        await onLogout()

        await navigateTo('/')
      },

      openDrinks() {
        this.custom_drinks=true
      }
    },

    apollo: {
      me: {
        prefetch: false,
        query() {
          return gql`
            ${this.meQuery.query}
          `
        },
        error(error) {
          this.error = error
          console.log(error)
        }
      }
    },
  }
</script>

<template>
  <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <v-app>
    <v-app-bar flat>
      <v-container class="mx-auto d-flex align-center">
        <div class="d-none d-sm-block">
          <nuxt-link 
            to="/">
            <v-img height="50" width="50" :src="logo"></v-img>
          </nuxt-link>
        </div>
        <v-spacer></v-spacer>
        <div v-if="isAuthenticated">
          <v-btn
            v-for="(link, key) in links"
            :key="key"
            :text="key"
            variant="text"
            :to="link"
          >
          </v-btn>
        </div>
        <v-spacer></v-spacer>
        <v-btn v-if="!isAuthenticated"
            color="blue"
            variant="outlined"
            to="/login">
          Login
        </v-btn>
        <v-btn
          v-if="!isAuthenticated"
          color="blue"
          variant="tonal"
          to="/register"
          class="ml-2"
        >
          Sign up
        </v-btn>
        <v-menu v-if="isAuthenticated">
          <template v-slot:activator="{ props }">
            <v-btn
              icon="mdi-account"
              v-bind="props"
            >
            </v-btn>
          </template>
          <v-list>
            <v-list-item
            >
              <v-list-item-title>{{ me.name }}</v-list-item-title>
            </v-list-item>
            <v-list-item>
              <v-btn
                  color="primary"
                  variant="text"
                  @click="openSettings()"
              >
                Settings
              </v-btn>
            </v-list-item>
            <v-list-item>
              <v-btn
                  color="primary"
                  variant="text"
                  @click="logout()"
              >
                Logout
              </v-btn>
            </v-list-item>
          </v-list>
        </v-menu>
      </v-container>
    </v-app-bar>
    <v-main>
      <slot/>
      <NuxtSnackbar />
    </v-main>
  </v-app>
</template>

<style scoped>

</style>