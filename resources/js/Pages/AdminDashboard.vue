<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import Table from "@/Components/Table.vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import {defineProps} from "vue";

defineProps({
    loggedIn: Boolean
})
</script>

<template>
    <Head title="Dashboard" />
    <AdminLayout :logged-in="loggedIn">
        <template #header>
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                Dashboard
            </h1>
        </template>
        <template #default>
            <Table />
        </template>
    </AdminLayout>
</template>
