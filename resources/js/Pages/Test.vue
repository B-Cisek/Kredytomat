<script setup>
import Layout from "@/Layouts/Layout.vue";
import InputCounter from "@/Components/InputCounter.vue";

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
            <div class="p-5">
                <InputCounter />
            </div>
        </template>
    </Layout>
</template>



