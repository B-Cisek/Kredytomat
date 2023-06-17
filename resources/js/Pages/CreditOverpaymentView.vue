<script setup>
import Layout from "@/Layouts/Layout.vue";
import {computed, nextTick, onMounted, ref, watch} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import RangeWithInput from "@/Components/RangeWithInput.vue";
import {useEqualInstallments} from "@/Composables/useEqualInstallments"
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import {useHelpers} from "@/Composables/useHelpers";
import CreditScheduleOverpayment from "@/Components/Tables/CreditScheduleOverpayment.vue";
import OverpaymentInputsList from "@/Components/InputsList/OverpaymentInputsList.vue";
import Collapse from "@/Components/Collapse.vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import RangeWithInputSelect from "@/Components/RangeWithInputSelect.vue";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";

const {formatHarmonogram, totalCreditCost, totalCreditInterest, formattedToPLN, getInterestPartArray} = useHelpers();

const auth = computed(() => usePage().props.value.auth);

Chart.register(...registerables);

const props = defineProps({
  wiborList: Object,
});

const results = ref(null);

const scrollToResult = () => {
  results.value.scrollIntoView({behavior: "smooth"});
}

const overpayments = ref([]);
const overpaymentsStorage = ref(localStorage.getItem("overpayment-values"));
const overpaymentType = ref(localStorage.getItem("overpayment-type") ?? "period");
const schedule = ref([]);
const formattedSchedule = ref([]);
const baseCreditSchedule = ref([]);
const costCostDiff = ref(null);
const monthsLess = ref(0);
const costLessPercent = ref(0);

const formData = ref({
  amountOfCredit: Number(localStorage.getItem("overpayment-amountOfCredit") ?? 300000),
  period: Number(localStorage.getItem("overpayment-period") ?? 25),
  margin: Number(localStorage.getItem("overpayment-margin") ?? 2),
  commission: Number(localStorage.getItem("overpayment-commission") ?? 0),
  wibor: Number(localStorage.getItem("overpayment-wibor") ?? Number(props.wiborList[0].value)),
  typeOfInstallment: localStorage.getItem("overpayment-typeOfInstallment") ?? "rowne",
  commissionType: "percent"
});

watch(formData.value, (newValue, oldValue) => {
  localStorage.setItem("overpayment-amountOfCredit", newValue.amountOfCredit.toString());
  localStorage.setItem("overpayment-period", newValue.period.toString());
  localStorage.setItem("overpayment-margin", newValue.margin.toString());
  localStorage.setItem("overpayment-commission", newValue.commission.toString());
  localStorage.setItem("overpayment-wibor", newValue.wibor.toString());
  localStorage.setItem("overpayment-typeOfInstallment", newValue.typeOfInstallment.toString());
});

const rules = {
  amountOfCredit: {required},
  period: {required},
  margin: {required},
  commission: {required},
  wibor: {required},
  typeOfInstallment: {required}
}

const decreasingInstallment = () => {
  if (overpaymentType.value === "period") {
    baseCreditSchedule.value = useDecreasingInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, [], []).getScheduleShorterPeriod()

    return useDecreasingInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, overpayments.value ?? [], []).getScheduleShorterPeriod();
  } else {
    baseCreditSchedule.value = useDecreasingInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, [], []).getScheduleSmallerInstallment();

    return useDecreasingInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, overpayments.value ?? [], []).getScheduleSmallerInstallment();
  }
}

const equalInstallment = () => {
  if (overpaymentType.value === "period") {
    baseCreditSchedule.value = useEqualInstallmentsV2({
        date: new Date(2023, 0),
        ...formData.value
      }, []).getScheduleShorterPeriod();

    return useEqualInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, overpayments.value ?? [], []).getScheduleShorterPeriod();
  } else {
    baseCreditSchedule.value = useEqualInstallmentsV2({
      date: new Date(2023, 0),
     ...formData.value
    }, [], []).getScheduleSmallerInstallment();

    return useEqualInstallmentsV2({
      date: new Date(2023, 0),
      ...formData.value
    }, overpayments.value ?? []).getScheduleSmallerInstallment();
  }
}

const v$ = useVuelidate(rules, formData);

const totalCost = ref(0);

