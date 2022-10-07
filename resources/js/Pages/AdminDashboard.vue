<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import {defineProps} from "vue";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Tile from "@/Components/Tile.vue";

defineProps({
    loggedIn: Boolean
})
</script>

<template>
    <Head title="Dashboard"/>

    <AdminDashboardLayout>
        <template #header>
            Dashboard
        </template>
        <template #default>
            <Tile :href="route('admin.users.index')">
                Użytkownicy
            </Tile>
            <Tile :href="route('admin.credits.index')">
                Kredyty
            </Tile>
            <Tile :href="route('admin.banks.index')">
                Banki
            </Tile>
        </template>
    </AdminDashboardLayout>
</template>
