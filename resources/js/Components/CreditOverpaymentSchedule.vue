<script setup>
import {useHelpers} from "@/Composables/useHelpers";
import {onMounted, ref} from "vue";
import {useNadplataRatyStale} from "@/Composables/useNadplataRatyStale";
import {useNadplataRatyMalejace} from "@/Composables/useNadplataRatyMalejace";

const {formatHarmonogram, formattedToPLN} = useHelpers();
const overpayment = ref([]);

const kredyt = {
  kwotaKredytu: 300000,
  okres: 25,
  marza: 1.93,
  wibor: 7,
  prowizja: 0
}

const {
  getHarmonogramNadplataZmniejsenieWyskosciRaty,
  getHarmonogramSkrocenieOkresuKredytowania
} = useNadplataRatyMalejace(kredyt, overpayment.value);


const schedule = ref(getHarmonogramSkrocenieOkresuKredytowania())


// 12.01.2023
const test = () => {
  schedule.value = getHarmonogramSkrocenieOkresuKredytowania();

  console.table(useHelpers().formatHarmonogram(schedule.value))
}

</script>

<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <div class="p-7 bg-white flex justify-center">
      <span>Harmonogram spłaty kredytu</span>
    </div>
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr class="bg-gray-200 text-center">
        <th scope="col" class="py-3">Nr. raty</th>
        <th scope="col" class="py-3">Kapitał do spłaty</th>
        <th scope="col" class="py-3">Część kapitałowa</th>
        <th scope="col" class="py-3">Część odsetkowa</th>
        <th scope="col" class="py-3 px-6">Rata całkowita</th>
        <th scope="col" class="py-3 px-6">Nadpłata</th>
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
        <td>
          <input v-model="overpayment[index]" type="number">
          <button class="btn" @click="test">Oblicz</button>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>



