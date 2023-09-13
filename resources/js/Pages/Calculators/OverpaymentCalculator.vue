<script setup>
import Layout from "@/Layouts/Layout.vue";
import {computed, nextTick, onMounted, ref, watch} from "vue";
import useVuelidate from "@vuelidate/core";
import {between, numeric, required} from "@vuelidate/validators";
import RangeWithInput from "@/Components/Inputs/RangeWithInput.vue";
import {useHelpers} from "@/Composables/useHelpers";
import CreditScheduleOverpayment from "@/Components/Tables/CreditScheduleOverpayment.vue";
import OverpaymentInputsList from "@/Components/InputsList/OverpaymentInputsList.vue";
import Collapse from "@/Components/Collapse.vue";
import {Head, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";
import useLocalStorage from "@/Composables/useLocalStorage";
import RangeInputPeriod from "@/Components/Inputs/RangeInputPeriod.vue";
import RangeInputCommission from "@/Components/Inputs/RangeInputCommission.vue";
import SetDateModal from "@/Components/Modals/SetDateModal.vue";
import {CalendarDaysIcon} from "@heroicons/vue/24/outline";
import {useCreditCalculation} from "@/Composables/useCreditCalculation";
import {useLineChartOverpayments} from "@/Composables/Charts/useLineChartOverpayments";
import Spinner from "@/Components/Spinner.vue";

const {totalCreditCost, totalCreditInterest, formattedToPLN, getInterestPartArray} = useHelpers();
const {options, chartData} = useLineChartOverpayments();
const auth = computed(() => usePage().props.value.auth);
Chart.register(...registerables);
const results = ref(null);

const props = defineProps({
  wiborList: Object,
  defaultData: Object
});

const overpayments = ref([]);
const overpaymentsStorage = ref(localStorage.getItem("overpayment-values"));
const overpaymentType = ref(localStorage.getItem("overpayment-type") ?? "period");
const schedule = ref([]);
const baseCreditSchedule = ref([]);
const costCostDiff = ref(null);
const monthsLess = ref(0);
const costLessPercent = ref(0);

const commission = useLocalStorage(0, 'calculator-overpayment-commission');
const commissionType = useLocalStorage('percent', 'calculator-overpayment-commission-type');

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
}, 'calculator-overpayment-data');

const wiborName = ref('');

const getProperWibor = () => {
  props.wiborList.forEach(val => {
    if (formData.value.wibor == Number(val.value)) {
      wiborName.value = val.type;
    }
  });
}

const rules = {
  amountOfCredit: {required, numeric, between: between(50000, 2000000)},
  period: {required, numeric, between: between(5, 420)},
  periodType: {required},
  margin: {required, numeric, between: between(0, 15)},
  commission: {required, numeric, between: between(0, 10000)},
  commissionType: {required},
  wibor: {required},
  typeOfInstallment: {required}
}

const v$ = useVuelidate(rules, formData);

const totalCost = ref(0);

const {loading, getSchedule} = useCreditCalculation();

const getResult = async () => {
  const result = await v$.value.$validate();
  if (!result) return;

  const baseRes = await getSchedule(formData);
  baseCreditSchedule.value = await baseRes.data.schedule;

  const overpaymentRes = await getSchedule(
      formData,
      {},
      {},
      overpayments,
      overpaymentType
  );
  schedule.value = await overpaymentRes.data.schedule;

  totalCost.value = totalCreditCost(overpaymentRes);

  console.log(totalCreditInterest(baseCreditSchedule.value))
  console.log(totalCreditInterest(schedule.value))
  costCostDiff.value = totalCreditInterest(baseCreditSchedule.value) - totalCreditInterest(schedule.value);
  monthsLess.value = baseCreditSchedule.value.length - schedule.value.length;
  costLessPercent.value = 100 - ((totalCreditInterest(schedule.value) / totalCreditInterest(baseCreditSchedule.value)) * 100);

  let label = [];
  for (let i = 1; i <= schedule.value.length; i++) {
    label.push(i);
  }

  chartData.value.labels = label;
  chartData.value.datasets[0].data = getInterestPartArray(schedule.value);
  chartData.value.datasets[1].data = getInterestPartArray(baseCreditSchedule.value);

  getProperWibor();
  await nextTick(() => results.value.scrollIntoView({behavior: 'smooth'}));
}

const getOverpayments = (value) => {
  overpayments.value = value;
  localStorage.setItem("overpayment-values", JSON.stringify(overpayments.value));
}

const getType = (value) => {
  overpaymentType.value = value;
  localStorage.setItem("overpayment-type", value);
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

  overpayments.value = JSON.parse(overpaymentsStorage.value) ?? [];
});

