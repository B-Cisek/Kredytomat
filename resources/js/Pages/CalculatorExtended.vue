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
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import ChangesInterestsRatesTable from "@/Components/Tables/ChangesInterestsRatesTable.vue";
import RangeWithInput from "@/Components/RangeWithInput.vue";
import RangeWithInputSelect from "@/Components/RangeWithInputSelect.vue";
import InterestRateChanges from "@/Components/InputsList/InterestRateChanges.vue";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";
import useLocalStorage from "@/Composables/useLocalStorage";

const {
  formattedToPLN,
  getCapitalPartArray,
  getInterestPartArray,
  getTotalFixedFees,
  getTotalChangingFees
} = useHelpers();

Chart.register(...registerables);

const auth = computed(() => usePage().props.value.auth);

const props = defineProps({
  wiborList: Object,
  defaultData: Object
});

const fees = ref({
  fixed: [],
  changing: []
});

const interestRateChangesStorage = ref(localStorage.getItem("calculator-extended-interest-rate-changes"));
const interestRateChanges = ref([]);

const getFixedFees = (value) => {
  fees.value.fixed = value;
}

const getChangingFees = (value) => {
  fees.value.changing = value;
}

const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: "smooth"});
}

const defaultSchedule = ref([]);
const schedule = ref([]);

const formData = useLocalStorage({
  date: new Date(2023, 0),
  amountOfCredit: 300000,
  period: 25,
  margin: 2,
  commission: 0,
  wibor: Number(props.wiborList[0].value),
  typeOfInstallment: "equal",
  commissionType: "percent"
}, 'calculator-extended-data');

const commission = useLocalStorage(0, 'calculator-extended-commission');
const commissionType = useLocalStorage('percent', 'calculator-extended-commission-type');

const min = ref(0);
const max = useLocalStorage(formData.value.commissionType === "percent" ? 7 : 10000, 'calculator-extended-max');
const step = useLocalStorage(formData.value.commissionType === "percent" ? 0.1 : 1, 'calculator-extended-step');

onMounted(() => {
  watch(commissionType, (newFormData, oldFormData) => {
    if (newFormData !== oldFormData) {
      if (newFormData === 'percent') {
        max.value = 7;
        step.value = 0.1;
        formData.value.commissionType = 'percent';
        commission.value = ((commission.value / formData.value.amountOfCredit) * 100).toFixed(2);
      }

      if (newFormData === 'number') {
        max.value = 10000;
        step.value = 1;
        formData.value.commissionType = 'number';
        commission.value = (commission.value * formData.value.amountOfCredit) / 100;
      }
    }
  });
});

watch(commission, newValue => {
  formData.value.commission = newValue;
});

const rules = {
  amountOfCredit: {required},
  period: {required},
  margin: {required},
  commission: {required},
  wibor: {required},
  typeOfInstallment: {required}
}
const wiborName = ref("");

const getProperWibor = () => {
  props.wiborList.forEach(val => {
    if (formData.value.wibor === Number(val.value)) {
      wiborName.value = val.type;
    }
  });
}

const v$ = useVuelidate(rules, formData);

const interestPartArray = ref(null);
const capitalPartArray = ref(null);

const getResult = async () => {
  const result = await v$.value.$validate();

  formData.value.date = new Date(formData.value.date);

  if (!result) {
    return;
  }

  if (formData.value.typeOfInstallment === "equal") {
    schedule.value = useEqualInstallmentsV2(
      formData.value,
      [],
      interestRateChanges.value,
      fees.value.fixed,
      fees.value.changing).getSchedule();

    defaultSchedule.value = useEqualInstallmentsV2(
      formData.value,
      [],
      [],
      [],
      []).getSchedule();
  } else {
    schedule.value = useDecreasingInstallmentsV2(
      formData.value,
      [],
      interestRateChanges.value,
      fees.value.fixed,
      fees.value.changing).getSchedule();

    defaultSchedule.value = useDecreasingInstallmentsV2(
      formData.value,
      [],
      [],
      [],
      []).getSchedule();
  }

  console.table(schedule.value)

  interestPartArray.value = getInterestPartArray(schedule.value);
  capitalPartArray.value = getCapitalPartArray(schedule.value);

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

  getProperWibor();
  await nextTick(() => scrollToResult())
}

