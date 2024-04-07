<script>

export default {
    name: 'AppInfoCard',

    props: {
        title: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: true
        },
        icon: {
            type: String,
            required: true
        },
        color: {
            type: String,
            default: "blue"
        },

    }
}

</script>

<template>
<v-card class="justify-center text-center pa-4 rounded-xl">
    <v-card-subtitle>
        {{ title }}
    </v-card-subtitle>
    <v-card-text>
        <v-icon
            :color="color"
            :icon="icon"
            size="60"
        ></v-icon>
        <p class="mt-2 text-body-1">{{ text }}</p>
    </v-card-text>
</v-card>
</template>

<style scoped>
</style>