const saveSimulation = () => {
  Inertia.post(route('profil.overpayment.save'), {
    amount_of_credit: formData.value.amountOfCredit,
    period: formData.value.period,
    margin: formData.value.margin,
    commission: formData.value.commission,
    commission_type: formData.value.commissionType,
    type_of_installment: formData.value.typeOfInstallment,
    wibor_id: formData.value.wibor,
    overpayment_type: overpaymentType.value,
    overpayments: JSON.stringify(overpayments.value),
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
  <Head title="Kalkulator nadpłaty kredytu"/>
  <Layout>
    <template v-slot:header>
      Kalkulator nadpłaty kredytu
    </template>
    <template v-slot:default>
      <SetDateModal
          v-if="modalOpen"
          @close="modalOpen = false"
          @change-date="changeStartDate"
          :date="formData.date"
      />
      <section
          class="relative flex flex-col lg:gap-8 gap-2 w-full mx-auto rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div @click="modalOpen = true"
             class="absolute -left-2 -top-5  bg-indigo-700 p-2 rounded-lg hover:bg-indigo-700/70 cursor-pointer">
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
        <div class="lg:flex gap-x-16 gap-4">
          <div class="flex-1">
            <div class="flex justify-between items-center mb-5 lg:mb-0">
              <label class="font-semibold text-black" for="wibor">WIBOR</label>
              <select
                  class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-[260px] w-[180px]"
                  :class="{'border-red-700': v$.wibor.$errors.length !== 0}"
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
            <span
                v-for="error in v$.wibor.$errors"
                :key="error.$uid"
                class="text-red-700"
            >Wybierz wibor</span>
          </div>
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">Rodzaj rat</label>
              <select
                  class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-[260px] w-[180px]"
                  :class="{'border-red-700': v$.typeOfInstallment.$errors.length !== 0}"
                  v-model="formData.typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="equal">Równe</option>
                <option value="decreasing">Malejące</option>
              </select>
            </div>
            <span
                v-for="error in v$.typeOfInstallment.$errors"
                :key="error.$uid"
                class="text-red-700"
            >Wybierz rodzaj raty</span>
          </div>
        </div>
        <div class="mt-7">
          <div>
            <OverpaymentInputsList
                :data="overpaymentsStorage"
                @input-list="getOverpayments"
                @type="getType"
                placeholder="PLN"
            />
          </div>
        </div>
        <button @click="getResult" class="btn btn-primary text-white w-full">
          <Spinner v-if="loading"/>
          {{ loading ? '' : 'Oblicz' }}
        </button>
      </section>

      <section
          ref="results"
          v-if="schedule.length"
          class="flex flex-col gap-y-2 mt-5"
      >
        <Collapse title="Dane" :collapsed="true">

          <div
              id="save-button"
              v-if="auth.loggedIn"
              class="w-12 h-12 absolute rounded-full -left-5 -top-5 grid place-items-center">
            <button
                @click="saveSimulation">
              <img title="Zapisz obliczenia" src="https://img.icons8.com/plasticine/100/null/plus-2-math.png"/>
            </button>
          </div>

          <div class="bg-[#21a142] px-5 pt-5 rounded-t text-white flex">
            <div class="flex flex-col justify-between flex-1 gap-3">
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
                  {{ Number(formData.wibor) + formData.margin }}%
                  <span class="text-sm font-normal">(WIBOR {{ formData.wibor }}% + marża {{ formData.margin }}%)</span>
                </p>
              </div>
            </div>
            <div class="flex flex-col justify-between items-end text-right flex-1">
              <div>
                <p class="mt-1">Rodzaj raty:</p>
                <span class="text-xl font-semibold">
                  {{ formData.typeOfInstallment === 'equal' ? 'Równe' : 'Malejące' }}</span>
              </div>
              <div>
                <p class="mt-1">WIBOR:</p>
                <span class="text-xl font-semibold">{{ wiborName }}</span>
              </div>
              <div>
                <p class="mt-1">Prowizja:</p>
                <span class="text-xl font-semibold">{{
                    formData.commission
                  }} {{ formData.commissionType === 'percent' ? '%' : 'zł' }}</span>
              </div>
            </div>
          </div>
          <div class="bg-[#21a142] px-5 text-white pb-5 pt-3 rounded-b-md flex">
            <div class="flex-1">
              <p>Nadpłaty:</p>
              <ul class="list-disc ml-5">
                <li
                    class="text-xl font-semibold"
                    v-for="(overpayment, index) in overpayments"
                    :key="index"
                >{{ overpayment.start.month + 1 }}/{{ overpayment.start.year }} -
                  {{ overpayment.end.month + 1 }}/{{ overpayment.end.year }}:
                  {{ formattedToPLN.format(overpayment.overpayment) }}
                </li>
              </ul>
            </div>
            <div class="text-right">
              <p class="mt-1">Nadpłata na:</p>
              <span class="text-xl font-semibold">
                {{ overpaymentType === 'period' ? 'Skrócenie okresu kredytowania' : 'Zminiejszenie raty' }}
              </span>
            </div>
          </div>
        </Collapse>

        <Collapse class="relative" title="Jaki skutek przyniesie nadpłata kredytu?" :collapsed="true">
          <div v-if="overpaymentType === 'period'" class="flex justify-between gap-10">
            <div class="flex-col flex p-5">
              <label>Liczba rat</label>
              <span class="text-2xl font-semibold">{{ schedule.length }}</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Skrócenie okresu kredytowania o:</label>
              <span class="text-2xl font-semibold">{{ monthsLess }} miesięcy</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Zmniejszenie kosztów o:</label>
              <span class="text-2xl font-semibold">{{ costLessPercent.toFixed(2) }}%</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Oszczędność na całym kredycie</label>
              <span class="text-2xl font-semibold">{{ formattedToPLN.format(costCostDiff) }}</span>
            </div>
          </div>

          <div v-if="overpaymentType === 'installment'" class="flex justify-between gap-10">
            <div class="flex-col flex p-5">
              <label>Liczba rat</label>
              <span class="text-2xl font-semibold">{{ schedule.length }}</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Skrócenie okresu kredytowania o:</label>
              <span class="text-2xl font-semibold">{{ monthsLess }} miesięcy</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Zmniejszenie kosztów o:</label>
              <span class="text-2xl font-semibold">{{ costLessPercent.toFixed(2) }}%</span>
            </div>
            <div class="flex-col flex p-5">
              <label>Oszczędność na całym kredycie</label>
              <span class="text-2xl font-semibold">{{ formattedToPLN.format(costCostDiff) }}</span>
            </div>
          </div>
        </Collapse>

        <Collapse title="Wykres kosztu kredytu" :collapsed="true">
          <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
        </Collapse>

        <Collapse title="Harmonogram" :collapsed="true">
          <CreditScheduleOverpayment :schedule="schedule"/>
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
