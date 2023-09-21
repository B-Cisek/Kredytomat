<script setup>
import Layout from "@/Layouts/Layout.vue";
import ResultBox from "@/Components/ResultBox.vue";
import {LineChart} from "vue-chart-3";
import {useHelpers} from "@/Composables/useHelpers";
import {onBeforeMount, ref} from "vue";
import {Chart, registerables} from "chart.js";
import html2pdf from "html2pdf.js";
import CreditSchedule from "@/Components/CreditSchedule.vue";
import ChangesInterestsRatesTable from "@/Components/Tables/ChangesInterestsRatesTable.vue";
import {Inertia} from "@inertiajs/inertia";
import ConfirmationModal from "@/Components/Modals/ConfirmationModal.vue";
import InterestRateChange from "@/Components/InterestRateChange.vue";
import {useCreditCalculation} from "@/Composables/useCreditCalculation";

const {
  formattedToPLN,
  getTotalFixedFees,
  getTotalChangingFees,
  getInterestPartArray,
  getCapitalPartArray
} = useHelpers();

const props = defineProps({
  creditSimulation: Object
});

const interestPartArray = ref(null);
const capitalPartArray = ref(null);
const schedule = ref([]);
const scheduleDefault = ref([]);
const credit = ref({
  amountOfCredit: Number(props.creditSimulation.amount_of_credit),
  period: Number(props.creditSimulation.period),
  periodType: 'year',
  date: JSON.parse(props.creditSimulation.start_date),
  margin: Number(props.creditSimulation.margin),
  wibor: Number(props.creditSimulation.wibor.value),
  typeOfInstallment: props.creditSimulation.type_of_installment,
  commission: Number(props.creditSimulation.commission),
  commissionType: 'percent'
});

Chart.register(...registerables);

const {getSchedulev2} = useCreditCalculation();

const scheduleFetched = ref(false);

const calculation = async () => {
  const res = await getSchedulev2(
      credit.value.typeOfInstallment,
      credit.value.date,
      credit.value.amountOfCredit,
      credit.value.period,
      'year',
      credit.value.margin,
      credit.value.wibor,
      credit.value.commission,
      'percent',
      JSON.parse(props.creditSimulation.interest_changes),
      {
        fixed: JSON.parse(props.creditSimulation.fixed_fees),
        changing: JSON.parse(props.creditSimulation.changing_fees)
      }
  );

  schedule.value = await res.data.schedule;

  const resDefault = await getSchedulev2(
      credit.value.typeOfInstallment,
      credit.value.date,
      credit.value.amountOfCredit,
      credit.value.period,
      'year',
      credit.value.margin,
      credit.value.wibor,
      credit.value.commission,
      'percent',
      {},
      {
        fixed: JSON.parse(props.creditSimulation.fixed_fees),
        changing: JSON.parse(props.creditSimulation.changing_fees)
      }
  );

  scheduleDefault.value = await resDefault.data.schedule;

  scheduleFetched.value = true;
  interestPartArray.value = getInterestPartArray(schedule.value);
  capitalPartArray.value = getCapitalPartArray(schedule.value);

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
}


const loading = ref(false);

