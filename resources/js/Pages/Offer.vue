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
    <template v-slot:header>Oferta Banków</template>
    <template v-slot:default>
      <section class="w-full rounded-lg shadow-2xl border border-gray-200 bg-white p-5 flex">
        <div class="flex-col flex">
          <img src="http://localhost/storage/logos/gHE0VDwZbkLJaQWNl4YqrD8MwNjhj5OXGxyhDqbF.jpg" alt="" class="w-48">
          <span class="">6 ofert</span>
        </div>
        <div>
          <img src="" alt="">
          <span>6 ofert</span>
        </div>
        <div>
          <img src="" alt="">
          <span>6 ofert</span>
        </div>
      </section>
    </template>
  </Layout>
</template>
