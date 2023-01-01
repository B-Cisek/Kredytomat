<script setup>
import Layout from "@/Layouts/Layout.vue";

import {useRatyMalejace} from "@/Composables/useRatyMalejace";
import {useHelpers} from "@/Composables/useHelpers";

const props = defineProps({
  credits: Object,
});


</script>

<template>
  <Layout>
    <template #header> Oferta </template>
    <template #default> dsad </template>
  </Layout>
</template>
