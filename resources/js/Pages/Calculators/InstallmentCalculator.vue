<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Head} from "@inertiajs/inertia-vue3";
import RangeWithInput from "@/Components/Inputs/RangeWithInput.vue";
import {PieChart} from "vue-chart-3";
import {useHelpers} from "@/Composables/useHelpers";
import {Chart, registerables} from "chart.js";
import useLocalStorage from "@/Composables/useLocalStorage";
import {nextTick, onMounted, ref, watch} from "vue";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";
import {Inertia} from "@inertiajs/inertia";

const {toDecimal, formattedToPLN, totalCreditCost} = useHelpers();

Chart.register(...registerables);

/** Form inputs */
const amountOfCredit = useLocalStorage(250000, 'calculator-amountOfCredit');
const period = useLocalStorage(25, 'calculator-period');
const rate = useLocalStorage(7, 'calculator-rate');
const commission = useLocalStorage(0, 'calculator-commission');
const commissionType = useLocalStorage("percent", "calculator-commission-type");
const commissionResult = ref(0);

const min = ref(0);
const max = useLocalStorage(commissionType.value === "percent" ? 7 : 10000, 'calculator-max');
const step = useLocalStorage(commissionType.value === "percent" ? 0.1 : 1, 'calculator-step');


onMounted(() => {
  watch(commissionType, newValue => {
    if (newValue === "number") {
      max.value = 10000;
      step.value = 1;
      commission.value = (commission.value / 100) * amountOfCredit.value;
      commissionType.value = "number";
    } else {
      max.value = 7;
      step.value = 0.1;
      commission.value = Number(((commission.value / amountOfCredit.value) * 100).toFixed(2));
      commissionType.value = "percent";
    }
  });
});

const commissionCalculation = () => {
  if (commissionType.value === "percent") {
    commissionResult.value = amountOfCredit.value * toDecimal(commission.value);
  } else {
    commissionResult.value = commission.value;
  }
}

/** Equal Installment */
const equalInstallment = ref(null);
const equalInstallmentCost = ref(0);

/** Decreasing Installment */
const firstDecreasingInstallment = ref(null);
const decreasingInstallmentCost = ref(0);

const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: "smooth"});
}


const calc = async () => {
  let fixedInstallmentsSchedule = useEqualInstallmentsV2({
    date: new Date(),
    amountOfCredit: amountOfCredit.value,
    period: period.value,
    margin: 0,
    wibor: rate.value,
    commission: commission.value
  }).getSchedule();

  let decreasingInstallmentSchedule = useDecreasingInstallmentsV2({
    date: new Date(),
    amountOfCredit: amountOfCredit.value,
    period: period.value,
    margin: 0,
    wibor: rate.value,
    commission: commission.value
  }).getSchedule();

  commissionCalculation();

  // Installments
  firstDecreasingInstallment.value = decreasingInstallmentSchedule[0][4];
  equalInstallment.value = fixedInstallmentsSchedule[0][4];

  // Installments cost
  equalInstallmentCost.value = totalCreditCost(fixedInstallmentsSchedule) + commissionResult.value;
  decreasingInstallmentCost.value = totalCreditCost(decreasingInstallmentSchedule) + commissionResult.value;

  await nextTick(() => scrollToResult())
}

const setCommissionType = value => {
  commission.value = 0;
  commissionType.value = value;
}

const setCommissionValue = value => {
  commission.value = value;
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

const options = ref({
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
  }
});

