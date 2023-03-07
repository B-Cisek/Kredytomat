<script setup>
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
  creditSimulations: Object
});
console.log(props.creditSimulations)
</script>

<template>
  <Layout>
    <template v-slot:header>Kalkulacje kredytu</template>
    <template v-slot:default>
      <div
        v-for="credit in props.creditSimulations"
        :key="credit.id"
      >
        <h1>{{ credit.amount_of_credit }}</h1>
      </div>
    </template>
  </Layout>
</template>
