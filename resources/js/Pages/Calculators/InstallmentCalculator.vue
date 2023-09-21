<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Head} from "@inertiajs/inertia-vue3";
import RangeWithInput from "@/Components/Inputs/RangeWithInput.vue";
import {PieChart} from "vue-chart-3";
import {useHelpers} from "@/Composables/useHelpers";
import {Chart, registerables} from "chart.js";
import useLocalStorage from "@/Composables/useLocalStorage";
import {nextTick, ref} from "vue";
import RangeInputPeriod from "@/Components/Inputs/RangeInputPeriod.vue";
import RangeInputCommission from "@/Components/Inputs/RangeInputCommission.vue";
import Spinner from "@/Components/Spinner.vue";
import {usePieChart} from "@/Composables/Charts/usePieChart";
import useVuelidate from "@vuelidate/core";
import {required, between, numeric, integer} from "@vuelidate/validators";

const {toDecimal, formattedToPLN, totalCreditCost} = useHelpers();

Chart.register(...registerables);

/** Calculator inputs */
const amountOfCredit = useLocalStorage(250000, 'calculator-amount-of-credit');
const period = useLocalStorage(25, 'calculator-period');
const periodType = useLocalStorage('year', 'calculator-period-type');
const rate = useLocalStorage(7, 'calculator-rate');
const commission = useLocalStorage(0, 'calculator-commission');
const commissionType = useLocalStorage("percent", "calculator-commission-type");

const amount = ref(0);

/** Equal Installment */
const equalInstallment = ref(null);
const equalInstallmentCost = ref(0);

/** Decreasing Installment */
const firstDecreasingInstallment = ref(null);
const decreasingInstallmentCost = ref(0);

const commissionResult = ref(0);
const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: 'smooth'});
}

// TODO: it should be in component
const calculateCommission = () => {
  if (commissionType.value === 'number') {
    commissionResult.value = commission.value;
  } else {
    commissionResult.value = amountOfCredit.value * toDecimal(commission.value);
  }
}

const loading = ref(false);

const getSchedule = async (type) => {
  loading.value = true;

  try {
    let res = await axios.post(route('get-schedule'), {
      typeOfInstallment: type,
      date: {
        year: new Date().getFullYear(),
        month: new Date().getMonth() + 1
      },
      credit: {
        amountOfCredit: amountOfCredit.value,
        period: period.value,
        periodType: periodType.value,
        margin: rate.value,
        wibor: 0,
        commission: 0,
        commissionType: 'percent'
      }
    })

    loading.value = false;
    return res;
  } catch (error) {
    loading.value = false;
    console.error(error); // TODO: add message error to toast
  }
}

// validation rules
const rules = {
  amountOfCredit: {required, numeric, integer, between: between(50000, 2000000)},
  period: {required, numeric, integer, between: between(5, 420)},
  rate: {required, numeric, between: between(0.01, 15)},
  commission: {required, numeric, between: between(0, 10000)}
}

const v$ = useVuelidate(rules, {
  amountOfCredit, period, rate, commission
});


const calc = async () => {
  const result = await v$.value.$validate();
  if (!result) return;

  let resDecreasingSchedule = await getSchedule('decreasing');
  let resEqualSchedule = await getSchedule('equal');
  let decreasingSchedule = resDecreasingSchedule.data.schedule;
  let equalSchedule = resEqualSchedule.data.schedule;

  // Installments
  firstDecreasingInstallment.value = decreasingSchedule[0][5];
  equalInstallment.value = equalSchedule[0][5];

  calculateCommission();

  // Installments cost
  const costEqual = totalCreditCost(equalSchedule) + commissionResult.value;
  equalInstallmentCost.value = Math.round(costEqual * 100) / 100;

  const costDecreasing = totalCreditCost(decreasingSchedule) + commissionResult.value;
  decreasingInstallmentCost.value = Math.round(costDecreasing * 100) / 100;

  dataEqualInstallment.value.datasets[0].data[0] = amountOfCredit.value;
  dataEqualInstallment.value.datasets[0].data[1] = equalInstallmentCost.value;
  dataEqualInstallment.value.datasets[0].data[2] = commissionResult.value;

  dataDecreasingInstallment.value.datasets[0].data[0] = amountOfCredit.value;
  dataDecreasingInstallment.value.datasets[0].data[1] = decreasingInstallmentCost.value;
  dataDecreasingInstallment.value.datasets[0].data[2] = commissionResult.value;

  await nextTick(() => scrollToResult());
}

const dataEqualInstallment = ref({
  labels: ['Kwota kredytu', 'Odsetki', 'Prowizja banku'],
  datasets: [
    {
      data: [0, equalInstallmentCost, commissionResult],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
});

const options = ref({
  responsive: true,
  plugins: {
    legend: {
      position: 'top',
    },
  }
});

const dataDecreasingInstallment = ref({
  labels: ['Kwota kredytu', 'Odsetki', 'Prowizja banku'],
  datasets: [
    {
      data: [0, decreasingInstallmentCost, commissionResult],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
});

</script>

<template>
  <Head title="Kalkulator raty kredytu"/>

  <Layout>
    <template v-slot:header>Kalkulator raty kredytu</template>

    <template v-slot:default>
      <section
        class="flex flex-col lg:gap-10 w-full mx-auto rounded-lg shadow-2xl border border-gray-200 bg-white p-5"
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
              :error="v$.amountOfCredit.$error"
            />
          </div>
          <div class="flex-1">
            <RangeWithInput
              v-model="period"
              input-type-label="LAT"
              heading="Okres"
              :min="5"
              :max="35"
              :step="1"
              label-left="5 lat"
              label-right="35 lat"
              :error="v$.period.$error"
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
              :error="v$.rate.$error"
            />
          </div>
          <div class="flex-1">
            <RangeInputCommission
              v-model="commission"
              v-model:type="commissionType"
              :error="v$.commission.$error"
            />
          </div>
        </div>
        <button @click="calc" class="btn btn-primary text-white">
          <Spinner v-if="loading"/>
          {{ loading ? '' : 'Oblicz ratę i koszt' }}
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
              <PieChart :chartData="dataEqualInstallment" :options="options"/>
            </div>
          </div>

          <!-- DIVIDER START -->
          <div class="flex font-semibold items-center text-2xl text-[#e0e0e0] relative hidden lg:flex">
            <span id="divider-vertical">VS</span>
          </div>
          <div
            class="w-full flex font-semibold items-center justify-center lg:hidden relative text-2xl text-[#e0e0e0] mt-5">
            <span class="w-full text-center" id="divider-horizontal">VS</span>
          </div>
          <!-- DIVIDER END -->

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
              <PieChart :chartData="dataDecreasingInstallment" :options="options"/>
            </div>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>

<style scoped>
#divider-vertical:after {
  content: "";
  position: absolute;
  border-left: 2px solid #e0e0e0;
  height: 45%;
  left: 15px;
  bottom: 0;
}

#divider-vertical:before {
  content: "";
  position: absolute;
  border-left: 2px solid #e0e0e0;
  height: 45%;
  top: 0;
  left: 15px;
}

#divider-horizontal:after {
  content: "";
  position: absolute;
  border-top: 2px solid #e0e0e0;
  width: 40%;
  top: 15px;
  right: 5px;
}

#divider-horizontal:before {
  content: "";
  position: absolute;
  border-bottom: 2px solid #e0e0e0;
  width: 40%;
  left: 5px;
  top: 15px;
}
</style>
