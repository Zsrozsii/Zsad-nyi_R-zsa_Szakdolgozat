import { createApolloProvider } from '@vue/apollo-option'
import { ApolloClient, createHttpLink, InMemoryCache } from '@apollo/client/core'
import { setContext } from '@apollo/client/link/context'

export default defineNuxtPlugin(async nuxtApp  => {
    const cache = new InMemoryCache();

    const token = useCookie("apollo:web.token").value

    const link = createHttpLink({
        uri: 'http://localhost/graphql',
        credentials: 'same-origin' // include, omit
    });

    const authLink = setContext((_, { headers }) => {

        return {
          headers: {
            ...headers,
            authorization: token ? `Bearer ${token}` : "",
          }
        }
    });

    const apolloClient = new ApolloClient({
        cache,
        link: authLink.concat(link),
        fetchOptions: {
            mode: 'no-cors',
        },
    })

    const apolloProvider = createApolloProvider({
        defaultClient: apolloClient,
    })

    nuxtApp.vueApp.use(apolloProvider)
})

