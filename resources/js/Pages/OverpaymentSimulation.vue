<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useHelpers} from "@/Composables/useHelpers";
const {formattedToPLN} = useHelpers();

const props = defineProps({
  overpaymentSimulation: Object
});


</script>

<template>
  <Layout>
    <template v-slot:header>Symulacja kredytowa</template>
    <template v-slot:default>

    </template>
  </Layout>
</template>

