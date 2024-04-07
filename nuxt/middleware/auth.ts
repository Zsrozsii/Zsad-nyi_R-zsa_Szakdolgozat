
export default defineNuxtRouteMiddleware(async (to, from) => {


    const token = useCookie("apollo:web.token").value

    if (!token) {
        return navigateTo('/login')
    }
})