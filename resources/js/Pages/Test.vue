<script setup>
import Layout from "@/Layouts/Layout.vue";
import TestSelectInput from "@/Components/TestSelectInput.vue";
import {ref, watch} from "vue";

const amountOfCredit = ref(250000);
const commission = ref(2000);
const commissionType = ref("number");

setTimeout(() => {
  commissionType.value = "percent";
}, 3000)


</script>

<template>
<Layout>
  <template v-slot:header>
    Test
  </template>
  <template v-slot:default>
    <TestSelectInput
      v-model:commission="commission"
      v-model:commission-type="commissionType"
      heading="Prowizja"
    />
  </template>
</Layout>
</template>
