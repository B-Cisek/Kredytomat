<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import RangeWithInput from "@/Components/RangeWithInput.vue";

const margin = ref("2");

</script>

<template>
  <Layout>
    <template #header> Oferta</template>
    <template #default>
      <RangeWithInput
        v-model="margin"
        heading="Marża"
        input-type-label="%"
        :min="1"
        :max="10"
        :step="0.01"
        labelLeft="1 %"
        label-right="10 %"
      />
    </template>
  </Layout>
</template>
