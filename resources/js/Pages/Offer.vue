<script setup>
import Layout from "@/Layouts/Layout.vue";
import RangeInput from "@/Components/RangeInput.vue";
import {ref} from "vue";

const amountOfCredit = ref(250000);
const period = ref(25);

const calc = val => {
  amountOfCredit.value = Number(val);
}

</script>

<template>
  <Layout>
    <template #header> Oferta</template>
    <template #default>

      <RangeInput
        @updateInput="calc"
        input-type-label="PLN"
        :default-value="amountOfCredit"
        heading="Kwota kredytu"
        :max="2000000"
        :min="50000"
        :step="10000"
        label-left="50 000 zł"
        label-right="2 000 000 zł"
      />

      <RangeInput
        input-type-label="LAT"
        :default-value="period"
        heading="Okres spłaty"
        :max="35"
        :min="5"
        :step="1"
        label-left="5 lat"
        label-right="35 lat"
      />

      <button @click="calc">Oblicz</button>
    </template>
  </Layout>
</template>
