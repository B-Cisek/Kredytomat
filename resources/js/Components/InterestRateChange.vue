<script setup>
import {Bar} from 'vue-chartjs'
import {Chart, registerables} from "chart.js";
import {onMounted, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {useCreditCalculation} from "@/Composables/useCreditCalculation";

Chart.register(...registerables);

const {formattedToPLN, getTotalInstallmentsYear} = useHelpers();

const props = defineProps({
  credit: Object,
  schedule: Object
});

const result = ref([]);

const interestRateChanges = [
  -2.50, -2.00, -1.50, -1.00, -0.50, 0.50, 1.00, 1.50, 2.00, 2.50
];

const {loading, getSchedule, getSchedulev2} = useCreditCalculation();

const scheduleCalculate = async (index) => {
  if (props.credit.typeOfInstallment === "equal") {

    let wibor = Number(props.credit.wibor) + interestRateChanges[index];

    const defaultRes = await getSchedulev2(
        props.credit.typeOfInstallment,
        props.credit.date,
        props.credit.amountOfCredit,
        props.credit.period,
        'year',
        props.credit.margin,
        wibor,
        props.credit.commission,
        'percent'
    );

    return await defaultRes.data.schedule;
  }
}

const calculate = async () => {
  for (let i = 0; i < 10; i++) {
    let newSchedule = await scheduleCalculate(i);

    result.value.push({
      change: getTotalInstallmentsYear(props.schedule) - getTotalInstallmentsYear(newSchedule),
      sumInstallments: getTotalInstallmentsYear(newSchedule)
    });
  }
}

onMounted(async () => {
  await calculate();

  result.value.splice(5, 0, {
    change: 0,
    sumInstallments: getTotalInstallmentsYear(props.schedule)
  });

  let changesArr = [];
  let installmentArr = [];

  result.value.forEach((val) => {
    changesArr.push(Math.abs(val.change));
    installmentArr.push(val.sumInstallments - Math.abs(val.change));
  })

  chartData.value.datasets[0].data = installmentArr;
  chartData.value.datasets[1].data = changesArr;
});

const labels = ['Spadek o 2,5%', 'Spadek o 2%', 'Spadek o 1,5%', 'Spadek o 1%', 'Spadek o 0,5%', 'obecnie',
  'Wzrost o 0,5%', 'Wzrost o 1%', 'Wzrost o 1,5%', 'Wzrost o 2%', 'Wzrost o 2,5%'];

const chartData = ref({
  labels: labels,
  datasets: [
    {
      label: 'Suma rat (rok)',
      data: [],
      backgroundColor: "#acbac9",
      stack: 'Stack 0',
    },
    {
      label: 'Zmiana (rok)',
      data: [],
      backgroundColor: "#00b55a",
      stack: 'Stack 0',
    },
  ]
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  scales: {
    yAxes: {
      ticks: {
        beginAtZero: true
      }
    }
  }
};
</script>

<template>
  <div class="flex justify-between p-5">
    <div class=" flex-1 flex flex-col gap-3">
      <div><p class="font-semibold">ZMIANA OPROCENTOWANIA</p></div>
      <div><p>Oprocentowanie spada o <span class="font-semibold">-2,5%</span></p></div>
      <div><p>Oprocentowanie spada o <span class="font-semibold">-2,0%</span></p></div>
      <div><p>Oprocentowanie spada o <span class="font-semibold">-1,5%</span></p></div>
      <div><p>Oprocentowanie spada o <span class="font-semibold">-1,0%</span></p></div>
      <div><p>Oprocentowanie spada o <span class="font-semibold">-0,5%</span></p></div>
      <div><p>Oproc. kredytu na obecnym poziomie</p></div>
      <div><p>Oprocentowanie wzrasta o <span class="font-semibold">0,5%</span></p></div>
      <div><p>Oprocentowanie wzrasta o <span class="font-semibold">1,0%</span></p></div>
      <div><p>Oprocentowanie wzrasta o <span class="font-semibold">1,5%</span></p></div>
      <div><p>Oprocentowanie wzrasta o <span class="font-semibold">2,0%</span></p></div>
      <div><p>Oprocentowanie wzrasta o <span class="font-semibold"> 2,5%</span></p></div>
    </div>
    <div class=" flex-1 text-right flex flex-col gap-3">
      <div><p class="font-semibold">ROCZNA ZMIANA</p></div>
      <div
        :class="res.change >= 0 ? 'text-[#00b55a]' : 'text-red-800'"
        v-for="(res, index) in result"
        :key="index"
      >
        <p class="font-semibold">{{ formattedToPLN.format(-res.change) }}</p>
      </div>
    </div>
    <div class=" flex-1 text-right flex flex-col gap-3">
      <div><p class="font-semibold">SUMA RAT</p></div>
      <div
        :class="res.change >= 0 ? 'text-[#00b55a]' : 'text-red-800'"
        v-for="(res, index) in result"
        :key="index"
      >
        <p class="font-semibold">{{ formattedToPLN.format(res.sumInstallments) }}</p>
      </div>
    </div>
  </div>
  <div class="html2pdf__page-break"></div>
  <div>
    <Bar
      class="h-[400px]"
      :chartOptions="chartOptions"
      :chartData="chartData"
    />
  </div>
</template>
