<script setup>
import { defineProps } from "vue";
import { PieChart } from "vue-chart-3";
import {Chart, registerables} from "chart.js";

Chart.register(...registerables);

const props = defineProps({
  data: Object,
  options: Object
});

</script>

<template>
  <div class="w-[400px]">
    <PieChart :chartData="props.data" :options="props.options"/>
  </div>
</template>
