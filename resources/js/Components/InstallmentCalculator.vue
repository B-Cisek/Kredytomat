<script setup>
import { ref, defineEmits, watch, onUpdated, watchEffect } from "vue";
import { useRataCalkowitaStala } from "@/Composables/useRataCalkowitaStala.js";
import { useHarmonogram } from "@/Composables/useHarmonogram";
import { useHelpers } from "@/Composables/useHelpers";
import Chart from "@/Components/Chart.vue";

const { rataStalaFormatted, rataStala, toDecimal } = useRataCalkowitaStala();
const { harmonogram, kosztKredytu } = useHarmonogram();
const { formatHarmonogram } = useHelpers();

const amountOfCredit = ref(250000);
const period = ref(25);
const rate = ref(7);
const commission = ref(0);
const installment = ref(null);
const cost = ref(0);
const toBePaid = ref(0);
const results = ref(null);

const commissionResult = ref(0);

const formattedToPLN = new Intl.NumberFormat("pl-PL", {
  style: "currency",
  currency: "PLN",
  maximumFractionDigits: 2,
});

const show = () => {
  let harmon = harmonogram(amountOfCredit.value, period.value, rate.value);
  cost.value = kosztKredytu(harmon);
  installment.value = rataStalaFormatted(amountOfCredit.value, period.value, rate.value);
  commissionResult.value = amountOfCredit.value * toDecimal(commission.value);
  console.log(amountOfCredit.value, cost.value, commissionResult.value);
  toBePaid.value = Number(amountOfCredit.value) + cost.value + commissionResult.value;
  results.value.scrollIntoView({ behavior: "smooth" });
};

const data = {
  labels: [`Kwota kredytu`, `Odsetki`, `Prowizja banku`],
  datasets: [
    {
      data: [amountOfCredit, cost, commissionResult],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
};
</script>
<template>
  <section
    class="flex flex-col gap-5 w-full mx-auto rounded-lg shadow-2xl border border-gray-200 bg-white p-5"
  >
    <div class="lg:flex gap-x-16">
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Kwota kredytu</h3>
          <div class="relative">
            <input
              type="number"
              class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
              v-model="amountOfCredit"
            />
            <span
              class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
              >PLN</span
            >
          </div>
        </div>
        <input
          type="range"
          min="50000"
          max="2000000"
          step="10000"
          v-model="amountOfCredit"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">50 000 zł</span>
          <span class="label-text-alt text-black">2 000 000 zł</span>
        </label>
      </div>
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Okres spłaty</h3>
          <div class="relative">
            <input
              type="number"
              class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
              v-model="period"
            />
            <span
              class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
              >LAT</span
            >
          </div>
        </div>
        <input
          type="range"
          min="5"
          max="35"
          step="1"
          v-model="period"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">5 lat</span>
          <span class="label-text-alt text-black">35 lat</span>
        </label>
      </div>
    </div>
    <div class="lg:flex gap-x-16">
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Oprocentowanie</h3>
          <div class="relative">
            <input
              type="number"
              class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
              v-model="rate"
            />
            <span
              class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
              >%</span
            >
          </div>
        </div>
        <input
          type="range"
          min="0.00"
          max="15"
          step="0.01"
          v-model="rate"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">0,01%</span>
          <span class="label-text-alt text-black">15%</span>
        </label>
      </div>
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Prowizja</h3>
          <div class="relative">
            <input
              type="number"
              class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
              v-model="commission"
            />
            <span
              class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
              >%</span
            >
          </div>
        </div>
        <input
          type="range"
          min="0.00"
          max="15"
          step="0.01"
          v-model="commission"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">0%</span>
          <span class="label-text-alt text-black">15%</span>
        </label>
      </div>
    </div>
    <button @click="show" class="btn btn-primary mt-10 text-white">
      Oblicz ratę i koszt
    </button>
  </section>
  <section
    ref="results"
    :class="installment == null ? 'hidden' : ''"
    class="w-full mx-auto p-5 mt-10 flex flex-col rounded-lg shadow-2xl border border-gray-200 bg-white"
  >
    <h1 class="text-2xl font-semibold mb-10 text-gray-700">
      Wyniki obliczeń. Porównaj koszty kredytu
    </h1>
    <div class="flex w-full">
      <div class="w-2/4 mr-10">
        <div class="flex-col">
          <h3 class="text-xl font-semibold mb-10">Raty stałe</h3>
          <div class="flex justify-between">
            <p class="mb-5">Wysokość raty</p>
            <p class="text-xl font-bold">{{ installment }}</p>
          </div>
          <div class="flex justify-between">
            <p class="mb-5">Całkowita kwota do spłaty</p>
            <p class="text-xl font-bold">
              {{ formattedToPLN.format(cost + amountOfCredit) }}
            </p>
          </div>
        </div>
        <div class="flex justify-center items-center mt-10">
          <Chart :data="data" />
        </div>
      </div>
      <div class="w-2/4 ml-10">
        <div class="flex-col">
          <h3 class="text-xl font-semibold mb-10">Raty malejące</h3>
          <div class="flex justify-between">
            <p class="mb-5">Wysokość pierwszej raty</p>
            <p class="text-xl font-bold">200 000 zł</p>
          </div>
          <div class="flex justify-between">
            <p class="mb-5">Całkowita kwota do spłaty</p>
            <p class="text-xl font-bold">200 000 zł</p>
          </div>
        </div>
        <div class="flex justify-center items-center mt-10">
          <Chart :data="data" />
        </div>
      </div>
    </div>
  </section>
</template>