const dataRatyMalejace = ref({
  labels: ['Kwota kredytu', `Odsetki`, `Prowizja banku`],
  datasets: [
    {
      label: 'dsadsa',
      data: [amountOfCredit, decreasingInstallmentCost, commissionResult],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
});


const test = () => {
  axios.post(route('get-schedule'), {
    TypeOfInstallment: 'decreasing',
    date: new Date(2023, 7),
    credit: {
      amountOfCredit: 500000,
      period: 20,
      periodType: 'year',
      margin: 1,
      wibor: 6,
      commission: 0,
      commissionType: 'percent'
    },
    overpayments: [],
    interestsRateChange: [],
    fees: {
      fixed: [],
      changing: []
    }
  }).then(res => console.log(res.data));
}

test()

</script>

<template>
  <Head title="Kalkulator raty kredytu"/>

  <Layout>
    <template v-slot:header>Kalkulator raty kredytu</template>

    <template v-slot:default>
      <section
        class="flex flex-col gap-10 w-full mx-auto rounded-lg shadow-2xl border border-gray-200 bg-white p-5"
      >
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <RangeWithInput
              v-model="amountOfCredit"
              input-type-label="PLN"
              heading="Kwota kredytu"
              :min="50000"
              :max="2000000"
              :step="10000"
              label-left="50 000 zł"
              label-right="2 000 000 zł"
            />
          </div>
          <div class="flex-1">
            <RangeWithInput
              v-model="period"
              input-type-label="LAT"
              heading="Okres spłaty"
              :min="5"
              :max="35"
              :step="1"
              label-left="5 lat"
              label-right="35 lat"
            />
          </div>
        </div>
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <RangeWithInput
              v-model="rate"
              input-type-label="%"
              heading="Oprocentowanie"
              :min="0.01"
              :max="15"
              :step="0.01"
              label-left="0,01%"
              label-right="15%"
            />
          </div>
          <div class="flex-1">
            <div>
              <div class="flex mb-3 items-center justify-between">

                <h3 class="font-semibold text-black">Prowizja</h3>

                <div class="relative">
                  <input
                    v-model="commission"
                    type="number"
                    class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
                  />
                  <select
                    v-model="commissionType"
                    class="appearance-none cursor-pointer absolute right-0 w-25 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white">
                    <option selected value="number">PLN</option>
                    <option value="percent">%</option>
                  </select>
                </div>
              </div>

              <input
                v-model.number="commission"
                type="range"
                :min="min"
                :max="max"
                :step="step"
                class="range range-primary bg-[#d1d3d9]"
              />

              <label class="label">
                <span class="label-text-alt text-black">{{ min }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
                <span class="label-text-alt text-black">{{ max }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
              </label>
            </div>
          </div>
        </div>
        <button @click="calc" class="btn btn-primary text-white">
          Oblicz ratę i koszt
        </button>
      </section>
      <section
        v-if="equalInstallment != null"
        ref="results"
        class="w-full mx-auto p-5 mt-5 flex flex-col rounded-lg shadow-2xl border border-gray-200 bg-white"
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
                  {{ formattedToPLN.format(equalInstallmentCost) }}
                </p>
              </div>
            </div>
            <div class="flex justify-center items-center mt-8">
              <PieChart :chartData="dataRatyStale" :options="options"/>
            </div>
          </div>

          <!-- DIVIDERS START -->
          <div class="flex font-semibold items-center text-2xl text-[#e0e0e0] relative hidden lg:flex">
            <span id="divider-vertical">VS</span>
          </div>
          <div
            class="w-full flex font-semibold items-center justify-center lg:hidden relative text-2xl text-[#e0e0e0] mt-5">
            <span class="w-full text-center" id="divider-horizontal">VS</span>
          </div>
          <!-- DIVIDERS END -->

          <div class="lg:w-2/4 lg:ml-10">
            <div class="flex-col p-3">
              <h3 class="text-xl font-bold mb-10">RATY MALEJĄCE</h3>
              <div class="flex justify-between">
                <p class="mb-5">Wysokość pierwszej raty</p>
                <p class="font-bold text-3xl text-indigo-600">{{
                    formattedToPLN.format(firstDecreasingInstallment)
                  }}</p>
              </div>
              <div class="flex justify-between mt-2">
                <p class="mb-5">Całkowita kwota do spłaty</p>
                <p class="text-lg font-bold">{{ formattedToPLN.format(decreasingInstallmentCost) }}</p>
              </div>
            </div>
            <div class="flex justify-center items-center mt-8">
              <PieChart :chartData="dataRatyMalejace" :options="options"/>
            </div>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>
