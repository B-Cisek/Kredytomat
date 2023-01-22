<script setup>
import {nextTick, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {useRatyMalejace} from "@/Composables/useRatyMalejace";
import {useRatyStale} from "@/Composables/useRatyStale";
import Chart from "@/Components/Chart.vue";

const {getPierwszaRata, getHarmonogram: getHarmonogramMalejace} = useRatyMalejace();
const {getRataStala, getHarmonogram: getHarmonogramStale} = useRatyStale();
const {toDecimal, formattedToPLN, kosztKredytu} = useHelpers();

/** Form inputs */
const amountOfCredit = ref(250000);
const period = ref(25);
const rate = ref(7);
const commission = ref(0);


const commissionResult = ref(0);

const commissionCalculation = () => {
  commissionResult.value = amountOfCredit.value * toDecimal(commission.value);
}

/** Equal Installment */
const equalInstallment = ref(null);
const equalInstallmentCost = ref(0);
const equalInstallmentToBePaid = ref(0);

/** Decreasing Installment */
const firstDecreasingInstallment = ref(null);
const decreasingInstallmentCost = ref(0);
const decreasingInstallmentToBePaid = ref(0);

const calc = async () => {
  let harmonogramRatyStale = getHarmonogramStale(amountOfCredit.value, period.value, rate.value);
  let harmonogramRatyMalejace = getHarmonogramMalejace(amountOfCredit.value, period.value, rate.value);
  commissionCalculation();

  // Installments
  firstDecreasingInstallment.value = getPierwszaRata(amountOfCredit.value, period.value, rate.value);
  equalInstallment.value = getRataStala(amountOfCredit.value, period.value, rate.value);

  // Installments cost
  equalInstallmentCost.value = kosztKredytu(harmonogramRatyStale);
  decreasingInstallmentCost.value = kosztKredytu(harmonogramRatyMalejace);

  // The total amount to be repaid
  equalInstallmentToBePaid.value = Number(amountOfCredit.value) + equalInstallmentCost.value + commissionResult.value;
  decreasingInstallmentToBePaid.value =
    Number(amountOfCredit.value) + decreasingInstallmentCost.value + commissionResult.value;

  await nextTick(() => scrollToResult())
}

const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: "smooth"});
}

const dataRatyStale = {
  labels: ['Kwota kredytu', `Odsetki`, `Prowizja banku`],
  datasets: [
    {
      data: [amountOfCredit, equalInstallmentCost, commissionResult],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
};

const options = {
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
  }
};

const dataRatyMalejace = {
  labels: ['Kwota kredytu', `Odsetki`, `Prowizja banku`],
  datasets: [
    {
      label: 'dsadsa',
      data: [amountOfCredit, decreasingInstallmentCost, commissionResult],
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
    <button @click="calc" class="btn btn-primary mt-10 text-white">
      Oblicz ratę i koszt
    </button>
  </section>
  <section
    v-show="equalInstallment != null"
    ref="results"
    class="w-full mx-auto p-5 mt-8 flex flex-col rounded-lg shadow-2xl border border-gray-200 bg-white"
  >
    <h1 class="text-2xl font-semibold mb-10 text-gray-700">
      Wyniki obliczeń. Porównaj koszty kredytu
    </h1>
    <div class="lg:flex w-full">
      <div class="lg:w-2/4 lg:mr-10">
        <div class="flex-col p-3">
          <h3 class="text-xl font-bold mb-10">RATY STAŁE</h3>
          <div class="flex justify-between">
            <p class="mb-5">Wysokość raty</p>
            <p class="font-bold text-3xl text-indigo-600">{{ formattedToPLN.format(equalInstallment) }}</p>
          </div>
          <div class="flex justify-between mt-2">
            <p class="mb-5">Całkowita kwota do spłaty</p>
            <p class="font-bold text-lg">
              {{ formattedToPLN.format(equalInstallmentCost + amountOfCredit) }}
            </p>
          </div>
        </div>
        <div class="flex justify-center items-center mt-8">
          <Chart :data="dataRatyStale" :options="options"/>
        </div>
      </div>

      <!-- DIVIDERS START -->
      <div class="flex font-semibold items-center text-2xl text-gray-700 relative hidden lg:flex">
        <span id="divider-vertical">VS</span>
      </div>
      <div class="w-full flex font-semibold items-center justify-center lg:hidden relative text-2xl text-gray-700 mt-5">
        <span class="w-full text-center" id="divider-horizontal">VS</span>
      </div>
      <!-- DIVIDERS END -->

      <div class="lg:w-2/4 lg:ml-10">
        <div class="flex-col p-3">
          <h3 class="text-xl font-bold mb-10">RATY MALEJĄCE</h3>
          <div class="flex justify-between">
            <p class="mb-5">Wysokość pierwszej raty</p>
            <p class="font-bold text-3xl text-indigo-600">{{ formattedToPLN.format(firstDecreasingInstallment) }}</p>
          </div>
          <div class="flex justify-between mt-2">
            <p class="mb-5">Całkowita kwota do spłaty</p>
            <p class="text-lg font-bold">{{ formattedToPLN.format(decreasingInstallmentCost + amountOfCredit) }}</p>
          </div>
        </div>
        <div class="flex justify-center items-center mt-8">
          <Chart :data="dataRatyMalejace" :options="options"/>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
#divider-vertical:after {
  content: "";
  position: absolute;
  border-left: 2px solid #374151;
  height: 45%;
  left: 15px;
  bottom: 0;
}

#divider-vertical:before {
  content: "";
  position: absolute;
  border-left: 2px solid #374151;
  height: 45%;
  top: 0;
  left: 15px;
}

#divider-horizontal:after {
  content: "";
  position: absolute;
  border-top: 2px solid #374151;
  width: 40%;
  top: 15px;
  right: 5px;
}

#divider-horizontal:before {
  content: "";
  position: absolute;
  border-bottom: 2px solid #444653;
  width: 40%;
  left: 5px;
  top: 15px;
}
</style>
