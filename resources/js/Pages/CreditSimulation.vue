<script setup>
import Layout from "@/Layouts/Layout.vue";
import ResultBox from "@/Components/ResultBox.vue";
import {LineChart} from "vue-chart-3";
import {useHelpers} from "@/Composables/useHelpers";
import {useEqualInstallments} from "@/Composables/useEqualInstallments";
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import {onBeforeMount, onMounted, ref} from "vue";
import {Chart, registerables} from "chart.js";
import html2pdf from "html2pdf.js";
import CreditSchedule from "@/Components/CreditSchedule.vue";
import ChangesInterestsRatesTable from "@/Components/Tables/ChangesInterestsRatesTable.vue";

const {formattedToPLN, getTotalFixedFees, getTotalChangingFees, getInterestPartArray, getCapitalPartArray} = useHelpers();

const props = defineProps({
  creditSimulation: Object
});

const interestPartArray = ref(null);
const capitalPartArray = ref(null);
const schedule = ref([]);
const credit = ref({
  amountOfCredit: Number(props.creditSimulation.amount_of_credit),
  period: Number(props.creditSimulation.period),
  margin: Number(props.creditSimulation.margin),
  wibor: Number(props.creditSimulation.wibor.value),
  typeOfInstallment: props.creditSimulation.type_of_installment,
  commission: 0
});
Chart.register(...registerables);

const calculation = () => {
  if (props.creditSimulation.type_of_installment === "rowne") {
    schedule.value = useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: Number(props.creditSimulation.amount_of_credit),
      period: Number(props.creditSimulation.period),
      margin: Number(props.creditSimulation.margin),
      wibor: Number(props.creditSimulation.wibor.value),
      commission: 0
    },
      [],
      [],
      JSON.parse(props.creditSimulation.fixed_fees),
      JSON.parse(props.creditSimulation.changing_fees)
    ).getSchedule();
  } else {
    schedule.value = useDecreasinginstallments({
      date: new Date(2023, 0),
      amountOfCredit: props.creditSimulation,
      period: props.creditSimulation,
      margin: props.creditSimulation,
      wibor: props.creditSimulation,
      commission: props.creditSimulations
    }, [], []).getScheduleSmallerInstallment();
  }

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
}

const downloadPdf = () => {
  const element = document.getElementById('pdf-export');
  const options = {
    filename: "test.pdf",

  };

  html2pdf(document.getElementById('pdf-export'), {
    filename: "test.pdf",
    margin: 0,
    jsPDF: {
      orientation: 'landscape',
    }

  });
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

onBeforeMount(() => {
  console.log(props.creditSimulation)
  calculation();
})

</script>

<template>
  <Layout>
    <template v-slot:header>Symulacja kredytowa</template>
    <template v-slot:default>
      <div class="w-full rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div class="w-full flex items-center">
          <div>
          </div>
          <div class="text-sm text-gray-900 flex-1">
            <div class="divide-y divide-gray-200 rounded-md border border-gray-200">
              <div class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                <div class="flex w-0 flex-1 items-center">
                  <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                       aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                          clip-rule="evenodd"/>
                  </svg>
                  <span class="ml-2 w-0 flex-1 truncate">resume_back_end_developer.pdf</span>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="downloadPdf" class="font-medium text-indigo-600 hover:text-indigo-500">Pobierz
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <section id="pdf-export">
        <div class="w-full rounded-lg border border-gray-200 bg-white">
          <div class="flex items-center justify-between cursor-pointer p-5 w-full">
            <h1 class="font-semibold text-xl">Twoje wyniki</h1>
          </div>
          <div>
            <div>
              <div class="flex-1 bg-[#21a142] p-5 text-white flex">
                <div class="flex flex-col justify-between flex-1">
                  <div>
                    <p class="mt-1">Kwota kredytu:</p>
                    <span class="text-xl font-semibold">{{ formattedToPLN.format(props.creditSimulation.amount_of_credit) }}</span>
                  </div>
                  <div>
                    <p class="mt-1">Okres spłaty:</p>
                    <span class="text-xl font-semibold">{{ props.creditSimulation.period }} lat</span>
                  </div>
                  <div>
                    <p class="mt-1">Oprocentowanie:</p>
                    <p class="text-xl font-semibold">{{ Number(props.creditSimulation.margin) + Number(props.creditSimulation.wibor.value) }}% <span
                      class="text-sm font-normal">(WIBOR {{ props.creditSimulation.wibor.value }}% + marża {{ props.creditSimulation.margin }}%)</span>
                    </p>
                  </div>
                  <div>
                    <p class="mt-1">Prowizja:</p>
                    <span class="text-xl font-semibold">{{ props.creditSimulation.commission }}%</span>
                  </div>
                </div>
                <div class="flex flex-col justify-between items-end text-right flex-1">
                  <div>
                    <p class="mt-1">Rodzaj raty:</p>
                    <span class="text-xl font-semibold">{{
                        props.creditSimulation.type_of_installment === 'rowne' ? 'Równe' : 'Malejące'
                      }}</span>
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
                    <span class="text-xl font-semibold">{{
                        formattedToPLN.format(getTotalChangingFees(schedule))
                      }}</span>
                  </div>
                </div>
              </div>
              <div class="flex-1">
                <ResultBox :schedule="schedule" class="rounded-none"/>
              </div>
            </div>
            <div class="p-2 mt-3">
              <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
            </div>
          </div>
          <div>
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Symulacja zmiany raty dla zmian stóp procentowych</h1>
            </div>
            <ChangesInterestsRatesTable :schedule="schedule" :credit="credit"/>
          </div>
          <div>
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Harmonogram spłaty</h1>
            </div>
            <CreditSchedule :schedule="schedule"/>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>
