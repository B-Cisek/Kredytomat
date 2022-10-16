<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import Cta from "@/Components/Cta.vue";
import Layout from "@/Layouts/Layout.vue";
import CreditCard from "@/Components/CreditCard.vue";

</script>


<template>
    <Head title="Home" />
    <Layout>
        <template #header>
            <Cta/>
        </template>
        <template #default>
            <CreditCard />
            <CreditCard />
            <CreditCard />
            <CreditCard />
            <CreditCard />
        </template>
    </Layout>
</template>

