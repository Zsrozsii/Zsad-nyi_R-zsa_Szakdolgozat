<script>

import AppSettingsElement from "~/components/AppSettingsElement.vue";
import AppCustomDrinksList from "~/components/AppCustomDrinksList.vue";
import AppCentralDrinksList from "~/components/AppCentralDrinksList.vue";

export default {
    name: 'settings',

    components: { AppSettingsElement, AppCustomDrinksList, AppCentralDrinksList },

    data() {
        return {
            tab: 'option-1',
        }
    },

}
</script>

<template>
<div class="bg">
    <v-container>
        <v-card class="rounded-xl">
            <v-tabs v-model="tab" color="primary">
                <v-tab value="option-1">
                    <v-icon start>
                        mdi-account
                    </v-icon>
                    User settings
                </v-tab>
                <v-tab value="option-2">
                    <v-icon start>
                        mdi-cup
                    </v-icon>
                    Favourite drinks
                </v-tab>
                <v-tab value="option-3">
                    <v-icon start>
                        mdi-cup
                    </v-icon>
                    Custom drinks
                </v-tab>
            </v-tabs>
            <v-window v-model="tab" class="window">
                <v-window-item value="option-1">
                    <app-settings-element></app-settings-element>
                </v-window-item>
                <v-window-item value="option-2">
                    <app-central-drinks-list></app-central-drinks-list>
                </v-window-item>
                <v-window-item value="option-3">
                    <app-custom-drinks-list></app-custom-drinks-list>
                </v-window-item>
            </v-window>
        </v-card>
    </v-container>
</div>
</template>

<style scoped>
.window {
    width: 100%;
}


.bg {
  min-height: 100%;
  background: url('../assets/img/dashboard/bg.jpg');
  background-size: cover;
}

</style>
