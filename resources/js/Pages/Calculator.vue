<script setup>
import InstallmentCalculator from "@/Components/InstallmentCalculator.vue";
import Layout from "@/Layouts/Layout.vue";
</script>

<template>
  <Layout>
    <template v-slot:header>Kalkulator raty kredytu</template>

    <template v-slot:default>
      <InstallmentCalculator/>
    </template>
  </Layout>
</template>
