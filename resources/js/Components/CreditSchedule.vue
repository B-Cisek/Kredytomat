<script setup>
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN} = useHelpers();

const props = defineProps({
  schedule: Object,
});

</script>

<template>
  <div class="overflow-x-auto relative">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr class="bg-gray-200 text-center">
        <th scope="col" class="py-3">Nr. raty</th>
        <th scope="col" class="py-3">Kapitał do spłaty</th>
        <th scope="col" class="py-3">Część kapitałowa</th>
        <th scope="col" class="py-3">Część odsetkowa</th>
        <th scope="col" class="py-3 px-6">Rata całkowita</th>
        <th scope="col" class="py-3 px-6">Oprocentowanie</th>
        <th scope="col" class="py-3 px-6">Opłaty</th>
        <th scope="col" class="py-3 px-6">Kapitał po spłacie</th>
      </tr>
      </thead>
      <tbody>
      <tr
        v-for="(sche, index) in props.schedule"
        :key="index"
        class="bg-white border-b hover:bg-gray-50 text-center"
      >
        <td class="font-medium text-gray-900 whitespace-nowrap p-3">
          {{ index + 1 }}
        </td>
        <td>{{ formattedToPLN.format(sche[2]) }}</td>
        <td>{{ formattedToPLN.format(sche[4]) }}</td>
        <td>{{ formattedToPLN.format(sche[3]) }}</td>
        <td>{{ formattedToPLN.format(sche[5] + sche[8] + sche[9]) }}</td>
        <td>{{ (sche[7] * 100).toFixed(2) }}%</td>
        <td :class="sche[8] != 0.00 || sche[9] !== 0.00 ? 'text-green-700 font-bold' : ''">
          {{ formattedToPLN.format(sche[8] + sche[9]) }}
        </td>
        <td>{{ formattedToPLN.format(sche[6]) }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
