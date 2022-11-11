<script setup>
import InputCalculator from "@/Components/InputCalculator.vue";
import Layout from "@/Layouts/Layout.vue";
</script>

<template>
  <Layout>
    <template #header> Faq </template>
    <template #default>
      <section class="w-4/12">
        <InputCalculator span-text="PLN" />
        <InputCalculator span-text="%" />
      </section>
    </template>
  </Layout>
</template>
