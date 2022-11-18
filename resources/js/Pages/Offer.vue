<script setup>
import Layout from "@/Layouts/Layout.vue";
import PieChart from "@/Components/PieChart.vue";
import CreditSchedule from "@/Components/CreditSchedule.vue";

const props = defineProps({
  credits: Object,
});
</script>

<template>
  <Layout>
    <template #header> Oferta </template>
    <template #default>
      <h1 v-for="credit in credits" key="credit.id" class="font-semibold">
        {{ credit.credit_name }}
      </h1>
      <CreditSchedule />
    </template>
  </Layout>
</template>
