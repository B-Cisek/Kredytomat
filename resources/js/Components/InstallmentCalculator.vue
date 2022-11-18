<script setup>
import { ref, defineEmits } from "vue";
import InputCalculator from "@/Components/InputCalculator.vue";
import { useRataCalkowitaStala } from "@/Composables/useRataCalkowitaStala.js";
import { useHarmonogram } from "@/Composables/useHarmonogram";
import { useHelpers } from "@/Composables/useHelpers";
import CreditSchedule from "@/Components/CreditSchedule.vue";

const { rataStalaFormatted, rataStala } = useRataCalkowitaStala();
const { harmonogram, kosztKredytu } = useHarmonogram();
const { formatHarmonogram } = useHelpers();

const amountOfCredit = ref(250000);
const period = ref(25);
const rate = ref(7);
const commission = ref(0);
const installment = ref(null);
const cost = ref(null);
const toBePaid = ref(null);

const formattedToPLN = new Intl.NumberFormat("pl-PL", {
  style: "currency",
  currency: "PLN",
  maximumFractionDigits: 2,
});

const show = () => {
  let harmon = harmonogram(amountOfCredit.value, period.value, rate.value);
  cost.value = kosztKredytu(harmon);
  installment.value = rataStalaFormatted(amountOfCredit.value, period.value, rate.value);
  toBePaid.value = amountOfCredit.value + cost.value;
};
</script>
<template>
  <section class="flex flex-col gap-5 w-full mx-auto rounded-lg shadow-md bg-white p-5">
    <div class="flex gap-x-16">
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Kwota kredytu</h3>
          <div class="relative">
            <input
              type="number"
              class="font-semibold input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
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
              class="input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
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
          v-model="period"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">5 lat</span>
          <span class="label-text-alt text-black">35 lat</span>
        </label>
      </div>
    </div>
    <div class="flex gap-x-16">
      <div class="flex-1">
        <div class="flex mb-3 items-center justify-between">
          <h3 class="font-semibold text-black">Oprocentowanie</h3>
          <div class="relative">
            <input
              type="number"
              class="input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
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
          min="0.01"
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
              class="input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
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
          min="0.01"
          max="15"
          step="0.01"
          v-model="commission"
          class="range range-primary"
        />
        <label class="label">
          <span class="label-text-alt text-black">0,01%</span>
          <span class="label-text-alt text-black">15%</span>
        </label>
      </div>
    </div>
    <button @click="show" class="btn btn-primary mt-10 text-white">
      Oblicz ratę i koszt
    </button>
    <section :class="installment == null ? 'hidden' : ''">
      <h1>
        Rata miesięczna: <span class="font-bold text-xl">{{ installment }}</span>
      </h1>
      <h1>
        Rata kapitałowa: <span class="font-bold text-xl">{{ installment }}</span>
      </h1>
      <h1>
        Rata odsetkowa: <span class="font-bold text-xl">{{ installment }}</span>
      </h1>
      <h1>
        Kwota kredytu:
        <span class="font-bold text-xl">{{ formattedToPLN.format(amountOfCredit) }}</span>
      </h1>
      <h1>
        Odsetki:
        <span class="font-bold text-xl">{{ formattedToPLN.format(cost) }}</span>
      </h1>
      <h1>
        Spłata razem:
        <span class="font-bold text-xl">{{ formattedToPLN.format(toBePaid) }}</span>
      </h1>
    </section>
  </section>
  <section class="mt-10">
    <CreditSchedule :schedule="schedule" />
  </section>
</template>
