<script setup>
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN} = useHelpers();

const props = defineProps({
  schedule: Object,
});

</script>

<template>
  <div class="overflow-x-auto relative shadow-md">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr class="bg-gray-200 text-center">
        <th scope="col" class="py-3">Nr. raty</th>
        <th scope="col" class="py-3">Kapitał do spłaty</th>
        <th scope="col" class="py-3">Część kapitałowa</th>
        <th scope="col" class="py-3">Część odsetkowa</th>
        <th scope="col" class="py-3 px-6">Rata całkowita</th>
        <th scope="col" class="py-3 px-6">Kapitał po spłacie</th>
        <th scope="col" class="py-3 px-6">Nadpłata</th>
      </tr>
      </thead>
      <tbody>
      <tr
          v-for="(schedule, index) in props.schedule"
          :key="index"
          class="bg-white border-b hover:bg-gray-50 text-center"
      >
        <td class="font-medium text-gray-900 whitespace-nowrap p-3">
          {{ index + 1 }}
        </td>
        <td>{{ formattedToPLN.format(schedule[2]) }}</td>
        <td>{{ formattedToPLN.format(schedule[4]) }}</td>
        <td>{{ formattedToPLN.format(schedule[3]) }}</td>
        <td>{{ formattedToPLN.format(schedule[5]) }}</td>
        <td>{{ formattedToPLN.format(schedule[6]) }}</td>
        <td :class="schedule[10] != 0.00 ? 'text-green-700 font-bold' : ''">
          {{ formattedToPLN.format(schedule[10]) }}
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
