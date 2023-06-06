<script setup>
import Layout from "@/Layouts/Layout.vue";
import RangeWithInputV2 from "@/Components/RangeWithInputV2.vue";
import {ref, watch} from "vue";

const prowizja = ref(0);

</script>

<template>
<Layout>
  <template v-slot:header>
    Test - {{prowizja}}
  </template>
  <template v-slot:default>
    <RangeWithInputV2
      v-model="prowizja"
      input-type-label="PLN"
      heading="Test"
      :min="0.1"
      :max="10"
      :step="0.1"
      label-left="test"
      label-right="test"
    />
  </template>
</Layout>
</template>
