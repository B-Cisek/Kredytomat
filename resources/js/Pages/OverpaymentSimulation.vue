<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useHelpers} from "@/Composables/useHelpers";
import ConfirmationModal from "@/Components/Modals/ConfirmationModal.vue";
import {Inertia} from "@inertiajs/inertia";
import {onMounted, ref} from "vue";
import {useEqualInstallmentsV2} from "@/Composables/useEqualInstallmentsV2";
import {useDecreasingInstallmentsV2} from "@/Composables/useDecreasingInstallmentsV2";
import {LineChart} from "vue-chart-3";
import {Chart, registerables} from "chart.js";
import CreditScheduleOverpayment from "@/Components/Tables/CreditScheduleOverpayment.vue";
import html2pdf from "html2pdf.js";

const {formattedToPLN, totalCreditCost, totalCreditInterest, getInterestPartArray} = useHelpers();

Chart.register(...registerables);

const props = defineProps({
  overpaymentSimulation: Object
});

const baseCreditSchedule = ref([]);
const schedule = ref([]);
const totalCost = ref(0);
const costCostDiff = ref(0);
const monthsLess = ref(0);
const costLessPercent = ref(0);

const decreasingInstallment = () => {
  if (props.overpaymentSimulation.overpayment_type === "period") {
    schedule.value = useDecreasingInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      }, JSON.parse(props.overpaymentSimulation.overpayments),
      [],
      [],
      []).getScheduleShorterPeriod();

    baseCreditSchedule.value = useDecreasingInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      }, [],
      [],
      [],
      []).getScheduleShorterPeriod();
  }

  if (props.overpaymentSimulation.overpayment_type === "installment") {
    schedule.value = useDecreasingInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      }, JSON.parse(props.overpaymentSimulation.overpayments),
      [],
      [],
      []).getScheduleSmallerInstallment();

    baseCreditSchedule.value = useDecreasingInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      }, [],
      [],
      [],
      []).getScheduleSmallerInstallment();
  }
}

const equalInstallment = () => {
  if (props.overpaymentSimulation.overpayment_type === "period") {
    schedule.value = useEqualInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      },
      JSON.parse(props.overpaymentSimulation.overpayments),
      [],
      [],
      []
    ).getScheduleShorterPeriod();

    baseCreditSchedule.value = useEqualInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      },
      [],
      [],
      [],
      []
    ).getScheduleShorterPeriod();
  }

  if (props.overpaymentSimulation.overpayment_type === "installment") {
    schedule.value = useEqualInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      },
      JSON.parse(props.overpaymentSimulation.overpayments),
      [],
      [],
      []
    ).getScheduleSmallerInstallment();

    baseCreditSchedule.value = useEqualInstallmentsV2({
        date: new Date(2023, 0),
        amountOfCredit: Number(props.overpaymentSimulation.amount_of_credit),
        period: Number(props.overpaymentSimulation.period),
        margin: Number(props.overpaymentSimulation.margin),
        wibor: Number(props.overpaymentSimulation.wibor.value),
        commission: Number(props.overpaymentSimulation.commission)
      },
      [],
      [],
      [],
      []
    ).getScheduleSmallerInstallment();
  }
}


const calculation = () => {

  if (props.overpaymentSimulation.type_of_installment === "equal") equalInstallment();
  if (props.overpaymentSimulation.type_of_installment === "decreasing") decreasingInstallment();

  let label = [];
  for (let i = 1; i <= schedule.value.length; i++) {
    label.push(i);
  }

  chartData.value.labels = label;
  chartData.value.datasets[0].data = getInterestPartArray(schedule.value);
  chartData.value.datasets[1].data = getInterestPartArray(baseCreditSchedule.value);

  totalCost.value = totalCreditCost(schedule);

  costCostDiff.value = totalCreditInterest(baseCreditSchedule.value) - totalCreditInterest(schedule.value);
  monthsLess.value = baseCreditSchedule.value.length - schedule.value.length;
  costLessPercent.value = 100 - ((totalCreditInterest(schedule.value) / totalCreditInterest(baseCreditSchedule.value)) * 100);
}

const loading = ref(false);

const downloadPdf = async () => {
  loading.value = true;

  const element = document.getElementById('pdf-export');

  const filename = `symulacja-nadpłaty-kredytu-${props.overpaymentSimulation.id}.pdf`;

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
    intersect: false, 1
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
  calculation();
});

const remove = () => {
  confirmationModalOpen.value = false;
  Inertia.delete(route('profil.overpayment.destroy', props.overpaymentSimulation));
}

