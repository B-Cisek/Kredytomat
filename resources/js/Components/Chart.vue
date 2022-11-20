<script setup>
import { defineProps } from "vue";
import { PieChart } from "vue-chart-3";
import { Chart, registerables } from "chart.js";

Chart.register(...registerables);

const props = defineProps({
  data: Object,
});

const testData = {
  labels: ["Kwota kredytu", "Odsetki", "Prowizja banku"],
  datasets: [
    {
      data: [30, 40, 60],
      backgroundColor: ["#0045db", "#ff2e66", "#ffb947"],
    },
  ],
};
</script>

<template>
  <div class="w-[300px]">
    <PieChart :chartData="props.data" />
  </div>
</template>
