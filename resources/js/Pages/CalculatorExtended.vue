<script setup>
import Layout from "@/Layouts/Layout.vue";
import {computed, nextTick, onMounted, ref, watch} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";
import Collapse from "@/Components/Collapse.vue";
import ResultBox from "@/Components/ResultBox.vue";
import CreditSchedule from "@/Components/CreditSchedule.vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import {useEqualInstallments} from "@/Composables/useEqualInstallments";
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import FeesInputsList from "@/Components/InputsList/FeesInputsList.vue";
import CapitalRepaymentSimulation from "@/Components/CapitalRepaymentSimulation.vue";
import InterestRateChange from "@/Components/InterestRateChange.vue";
import {Link, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import ChangesInterestsRatesTable from "@/Components/Tables/ChangesInterestsRatesTable.vue";

const {formattedToPLN, getCapitalPartArray, getInterestPartArray, getTotalFixedFees, getTotalChangingFees} = useHelpers();

Chart.register(...registerables);

const auth = computed(() => usePage().props.value.auth);

const props = defineProps({
  wiborList: Object,
});

const fees = ref({
  fixed: [],
  changing: []
});

const fixedFeeStorage = ref(localStorage.getItem("extended-fixedFees"));
const changingFeeStorage = ref(localStorage.getItem("extended-changingFees"));

const getFixedFees = (value) => {
  fees.value.fixed = value;
  localStorage.setItem("extended-fixedFees", JSON.stringify(fees.value.fixed));
}

const getChangingFees = (value) => {
  fees.value.changing = value;
  localStorage.setItem("extended-changingFees", JSON.stringify(fees.value.changing));
}

const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: "smooth"});
}

const schedule = ref([])

const formData = ref({
  amountOfCredit: Number(localStorage.getItem("extended-amountOfCredit") ?? 300000),
  period: Number(localStorage.getItem("extended-period") ?? 25),
  margin: Number(localStorage.getItem("extended-margin") ?? 2),
  commission: Number(localStorage.getItem("extended-commission") ?? 0),
  wibor: Number(localStorage.getItem("extended-wibor")),
  typeOfInstallment: localStorage.getItem("extended-typeOfInstallment") ?? "rowne"
});

watch(formData.value, (newValue, oldValue) => {
  localStorage.setItem("extended-amountOfCredit", newValue.amountOfCredit.toString());
  localStorage.setItem("extended-period", newValue.period.toString());
  localStorage.setItem("extended-margin", newValue.margin.toString());
  localStorage.setItem("extended-commission", newValue.commission.toString());
  localStorage.setItem("extended-wibor", newValue.wibor.toString());
  localStorage.setItem("extended-typeOfInstallment", newValue.typeOfInstallment.toString());
});

const rules = {
  amountOfCredit: {required},
  period: {required},
  margin: {required},
  commission: {required},
  wibor: {required},
  typeOfInstallment: {required}
}

const v$ = useVuelidate(rules, formData);

const interestPartArray = ref(null);
const capitalPartArray = ref(null);

const getResult = async () => {
  const result = await v$.value.$validate();

  if (!result) {
    return;
  }

  if (formData.value.typeOfInstallment === "rowne") {
    schedule.value = useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: formData.value.amountOfCredit,
      period: formData.value.period,
      margin: formData.value.margin,
      wibor: formData.value.wibor,
      commission: formData.value.commission
    }, [], [], fees.value.fixed, fees.value.changing).getSchedule();
  } else {
    schedule.value = useDecreasinginstallments({
      date: new Date(2023, 0),
      amountOfCredit: formData.value.amountOfCredit,
      period: formData.value.period,
      margin: formData.value.margin,
      wibor: formData.value.wibor,
      commission: formData.value.commission
    }, [], []).getScheduleSmallerInstallment();
  }

  interestPartArray.value = getInterestPartArray(schedule.value);
  capitalPartArray.value = getCapitalPartArray(schedule.value);

  console.table(schedule.value)

  let label = [];
  for (let i = 1; i <= schedule.value.length; i++) {
    label.push(i);
  }
  chartData.value.datasets[0].data = interestPartArray.value;
  let combinedData = [];
  for (let i = 0; i < schedule.value.length; i++) {
    combinedData.push(schedule.value[i][2] + schedule.value[i][3]);
  }
  chartData.value.datasets[1].data = combinedData;
  chartData.value.labels = label;


  await nextTick(() => scrollToResult())
}

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: "RATA ODSETKOWA",
      fill: true,
      data: [],
      backgroundColor: '#DF2935',

    },
    {
      label: "RATA KAPITAŁOWA",
      fill: true,
      data: [],
      backgroundColor: '#1cb027',
    },
  ],
});

let options = {
  elements: {
    point: {
      pointRadius: 0
    }
  },
  plugins: {
    title: {
      display: true,
      text: 'Stacked'
    },
  },
  responsive: true,
  interaction: {
    intersect: false,
  },
  scales: {
    x: {
      stacked: true,
    },
    y: {
      ticks: {
        beginAtZero: true,
        stacked: true
      }
    }
  }
};

onMounted(() => {
  fees.value.fixed = JSON.parse(fixedFeeStorage.value);
  fees.value.changing = JSON.parse(changingFeeStorage.value);
});


const saveSimulation = () => {
  Inertia.post(route('credit-simulation.save'), {
    amount_of_credit: formData.value.amountOfCredit,
    period: formData.value.period,
    margin: formData.value.margin,
    commission: formData.value.commission,
    type_of_installment: formData.value.typeOfInstallment,
    wibor_id: formData.value.wibor,
    fixed_fees: JSON.stringify(fees.value.fixed),
    changing_fees: JSON.stringify(fees.value.changing),
  }, {preserveScroll: true});
}
</script>


