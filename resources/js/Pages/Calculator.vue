<script setup>
import CreditSchedule from "@/Components/CreditSchedule.vue";
import InstallmentCalculator from "@/Components/InstallmentCalculator.vue";
import Layout from "@/Layouts/Layout.vue";
</script>

<template>
  <Layout>
    <template #header> Kalkulator raty kredytu </template>
    <template #default>
      <InstallmentCalculator class="mb-10" />
    </template>
  </Layout>
</template>
