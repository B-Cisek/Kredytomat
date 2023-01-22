<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import Cta from "@/Components/Cta.vue";
import Layout from "@/Layouts/Layout.vue";
import CreditCard from "@/Components/CreditCard.vue";
import ToastList from "@/Components/ToastList.vue";

defineProps({
  credits: Object,
});
</script>

<template>
  <Head title="Home" />
  <Layout>
    <template #header>
      <Cta />
    </template>
    <template #default>
      <CreditCard :credits="credits" />
    </template>
  </Layout>
</template>
