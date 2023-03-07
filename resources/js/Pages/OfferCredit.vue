<script setup>
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
  credit: Object
});

console.log(props.credit);
</script>

<template>
  <Layout>
    <template v-slot:header>
      Kredyt
    </template>
  </Layout>
</template>
