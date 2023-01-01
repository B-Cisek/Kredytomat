<script setup>
import InstallmentCalculator from "@/Components/InstallmentCalculator.vue";
import Layout from "@/Layouts/Layout.vue";
</script>

<template>
  <Layout>
    <template #header>Kalkulator raty kredytu</template>
    <template #default>
      <InstallmentCalculator/>
    </template>
  </Layout>
</template>