const getResult = async () => {
  const result = await v$.value.$validate();

  if (!result) {
    return;
  }

  let creditResult;

  if (formData.value.typeOfInstallment === "rowne") creditResult = equalInstallment();
  if (formData.value.typeOfInstallment === "malejace") creditResult = decreasingInstallment();


  schedule.value = creditResult;
  formattedSchedule.value = formatHarmonogram(creditResult);
  totalCost.value = totalCreditCost(creditResult);

  costCostDiff.value = totalCreditInterest(baseCreditSchedule.value) - totalCreditInterest(schedule.value);
  monthsLess.value = baseCreditSchedule.value.length - schedule.value.length;
  costLessPercent.value = 100 - ((totalCreditInterest(schedule.value) / totalCreditInterest(baseCreditSchedule.value)) * 100);


  let label = [];
  for (let i = 1; i <= schedule.value.length; i++) {
    label.push(i);
  }

  chartData.value.labels = label;
  chartData.value.datasets[0].data = getInterestPartArray(schedule.value);;
  chartData.value.datasets[1].data = getInterestPartArray(baseCreditSchedule.value);;

  await nextTick(() => scrollToResult());
}

const getOverpayments = (value) => {
  overpayments.value = value;
  localStorage.setItem("overpayment-values", JSON.stringify(overpayments.value));
}

const getType = (value) => {
  overpaymentType.value = value;
  localStorage.setItem("overpayment-type", value);
}

onMounted(() => overpayments.value = JSON.parse(overpaymentsStorage.value));

const saveSimulation = () => {
  Inertia.post(route('profil.overpayment.save'), {
    amount_of_credit: formData.value.amountOfCredit,
    period: formData.value.period,
    margin: formData.value.margin,
    commission: formData.value.commission,
    type_of_installment: formData.value.typeOfInstallment,
    wibor_id: formData.value.wibor,
    overpayment_type: overpaymentType.value,
    overpayments: JSON.stringify(overpayments.value),
  }, {preserveScroll: true});
}

const setCommissionType = value => {
  formData.value.commission = 0;
  formData.value.commissionType = value;
}

const setCommissionValue = value => {
  formData.value.commission = value;
}


const chartData = ref({
  labels: [],
  datasets: [
    {
      label: "Odsetki po nadpłacie",
      fill: true,
      data: [],
      backgroundColor: 'rgba(28, 176, 39, 0.2)',
      borderColor: '#1cb027'
    },
    {
      label: "Odsetki bez nadpłaty",
      fill: true,
      data: [],
      backgroundColor: 'rgba(223, 41, 53, 0.2)',
      borderColor: '#DF2935'
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

</script>

<template>
  <Layout>
    <template v-slot:header>
      Kalkulator nadpłaty kredytu
    </template>
    <template v-slot:default>
      <section class="w-full rounded-lg shadow-2xl border border-gray-200 bg-white p-5">
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
        <div class="lg:flex gap-x-16 mt-7">
          <div class="flex-1">
            <RangeWithInput
              v-model="formData.margin"
              input-type-label="%"
              heading="Marża"
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
        <div class="lg:flex gap-x-16 mt-7">
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">WIBOR</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                :class="{'border-red-700': v$.wibor.$errors.length !== 0}"
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
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                :class="{'border-red-700': v$.typeOfInstallment.$errors.length !== 0}"
                v-model="formData.typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="rowne">Równe</option>
                <option value="malejace">Malejące</option>
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
        <button @click="getResult" class="btn btn-primary mt-10 text-white w-full">
          Oblicz
        </button>
      </section>

      <section
        ref="results"
        v-if="formattedSchedule.length"
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
                <p class="text-xl font-semibold">{{ formData.wibor + formData.margin }}% <span
                  class="text-sm font-normal">(WIBOR {{ formData.wibor }}% + marża {{ formData.margin }}%)</span></p>
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
                <span class="text-xl font-semibold">3M</span>
              </div>
              <div>
                <p class="mt-1">Prowizja:</p>
                <span class="text-xl font-semibold">{{ formData.commission }}%</span>
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
          <div class="flex justify-center gap-10">
              <div class="flex-col flex p-5">
                <label>Oszczędność na całym kredycie</label>
                <span class="text-2xl font-semibold">{{ formattedToPLN.format(costCostDiff)}}</span>
              </div>
              <div class="flex-col flex p-5">
                <label>Skrócenie okresu kredytowania o:</label>
                <span class="text-2xl font-semibold">{{monthsLess}} miesięcy</span>
              </div>

              <div class="flex-col flex p-5">
                <label>Zmniejszenie kosztów o:</label>
                <span class="text-2xl font-semibold">{{ costLessPercent.toFixed(2) }}%</span>
              </div>
              <div class="flex-col flex p-5">
                <label>Roczna oszczędność</label>
                <span class="text-2xl font-semibold">---- PLN</span>
              </div>
            </div>
        </Collapse>

        <Collapse title="Wykres kosztu kredytu" :collapsed="true">
          <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
        </Collapse>

        <Collapse title="Harmonogram" :collapsed="true">
          <CreditScheduleOverpayment :schedule="formattedSchedule"/>
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
