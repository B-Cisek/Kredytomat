<script setup>
import Layout from "@/Layouts/Layout.vue";
import RangeWithInputSelect from "@/Components/RangeWithInputSelect.vue";
import {ref} from "vue";

const commission = ref(0);
const commissionType = ref("percent");

const setCommissionType = value => {
  commission.value = 0;
  commissionType.value = value;
}

const setCommissionValue = value => {
  commission.value = value;
}
</script>

<template>
<Layout>
  <template v-slot:header>
    dsadas
  </template>
  <template v-slot:default>
    <RangeWithInputSelect
      heading="Prowizja"
      @selected-type="setCommissionType"
      @commission-value="setCommissionValue"
    />
  </template>
</Layout>
</template>
