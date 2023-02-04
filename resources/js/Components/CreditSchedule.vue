<script setup>
import {ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";
import {useRatyStaleExtended} from "@/Composables/useRatyStaleExtended";


const kredyt = {
  kwotaKredytu: 300000,
  okres: 25,
  marza: 1.5,
  wibor: 7.7,
  prowizja: 0
}

const harmonogram = useRatyStaleExtended(kredyt).getHarmonogram();


const {formattedToPLN} = useHelpers();

const props = defineProps({
  schedule: Object,
});

</script>

<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
<!--    <div class="p-7 bg-white flex justify-center">-->
<!--        <span>Harmonogram spłaty kredytu</span>-->
<!--    </div>-->
    <table class="w-full text-sm text-left text-gray-500">
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
        <td>{{ formattedToPLN.format(sche[1]) }}</td>
        <td>{{ formattedToPLN.format(sche[3]) }}</td>
        <td>{{ formattedToPLN.format(sche[2]) }}</td>
        <td>{{ formattedToPLN.format(sche[4]) }}</td>
        <td>{{ formattedToPLN.format(sche[5]) }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