const downloadPdf = async () => {
  loading.value = true;

  const element = document.getElementById('pdf-export');

  const filename = `symulacja-kredytowa-${props.creditSimulation.id}.pdf`;

  const options = {
    filename: filename,
    margin: 0,
    jsPDF: {
      orientation: 'landscape',
    }
  };

  await html2pdf().from(element).set(options).save();
  loading.value = false;
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

let options = ref({
  elements: {
    point: {
      pointRadius: 0
    }
  },
  plugins: {
    title: {
      display: true,
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
});

onBeforeMount(() => {
  calculation();
});

const remove = () => {
  confirmationModalOpen.value = false;
  Inertia.delete(route('profil.credit.destroy', props.creditSimulation));
}

const confirmationModalOpen = ref(false);

const openCalculator = () => {
  Inertia.get(route('calculator.extended', {
    amount_of_credit: props.creditSimulation.amount_of_credit,
    period: props.creditSimulation.period,
    start_date: encodeURIComponent(props.creditSimulation.start_date),
    margin: props.creditSimulation.margin,
    commission: props.creditSimulation.commission,
    wibor: props.creditSimulation.wibor.value,
    type_of_installment: props.creditSimulation.type_of_installment,
    changing_fees: encodeURIComponent(props.creditSimulation.changing_fees),
    fixed_fees: encodeURIComponent(props.creditSimulation.fixed_fees),
    interest_changes: encodeURIComponent(props.creditSimulation.interest_changes)
  }));
}
</script>

<template>
  <Layout>
    <template v-slot:header>Symulacja kredytowa</template>
    <template v-slot:default>
      <ConfirmationModal
          v-if="confirmationModalOpen"
          @close="confirmationModalOpen = false"
          @submit="remove"
          title="Usuwanie symulacji"
          description="Czy napewno chcesz usunąć symulacje?"
          confirm-button-text="Usuń"
      />
      <div class="w-full rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div class="w-full flex items-center">
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
                  <span class="ml-2 w-0 flex-1 truncate">symulacja-kredytowa-{{ creditSimulation.id }}.pdf</span>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button
                      v-if="!loading"
                      @click="downloadPdf"
                      class="font-medium text-indigo-600 hover:text-indigo-500"
                  >Pobierz
                  </button>
                  <div
                      v-if="loading"
                      class="text-[#4338ca] inline-block h-5 w-5 animate-spin rounded-full border-4 border-solid border-current border-r-transparent align-[-0.125em] motion-reduce:animate-[spin_1.5s_linear_infinite]"
                      role="status">
                    <span
                        class="!absolute !-m-px !h-px !w-px !overflow-hidden !whitespace-nowrap !border-0 !p-0 ![clip:rect(0,0,0,0)]">Ładowanie...</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="flex gap-3 ml-3">
            <button @click="openCalculator" class="hover:opacity-75">
              <svg xmlns="http://www.w3.org/2000/svg" width="46px" height="46px">
                <path fill="#7cb342" d="M24 3A21 21 0 1 0 24 45A21 21 0 1 0 24 3Z"/>
                <path fill="#dcedc8"
                      d="M24,36.1c-6.6,0-12-5.4-12-12c0-3.6,1.6-7,4.4-9.3l2.5,3.1c-1.8,1.5-2.9,3.8-2.9,6.2c0,4.4,3.6,8,8,8 s8-3.6,8-8c0-2.1-0.8-4-2.2-5.5l2.9-2.7C34.8,18,36,21,36,24.1C36,30.7,30.6,36.1,24,36.1z"/>
                <path fill="#dcedc8" d="M12 13L21 13 21 22z"/>
              </svg>
            </button>
            <button @click="confirmationModalOpen = true">
              <img class="h-[46px] hover:opacity-75"
                   src="https://img.icons8.com/external-smashingstocks-circular-smashing-stocks/100/null/external-delete-webmobile-applications-smashingstocks-circular-smashing-stocks.png"
                   alt=""
              />
            </button>
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
                    <span class="text-xl font-semibold">{{
                        formattedToPLN.format(props.creditSimulation.amount_of_credit)
                      }}</span>
                  </div>
                  <div>
                    <p class="mt-1">Okres spłaty:</p>
                    <span class="text-xl font-semibold">{{ props.creditSimulation.period }} lat</span>
                  </div>
                  <div>
                    <p class="mt-1">Oprocentowanie:</p>
                    <p class="text-xl font-semibold">
                      {{
                        Math.round((Number(props.creditSimulation.margin) + Number(props.creditSimulation.wibor.value)) * 100) / 100
                      }}% <span
                        class="text-sm font-normal">(WIBOR {{
                        props.creditSimulation.wibor.value
                      }}% + marża {{ props.creditSimulation.margin }}%)</span>
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
                        props.creditSimulation.type_of_installment === 'equal' ? 'Równe' : 'Malejące'
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
              <div class="flex-1 html2pdf__page-break">
                <ResultBox
                    v-if="scheduleFetched"
                    :schedule="scheduleDefault"
                    :commission="credit.commission"
                    :amount-of-credit="credit.amountOfCredit"
                    :commission-type="credit.commissionType"
                    class="rounded-none"
                />
              </div>
            </div>
            <div class="p-2 mt-3 html2pdf__page-break">
              <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
            </div>
          </div>
          <div
              v-if="credit.typeOfInstallment === 'equal'"
              class="border-t-2 html2pdf__page-break">
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Roczny wzrost obciążeń z tytułu kredytu</h1>
            </div>
            <InterestRateChange v-if="scheduleFetched" :schedule="scheduleDefault" :credit="credit"/>
          </div>
          <div class="border-t-2 html2pdf__page-break">
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Symulacja zmiany raty dla zmian stóp procentowych</h1>
            </div>
            <ChangesInterestsRatesTable v-if="scheduleFetched" :schedule="scheduleDefault" :credit="credit"/>
          </div>
          <div class="html2pdf__page-break">
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Harmonogram spłaty</h1>
            </div>
            <CreditSchedule v-if="scheduleFetched" :schedule="schedule"/>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>
