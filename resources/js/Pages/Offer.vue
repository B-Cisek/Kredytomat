<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import {useHelpers} from "@/Composables/useHelpers";

const {formatHarmonogram} = useHelpers();

let creditResult = useDecreasinginstallments({
  date: new Date(2023, 0),
  amountOfCredit: 250000,
  period: 25,
  margin: 2,
  wibor: 7.77,
  commission: 0
}, [], []).getScheduleShorterPeriod();

console.table(formatHarmonogram(creditResult));
</script>


<template>
  <Head title="Oferta" />
  <Layout>
    <template v-slot:header>Oferta</template>
    <template v-slot:default>

    </template>
  </Layout>
</template>