const confirmationModalOpen = ref(false);

const openCalculator = () => {
  Inertia.get(route('calculator.overpayment', {
    amount_of_credit: props.overpaymentSimulation.amount_of_credit,
    period: props.overpaymentSimulation.period,
    margin: props.overpaymentSimulation.margin,
    commission: props.overpaymentSimulation.commission,
    wibor: props.overpaymentSimulation.wibor.value,
    type_of_installment: props.overpaymentSimulation.type_of_installment,
    overpayments: encodeURIComponent(props.overpaymentSimulation.overpayments),
    overpayment_type: props.overpaymentSimulation.overpayment_type
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
        description="Czy napewno chcesz usunąć symulacje nadpłaty kredytu?"
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
                  <span class="ml-2 w-0 flex-1 truncate">symulacja-nadpłaty-kredytu-{{
                      overpaymentSimulation.id
                    }}.pdf</span>
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
                   alt=""/>
            </button>
          </div>
        </div>
      </div>
      <section id="pdf-export">
        <div class="w-full rounded-t-lg border border-gray-200 bg-white">
          <div class="flex items-center justify-between cursor-pointer p-5 w-full">
            <h1 class="font-semibold text-xl">Twoje wyniki</h1>
          </div>
        </div>
        <div>
          <div class="bg-[#21a142] px-5 pt-5 text-white flex">
            <div class="flex flex-col justify-between flex-1 gap-3">
              <div>
                <p class="mt-1">Kwota kredytu:</p>
                <span class="text-xl font-semibold">{{
                    formattedToPLN.format(overpaymentSimulation.amount_of_credit)
                  }}</span>
              </div>
              <div>
                <p class="mt-1">Okres spłaty:</p>
                <span class="text-xl font-semibold">{{ overpaymentSimulation.period }} lat</span>
              </div>
              <div>
                <p class="mt-1">Oprocentowanie:</p>
                <p class="text-xl font-semibold">
                  {{ Number(overpaymentSimulation.wibor.value) + Number(overpaymentSimulation.margin) }}% <span
                  class="text-sm font-normal">(WIBOR {{
                    overpaymentSimulation.wibor.value
                  }}% + marża {{ overpaymentSimulation.margin }}%)</span></p>
              </div>
            </div>
            <div class="flex flex-col justify-between items-end text-right flex-1">
              <div>
                <p class="mt-1">Rodzaj raty:</p>
                <span class="text-xl font-semibold">
                  {{ overpaymentSimulation.type_of_installment === 'equal' ? 'Równe' : 'Malejące' }}</span>
              </div>
              <div>
                <p class="mt-1">WIBOR:</p>
                <span class="text-xl font-semibold">3M</span>
              </div>
              <div>
                <p class="mt-1">Prowizja:</p>
                <span class="text-xl font-semibold">{{ overpaymentSimulation.commission }}%</span>
              </div>
            </div>
          </div>
          <div class="bg-[#21a142] px-5 text-white pb-5 pt-3 flex">
            <div class="flex-1">
              <p>Nadpłaty:</p>
              <ul class="list-disc ml-5">
                <li
                  class="text-xl font-semibold"
                  v-for="(overpayment, index) in JSON.parse(props.overpaymentSimulation.overpayments)"
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
                {{
                  overpaymentSimulation.overpayment_type === 'period' ? 'Skrócenie okresu kredytowania' : 'Zminiejszenie raty'
                }}
              </span>
            </div>
          </div>
        </div>
        <div>
          <div class="w-full border border-gray-200 bg-white">
            <div class="flex items-center justify-between cursor-pointer p-5 w-full">
              <h1 class="font-semibold text-xl">Jaki skutek przyniesie nadpłata kredytu?</h1>
            </div>
            <div>
              <div v-if="overpaymentSimulation.overpayment_type === 'period'" class="flex justify-between gap-10">
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

              <div v-if="overpaymentSimulation.overpayment_type === 'installment'" class="flex justify-between gap-10">
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
            </div>
            <div class="html2pdf__page-break">
              <div class="flex items-center justify-between cursor-pointer p-5 w-full border-t-2">
                <h1 class="font-semibold text-xl">Wykres kosztu kredytu</h1>
              </div>
              <LineChart class="h-[400px]" :chartData="chartData" :options="options"/>
            </div>
            <div class="html2pdf__page-break">
              <div class="flex items-center justify-between cursor-pointer p-5 w-full border-t-2">
                <h1 class="font-semibold text-xl">Harmonogram</h1>
              </div>
              <CreditScheduleOverpayment :schedule="schedule"/>
            </div>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>

