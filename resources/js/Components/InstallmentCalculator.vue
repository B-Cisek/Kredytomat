<script setup>
import {nextTick, ref, watch} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import Chart from "@/Components/Chart.vue";
import RangeWithInput from "@/Components/RangeWithInput.vue";
import RangeWithInputSelect from "@/Components/RangeWithInputSelect.vue";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";

const {toDecimal, formattedToPLN, totalCreditCost} = useHelpers();

/** Form inputs */
const amountOfCredit = ref(250000);
const period = ref(25);
const rate = ref(7);
const commission = ref(0);
const commissionType = ref("percent");
const commissionResult = ref(0);

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
        <RangeWithInputSelect
          heading="Prowizja"
          @selected-type="setCommissionType"
          @commission-value="setCommissionValue"
        />
      </div>
    </div>
    <button @click="calc" class="btn btn-primary text-white">
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
              {{ formattedToPLN.format(equalInstallmentCost) }}
            </p>
          </div>
        </div>
        <div class="flex justify-center items-center mt-8">
          <Chart :data="dataRatyStale" :options="options"/>
        </div>
      </div>

      <!-- DIVIDERS START -->
      <div class="flex font-semibold items-center text-2xl text-[#e0e0e0] relative hidden lg:flex">
        <span id="divider-vertical">VS</span>
      </div>
      <div class="w-full flex font-semibold items-center justify-center lg:hidden relative text-2xl text-[#e0e0e0] mt-5">
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
            <p class="text-lg font-bold">{{ formattedToPLN.format(decreasingInstallmentCost) }}</p>
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