<template>
  <Layout>
    <template v-slot:header>Kalkulator rozszerzony</template>

    <template v-slot:default>
      <section class="flex flex-col gap-8 w-full mx-auto rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <h3 class="font-semibold text-black">Kwota kredytu</h3>
              <div class="relative">
                <input
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="formData.amountOfCredit"
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
              v-model="formData.amountOfCredit"
              class="range range-primary bg-[#d1d3d9]"
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
                  v-model="formData.period"
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
              v-model="formData.period"
              class="range range-primary bg-[#d1d3d9]"
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
              <h3 class="font-semibold text-black">Marża</h3>
              <div class="relative">
                <input
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="formData.margin"
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
              v-model="formData.margin"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">0,01%</span>
              <span class="label-text-alt text-black">15%</span>
            </label>
          </div>
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <label for="" class="font-semibold text-black">Prowizja</label>
              <div class="relative">
                <input
                  id="commission"
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="formData.commission"
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
              v-model="formData.commission"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">0%</span>
              <span class="label-text-alt text-black">15%</span>
            </label>
          </div>
        </div>
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">WIBOR</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                v-model="formData.wibor"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option
                  v-for="wibor in props.wiborList"
                  :key="wibor.id"
                  :value="Number(wibor.value)"
                >{{ wibor.type + ` (${wibor.value}%)` }}
                </option>
              </select>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">Rodzaj rat</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                v-model="formData.typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="rowne">Równe</option>
                <option value="malejace">Malejące</option>
              </select>
            </div>
          </div>
        </div>
        <div>
          <FeesInputsList
            @input-list="getFixedFees"
            title="Opłaty stałe:"
            placeholder="Kwota [PLN]"
            :data="fixedFeeStorage"
          />
        </div>
        <div>
          <FeesInputsList
            @input-list="getChangingFees"
            title="Opłaty zmienne:"
            placeholder="[%]"
            :data="changingFeeStorage"
          />
        </div>
        <button @click="getResult" class="btn btn-primary text-white w-full">
          Oblicz
        </button>
      </section>
      <section
        class="flex flex-col gap-2 mt-5"
        ref="results"
        v-if="schedule.length">
        <Collapse class="relative" title="Twoje wyniki" :collapsed="true">
          <div
            v-if="auth.loggedIn"
            class="w-12 h-12 absolute rounded-full -left-5 -top-5 grid place-items-center">
            <button
              @click="saveSimulation"
            >
              <img title="Zapisz obliczenia" src="https://img.icons8.com/plasticine/100/null/plus-2-math.png" alt=""/>
            </button>
          </div>
          <div class="flex gap-3">
            <div class="flex-1 bg-[#21a142] p-5 rounded text-white flex">
              <div class="flex flex-col justify-between flex-1">
                <div>
                  <p class="mt-1">Kwota kredytu:</p>
                  <span class="text-xl font-semibold">{{ formattedToPLN.format(formData.amountOfCredit) }}</span>
                </div>
                <div>
                  <p class="mt-1">Okres spłaty:</p>
                  <span class="text-xl font-semibold">{{ formData.period }} lat</span>
                </div>
                <div>
                  <p class="mt-1">Oprocentowanie:</p>
                  <p class="text-xl font-semibold">{{ Number(formData.margin) + Number(formData.wibor) }}% <span class="text-sm font-normal">(WIBOR {{ formData.wibor }}% + marża {{ formData.margin}}%)</span></p>
                </div>
                <div>
                  <p class="mt-1">Prowizja:</p>
                  <span class="text-xl font-semibold">{{ formData.commission }}%</span>
                </div>
              </div>
              <div class="flex flex-col justify-between items-end text-right flex-1">
                <div>
                  <p class="mt-1">Rodzaj raty:</p>
                  <span class="text-xl font-semibold">{{ formData.typeOfInstallment === 'rowne' ? 'Równe' : 'Malejące' }}</span>
                </div>
                <div>
                  <p class="mt-1">WIBOR:</p>
                  <span class="text-xl font-semibold">3M</span>
                </div>
                <div>
                  <p class="mt-1">Opłaty stałe łącznie:</p>
                  <span class="text-xl font-semibold">{{ formattedToPLN.format(getTotalFixedFees(schedule)) }}</span>
                </div>
                <div>
                  <p class="mt-1">Opłaty zmienne łącznie:</p>
                  <span class="text-xl font-semibold">{{ formattedToPLN.format(getTotalChangingFees(schedule)) }}</span>
                </div>
              </div>
            </div>
            <div class="flex-1">
              <ResultBox :schedule="schedule"/>
            </div>
          </div>
          <div class="p-2 mt-3">
            <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
          </div>
        </Collapse>
        <Collapse title="Symulacja spłaty kapitału" :collapsed="false">
          <CapitalRepaymentSimulation :schedule="schedule" />
        </Collapse>
        <Collapse title="Jak zmieni się rata przy zmianie stopy procentowej?" :collapsed="false">
          <InterestRateChange :credit="formData"/>
        </Collapse>
        <Collapse title="Harmonogram spłaty kredytu" :collapsed="false">
          <CreditSchedule :schedule="schedule"/>
        </Collapse>
        <Collapse title="Symulacja zmiany raty dla zmian stóp procentowych" :collapsed="true">
          <ChangesInterestsRatesTable :schedule="schedule" :credit="formData"/>
        </Collapse>
      </section>
    </template>
  </Layout>
</template>