const chartData = ref({
  labels: [],
  datasets: [
    {
      label: "Rata odsetkowa",
      fill: true,
      data: [],
      backgroundColor: '#DF2935',

    },
    {
      label: "Rata kapitałowa",
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
      display: false,
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

const overwriteData = () => {
  formData.value.amountOfCredit = props.defaultData.amountOfCredit ?? formData.value.amountOfCredit;
  formData.value.commission = Number(props.defaultData.commission) ?? formData.value.commission;
  formData.value.margin = props.defaultData.margin ?? formData.value.margin;
  formData.value.period = props.defaultData.period ?? formData.value.period;
  formData.value.typeOfInstallment = props.defaultData.typeOfInstallment ?? formData.value.typeOfInstallment;
  formData.value.wibor = props.defaultData.wibor ?? formData.value.wibor;
  fees.value.fixed = JSON.parse(decodeURIComponent(props.defaultData.fixedFees)) ?? fees.value.fixed;
  fees.value.changing = JSON.parse(decodeURIComponent(props.defaultData.changingFees)) ?? fees.value.changing;
}

onMounted(() => {
  overwriteData();
});


const saveSimulation = () => {
  Inertia.post(route('credit-simulation.save'), {
    amount_of_credit: formData.value.amountOfCredit,
    period: formData.value.period,
    margin: formData.value.margin,
    commission: formData.value.commission,
    commission_type: formData.value.commissionType,
    type_of_installment: formData.value.typeOfInstallment,
    wibor_id: formData.value.wibor,
    fixed_fees: JSON.stringify(fees.value.fixed),
    changing_fees: JSON.stringify(fees.value.changing),
    interest_changes: JSON.stringify(interestRateChanges.value)
  }, {preserveScroll: true});
}

const getInterestRateChange = value => {
  interestRateChanges.value = value;
  localStorage.setItem("calculator-extended-interest-rate-changes", JSON.stringify(interestRateChanges.value));
}
</script>


<template>
  <Layout>
    <template v-slot:header>Kalkulator rozszerzony</template>

    <template v-slot:default>
      <section class="flex flex-col lg:gap-8 gap-2 w-full mx-auto rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <RangeWithInput
              v-model="formData.amountOfCredit"
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
              v-model="formData.period"
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
              v-model="formData.margin"
              input-type-label="%"
              heading="Marża"
              :min="0.00"
              :max="15"
              :step="0.01"
              label-left="0%"
              label-right="15%"
            />
          </div>
          <div class="flex-1">
            <div>
              <div className="flex mb-3 items-center justify-between">

                <h3 className="font-semibold text-black">Prowizja</h3>

                <div className="relative">
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

              <label className="label">
                <span className="label-text-alt text-black">{{ min }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
                <span className="label-text-alt text-black">{{ max }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
              </label>
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-4 lg:flex-row gap-x-16">
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">WIBOR</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-[260px] w-[180px]"
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
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-[260px] w-[180px]"
                v-model="formData.typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="equal">Równe</option>
                <option value="decreasing">Malejące</option>
              </select>
            </div>
          </div>
        </div>
        <div>
          <InterestRateChanges
            @input-list="getInterestRateChange"
            title="Zmiany oprocentowania"
            placeholder="Oprocentowanie [%]"
            :data="interestRateChanges"
          />
        </div>
        <div>
          <FeesInputsList
            @input-list="getFixedFees"
            title="Opłaty stałe:"
            placeholder="Kwota [PLN]"
            :data="fees.fixed"
          />
        </div>
        <div>
          <FeesInputsList
            @input-list="getChangingFees"
            title="Opłaty zmienne:"
            placeholder="[%]"
            :data="fees.changing"
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
          <!--  SAVE BUTTON   -->
          <div
            id="save-button"
            v-if="auth.loggedIn"
            class="w-12 h-12 absolute rounded-full -left-5 -top-5 grid place-items-center">
            <button
              @click="saveSimulation"
            >
              <img title="Zapisz obliczenia" src="https://img.icons8.com/plasticine/100/null/plus-2-math.png" alt=""/>
            </button>
          </div>

          <div class="flex gap-3 flex-col lg:flex-row">
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
                  <p class="text-xl font-semibold">
                    {{ Math.round((Number(formData.margin) + Number(formData.wibor)) * 100) / 100 }}%
                    <span class="text-sm font-normal">
                      (WIBOR {{ formData.wibor }}% + marża {{ formData.margin }}%)
                  </span>
                  </p>
                </div>
                <div>
                  <p class="mt-1">Prowizja:</p>
                  <span class="text-xl font-semibold">{{ formData.commission }} {{formData.commissionType === 'percent' ? '%' : 'zł'}}</span>
                </div>
              </div>
              <div class="flex flex-col justify-between items-end text-right flex-1">
                <div>
                  <p class="mt-1">Rodzaj raty:</p>
                  <span class="text-xl font-semibold">{{
                      formData.typeOfInstallment === 'equal' ? 'Równe' : 'Malejące'
                    }}</span>
                </div>
                <div>
                  <p class="mt-1">WIBOR:</p>
                  <span class="text-xl font-semibold">{{ wiborName }}</span>
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
              <ResultBox
                :schedule="defaultSchedule"
                :amount-of-credit="formData.amountOfCredit"
                :commission="formData.commission"
                :commission-type="formData.commissionType"
              />
            </div>
          </div>

          <div class="p-2 mt-3">
            <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
          </div>
        </Collapse>

        <Collapse title="Symulacja spłaty kapitału" :collapsed="true">
          <CapitalRepaymentSimulation :schedule="defaultSchedule"/>
        </Collapse>

        <Collapse v-if="formData.typeOfInstallment === 'equal'" title="Roczny wzrost obciążeń z tytułu kredytu"
                  :collapsed="true">
          <InterestRateChange :credit="formData" :schedule="defaultSchedule"/>
        </Collapse>

        <Collapse title="Symulacja zmiany raty dla zmian stóp procentowych" :collapsed="true">
          <ChangesInterestsRatesTable :schedule="defaultSchedule" :credit="formData"/>
        </Collapse>

        <Collapse title="Harmonogram spłaty kredytu" :collapsed="true">
          <CreditSchedule :schedule="schedule"/>
        </Collapse>
      </section>
    </template>
  </Layout>
</template>

<style scoped>
#save-button:hover {
  transform: scale(1.5);
}

#save-button {
  transition: transform .2s;
}
</style>
