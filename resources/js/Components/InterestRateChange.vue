<script setup>
import {Bar} from 'vue-chartjs'
import {Chart, registerables} from "chart.js";
import {onMounted, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {useEqualInstallments} from "@/Composables/useEqualInstallments";

Chart.register(...registerables);

const {formattedToPLN} = useHelpers();

const props = defineProps({
  credit: Object,
  schedule: Object
});

const result = ref([]);

const interestRateChanges = [
  -2.50, -2.00, -1.50, -1.00, -0.50, 0.50, 1.00, 1.50, 2.00, 2.50
];

const scheduleCalculate = (index) => {
  if (props.credit.typeOfInstallment === "rowne") {
    return useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: props.credit.amountOfCredit,
      period: props.credit.period,
      margin: props.credit.margin,
      wibor: props.credit.wibor + interestRateChanges[index],
      commission: props.credit.commission
    }).getSchedule();
  }
}

const calculate = () => {
  for (let i = 0; i < 10; i++) {
    let newSchedule = scheduleCalculate(i);

    result.value.push({
      change: props.schedule[0][4] - newSchedule[0][4],
      installment: newSchedule[0][4]
    });
  }
}

onMounted(() => {
  calculate();

  result.value.splice(5, 0, {
    change: 0,
    installment: props.schedule[0][4]
  });

  let changesArr = [];
  let installmentArr = [];

  result.value.forEach((val) => {
    changesArr.push(val.change > 0 ? val.change : -val.change);
    installmentArr.push(val.installment);
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
      label: 'Obecna rata',
      data: [],
      backgroundColor: "#acbac9",
      stack: 'Stack 0',
    },
    {
      label: 'Zmiana',
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
    <div class="flex-col flex gap-5 justify-center px-5">
      <span></span>
      <p>Oprocentowanie spada o <span class="font-semibold">-2,5%</span></p>
      <p>Oprocentowanie spada o <span class="font-semibold">-2,0%</span></p>
      <p>Oprocentowanie spada o <span class="font-semibold">-1,5%</span></p>
      <p>Oprocentowanie spada o <span class="font-semibold">-1,0%</span></p>
      <p>Oprocentowanie spada o <span class="font-semibold">-0,5%</span></p>
      <p>Oproc. kredytu na obecnym poziomie</p>
      <p>Oprocentowanie wzrasta o <span class="font-semibold">0,5%</span></p>
      <p>Oprocentowanie wzrasta o <span class="font-semibold">1,0%</span></p>
      <p>Oprocentowanie wzrasta o <span class="font-semibold">1,5%</span></p>
      <p>Oprocentowanie wzrasta o <span class="font-semibold">2,0%</span></p>
      <p>Oprocentowanie wzrasta o <span class="font-semibold"> 2,5%</span></p>
    </div>
    <div class="flex-col flex gap-5 justify-center px-5 text-center">
      <span>ROCZNA ZMIANA</span>
      <span
        v-for="(res, index) in result"
        :key="index"
      >{{ formattedToPLN.format(-res.change)}}</span>
    </div>
    <div class="flex-col flex gap-5 justify-center px-5 text-right">
      <span>WYSOKOŚĆ RATY</span>
      <span
        v-for="(res, index) in result"
        :key="index"
      >{{formattedToPLN.format(res.installment)}}</span>
    </div>
  </div>
  <div>
    <Bar
      class="w-full p-5"
      :chartOptions="chartOptions"
      :chartData="chartData"
    />
  </div>
</template>
