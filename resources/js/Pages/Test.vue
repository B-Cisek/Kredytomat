<script setup>
import Layout from "@/Layouts/Layout.vue";
import {reactive} from "vue";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import {useHelpers} from "@/Composables/useHelpers";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";

const newInterestRate = [
  {value: 5, date: {year: 2023, month: 2}},
  {value: 6, date: {year: 2024, month: 11}}
];

const credit = reactive({
  date: new Date(2023,0),
  amountOfCredit: 300000,
  period: 25,
  margin: 1,
  commission: 0,
  wibor: 7,
  typeOfInstallment: "decreasing"
});


let result = [];

result = useDecreasingInstallmentsV2(
  credit,
  [],
  newInterestRate
).getSchedule();


console.table(result)




</script>

<template>
<Layout>
  <template v-slot:header>
    dsadas
  </template>
  <template v-slot:default>
    dsad
  </template>
</Layout>
</template>
