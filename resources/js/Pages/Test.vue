<script setup>
import Layout from "@/Layouts/Layout.vue";

defineProps({
    loggedIn: Boolean
})
</script>

<template>
    <Layout :loggedIn="loggedIn">
        <template #header>
            TEST DASHBOARD
        </template>
        <template #default>
            Test Page
        </template>
    </Layout>
</template>



