<script setup>
import { useHarmonogram } from "@/Composables/useHarmonogram";
import { useHelpers } from "@/Composables/useHelpers";
import { ref } from "vue";

const props = defineProps({
  schedule: Object,
});
const scheduleOn = ref(false);
const { harmonogram, kosztKredytu } = useHarmonogram();
const { formatHarmonogram } = useHelpers();
const schedule = formatHarmonogram(harmonogram(250000, 25, 8.93));

const formattedToPLN = new Intl.NumberFormat("pl-PL", {
  style: "currency",
  currency: "PLN",
  maximumFractionDigits: 2,
});
</script>
<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <div class="p-7 bg-white flex justify-center">
      <button
        @click="scheduleOn = !scheduleOn"
        class="text-center font-semibold text-xl flex justify-center indicator-center cursor-pointer"
      >
        Harmonogram spłaty kredytu
        <span class="ml-3">
          <svg
            v-show="!scheduleOn"
            class="w-7 h-7"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M15.707 4.293a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 011.414-1.414L10 8.586l4.293-4.293a1 1 0 011.414 0zm0 6a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-5-5a1 1 0 111.414-1.414L10 14.586l4.293-4.293a1 1 0 011.414 0z"
              clip-rule="evenodd"
            ></path>
          </svg>
          <svg
            v-show="scheduleOn"
            class="w-7 h-7"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              fill-rule="evenodd"
              d="M4.293 15.707a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414 0zm0-6a1 1 0 010-1.414l5-5a1 1 0 011.414 0l5 5a1 1 0 01-1.414 1.414L10 5.414 5.707 9.707a1 1 0 01-1.414 0z"
              clip-rule="evenodd"
            ></path>
          </svg>
        </span>
      </button>
    </div>
    <table v-show="scheduleOn" class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr class="bg-gray-200 text-center">
          <th scope="col" class="py-3">Nr. raty</th>
          <th scope="col" class="py-3">Kapitał do spłaty</th>
          <th scope="col" class="py-3">Część kapitałowa</th>
          <th scope="col" class="py-3">Część odsetkowa</th>
          <th scope="col" class="py-3 px-6">Rata całkowita</th>
          <th scope="col" class="py-3 px-6">Kapitał po spłacie</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="(sche, index) in schedule"
          :key="index"
          class="bg-white border-b hover:bg-gray-50 text-center"
        >
          <td class="font-medium text-gray-900 whitespace-nowrap p-3">
            {{ index + 1 }}
          </td>
          <td>{{ formattedToPLN.format(sche[0]) }}</td>
          <td>{{ formattedToPLN.format(sche[2]) }}</td>
          <td>{{ formattedToPLN.format(sche[1]) }}</td>
          <td>{{ formattedToPLN.format(sche[3]) }}</td>
          <td>{{ formattedToPLN.format(sche[4]) }}</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
