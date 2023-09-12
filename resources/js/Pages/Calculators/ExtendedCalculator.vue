<script setup>
import Layout from "@/Layouts/Layout.vue";
import {computed, nextTick, onMounted, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";
import Collapse from "@/Components/Collapse.vue";
import ResultBox from "@/Components/ResultBox.vue";
import CreditSchedule from "@/Components/CreditSchedule.vue";
import useVuelidate from "@vuelidate/core";
import {between, numeric, required} from "@vuelidate/validators";
import FeesInputsList from "@/Components/InputsList/FeesInputsList.vue";
import CapitalRepaymentSimulation from "@/Components/CapitalRepaymentSimulation.vue";
import InterestRateChange from "@/Components/InterestRateChange.vue";
import {Head, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import ChangesInterestsRatesTable from "@/Components/Tables/ChangesInterestsRatesTable.vue";
import RangeWithInput from "@/Components/Inputs/RangeWithInput.vue";
import useLocalStorage from "@/Composables/useLocalStorage";
import RangeInputPeriod from "@/Components/Inputs/RangeInputPeriod.vue";
import RangeInputCommission from "@/Components/Inputs/RangeInputCommission.vue";
import {useLineChart} from "@/Composables/Charts/useLineChart";
import InterestRateChanges from "@/Components/InputsList/InterestRateChanges.vue";
import Spinner from "@/Components/Spinner.vue";
import {useCreditCalculation} from "@/Composables/useCreditCalculation";
import {CalendarDaysIcon} from "@heroicons/vue/24/outline";
import SetDateModal from "@/Components/Modals/SetDateModal.vue";

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

const results = ref(null);

const fees = ref({
  fixed: [],
  changing: []
});

const formData = useLocalStorage({
  date: {
    year: new Date().getFullYear(),
    month: new Date().getMonth()
  },
  amountOfCredit: 300000,
  period: 25,
  periodType: 'year',
  margin: 2,
  commission: 0,
  commissionType: 'percent',
  wibor: props.wiborList[0].value,
  typeOfInstallment: 'equal'
}, 'calculator-extended-data');

const defaultSchedule = ref([]);
const schedule = ref([]);

const rules = {
  amountOfCredit: {required, numeric, between: between(50000, 2000000)},
  period: {required, numeric, between: between(5, 420)},
  periodType: {required},
  margin: {required, numeric, between: between(0, 15)},
  commission: {required, numeric, between: between(0, 10000)},
  wibor: {required},
  typeOfInstallment: {required}
}
const wiborName = ref('');

const getProperWibor = () => {
  props.wiborList.forEach(val => {
    if (formData.value.wibor == Number(val.value)) {
      wiborName.value = val.type;
    }
  });
}

onMounted(() => {
  const exist = props.wiborList.some(obj => {
    let data = localStorage.getItem('calculator-extended-data');

    if (data) {
      return obj.value == JSON.parse(data).wibor
    }
  });

  if (!exist) {
    formData.value.wibor = props.wiborList[0].value;
  }

  interestRateChanges.value = JSON.parse(interestRateChangesStorage.value) ?? [];
  fees.value.fixed = JSON.parse(fixedFeesStorage.value) ?? [];
  fees.value.changing = JSON.parse(changingFeesStorage.value) ?? [];
});

const {options, chartData} = useLineChart();

const interestRateChangesStorage = ref(localStorage.getItem('calculator-extended-interest-rate-changes'));
const interestRateChanges = ref([]);

const getInterestRateChange = value => {
  interestRateChanges.value = value;
  localStorage.setItem('calculator-extended-interest-rate-changes', JSON.stringify(interestRateChanges.value));
}

const fixedFeesStorage = ref(localStorage.getItem("calculator-extended-fixed-fees"));
const changingFeesStorage = ref(localStorage.getItem("calculator-extended-changing-fees"));

const getFixedFees = (value) => {
  fees.value.fixed = value;
  localStorage.setItem("calculator-extended-fixed-fees", JSON.stringify(fees.value.fixed));
}

const getChangingFees = (value) => {
  fees.value.changing = value;
  localStorage.setItem("calculator-extended-changing-fees", JSON.stringify(fees.value.changing));
}

const v$ = useVuelidate(rules, formData);

const interestPartArray = ref(null);
const capitalPartArray = ref(null);

const {loading, getSchedule} = useCreditCalculation();

const getResult = async () => {
  const result = await v$.value.$validate();
  if (!result) return;

  const defaultRes = await getSchedule(formData.value.typeOfInstallment, formData);
  defaultSchedule.value = await defaultRes.data.schedule;

  const res = await getSchedule(formData.value.typeOfInstallment, formData, interestRateChanges, fees);
  schedule.value = await res.data.schedule;

  interestPartArray.value = getInterestPartArray(schedule.value);
  capitalPartArray.value = getCapitalPartArray(schedule.value);

  // format data for LineChart
  let label = [];
  for (let i = 1; i <= schedule.value.length; i++) {
    label.push(i);
  }

  chartData.value.datasets[0].data = interestPartArray.value;
  let combinedData = [];
  for (let i = 0; i < schedule.value.length; i++) {
    combinedData.push(schedule.value[i][3] + schedule.value[i][4]);
  }

  chartData.value.datasets[1].data = combinedData;
  chartData.value.labels = label;


  getProperWibor();
  await nextTick(() => results.value.scrollIntoView({behavior: "smooth"}))
}


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

const modalOpen = ref(false);

const changeStartDate = (value) => {
  if (value) {
    formData.value.date = value;
  }
  modalOpen.value = false;
}
</script>


<template>
  <Head title="Kalkulator rozszerzony"/>
  <Layout>
    <template v-slot:header>Kalkulator rozszerzony</template>

    <template v-slot:default>
      <SetDateModal
          v-if="modalOpen"
          @close="modalOpen = false"
          @change-date="changeStartDate"
          :date="formData.date"
      />
      <section
          class="relative flex flex-col lg:gap-8 gap-2 w-full mx-auto rounded-lg shadow-md border border-gray-200 bg-white p-5">

        <div @click="modalOpen = true" class="absolute -left-2 -top-5  bg-indigo-700 p-2 rounded-lg hover:bg-indigo-700/70 cursor-pointer">
            <CalendarDaysIcon class="h-7 w-7 text-white"/>
        </div>
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
                :error="v$.amountOfCredit.$error"
            />
          </div>
          <div class="flex-1">
            <RangeInputPeriod
                v-model="formData.period"
                v-model:type="formData.periodType"
                :error="v$.period.$error"
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
                :error="v$.margin.$error"
            />
          </div>
          <div class="flex-1">
            <RangeInputCommission
                v-model="formData.commission"
                v-model:type="formData.commissionType"
                :error="v$.commission.$error"
            />
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
                    :value="wibor.value"
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
              title="Zmiany oprocentowania:"
              placeholder="Oprocentowanie [%]"
              :data="interestRateChangesStorage"
          />
        </div>
        <div>
          <FeesInputsList
              @input-list="getFixedFees"
              title="Opłaty stałe:"
              placeholder="Kwota [PLN]"
              :data="fixedFeesStorage"
          />
        </div>
        <div>
          <FeesInputsList
              @input-list="getChangingFees"
              title="Opłaty zmienne:"
              placeholder="[%]"
              :data="changingFeesStorage"
          />
        </div>
        <button @click="getResult" class="btn btn-primary text-white w-full">
          <Spinner v-if="loading"/>
          {{ loading ? '' : 'Oblicz' }}
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
                  <span class="text-xl font-semibold">{{
                      formData.commission
                    }} {{ formData.commissionType === 'percent' ? '%' : 'zł' }}</span>
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
                  :commission="Number(formData.commission)"
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
