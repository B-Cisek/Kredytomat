<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import RangeInputPeriod from "@/Components/Inputs/RangeInputPeriod.vue";

const childComponentState = ref(20);
const type = ref('year');

</script>

<template>
<Layout>
  <RangeInputPeriod
    v-model="childComponentState"
    v-model:type="type"
  />
</Layout>
</template>
