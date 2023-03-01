<script setup>
import { Bar } from 'vue-chartjs'
import {Chart, registerables} from "chart.js";
import {onMounted, ref} from "vue";
import {useEqualInstallments} from "@/Composables/useEqualInstallments";
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import {useHelpers} from "@/Composables/useHelpers";

Chart.register(...registerables);
const {formattedToPLN} = useHelpers();
const props = defineProps({
  credit: Object
});


const installment = ref([]);

const decrease = ref({
  one: Number,
  two: Number,
  three: Number,
  four: Number,
  five: Number,
});

const increase = ref([]);

const change = ref([]);
const result = ref(null);

const calculate = () => {
  if (props.credit.typeOfInstallment === "rowne") {
    result.value = useEqualInstallments({
      date: new Date(2023, 0), ...props.credit
    }).getSchedule()
  } else {
    result.value = useDecreasinginstallments({date: new Date(2023, 0), ...props.credit}).getScheduleShorterPeriod()
  }

  for (let i = 0; i < 5; i++) {
    let change = 0.5;
    increase[i] = useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: props.amountOfCredit,
      period: props.period,
      margin: props.margin,
      wibor: props.wibor - change,
      commission: props.commission
    }).getSchedule()
    change += 0.5;
  }
  console.table(increase.value);
}

onMounted(() => {
  calculate()
})



const labels = ['Spadek o 2,5%','Spadek o 2%','Spadek o 1,5%','Spadek o 1%','Spadek o 0,5%','obecnie',
                'Wzrost o 0,5%','Wzrost o 1%','Wzrost o 1,5%', 'Wzrost o 2%', 'Wzrost o 2,5%'];
const chartData = {
  labels: labels,
  datasets: [
    {
      label: 'Dataset 1',
      data: [20,30,40,50,50,30,70,75,55,100],
      backgroundColor: "#ff4069",
      stack: 'Stack 0',
    },
    {
      label: 'Dataset 2',
      data: [15,50,20,50,50,30,70,75,55,100],
      backgroundColor: "#22cfcf",
      stack: 'Stack 0',
    },
  ]
};

const chartOptions = {
  type: 'bar',
  data: chartData,
  options: {
    plugins: {
      title: {
        display: true,
        text: 'Chart.js Bar Chart - Stacked'
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
        stacked: true
      }
    }
  }
};


</script>

<template>
  <div class="flex flex-col px-5">
    <label class="font-semibold text-black" for="wibor">Porównaj:</label>
    <select
      class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none
           focus:shadow-none font-semibold input outline-none w-[260px]"
    >
      <option value="rowne">Miesięczną rate</option>
      <option value="malejace">Roczny wzrost obciążeń z tytułu kredytu</option>
    </select>
  </div>

  <div class="flex">
    <div class="flex-1 flex-col flex gap-5 justify-center">
      <div class="flex justify-end gap-20">
        <span></span>
        <span>zmiana</span>
        <span>wysokość raty</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oproc. kredytu na obecnym poziomie</span>
        <span>0,00 PLN</span>
<!--        <span>{{ formattedToPLN.format(result[0][4]) }}</span>-->
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
      <div class="flex justify-between gap-10">
        <span>Oprocentowanie spada o -2,5%</span>
        <span>- 337,06 PLN</span>
        <span>1 469,70 PLN</span>
      </div>
    </div>



    <div class="flex-1">
      <Bar
        :chartOptions="chartOptions"
        :chartData="chartData"
      />
    </div>
  </div>
</template>
