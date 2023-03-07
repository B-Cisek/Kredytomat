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

const baseInstallment = ref(null);

const calcBaseInstallment = () => {
  props.credit.date = new Date(2023,0);
  if (props.credit.typeOfInstallment === 'rowne') {
    let result = useEqualInstallments(props.credit).getSchedule();
    baseInstallment.value = result[0][4];
  }
}


const calculate = () => {

}

onMounted(() => {
  calcBaseInstallment();
})



const labels = ['Spadek o 2,5%','Spadek o 2%','Spadek o 1,5%','Spadek o 1%','Spadek o 0,5%','obecnie',
                'Wzrost o 0,5%','Wzrost o 1%','Wzrost o 1,5%', 'Wzrost o 2%', 'Wzrost o 2,5%'];
const chartData = {
  labels: labels,
  datasets: [
    {
      label: 'Obecna rata',
      data: [20,30,40,50,50,30,70,75,55,100],
      backgroundColor: "#ff4069",
      stack: 'Stack 0',
    },
    {
      label: 'Zmiana',
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

  <div class="flex flex-col">
    <div class="flex-col flex gap-5 justify-center px-5">
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



    <div>
      <Bar
        class="w-[500px]"
        :chartOptions="chartOptions"
        :chartData="chartData"
      />
    </div>
  </div>
</template>
