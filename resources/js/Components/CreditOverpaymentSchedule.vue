<script setup>
import {useHelpers} from "@/Composables/useHelpers";
import {onMounted, ref, watch} from "vue";
import {useNadplataRatyStale} from "@/Composables/useNadplataRatyStale";
import {debounce} from "lodash";

const {formattedToPLN} = useHelpers();
const overpayment = ref([]);

const props = defineProps({
  credit: Object,
});

const schedule = ref(useNadplataRatyStale(props.credit, overpayment.value).getHarmonogramNadplataZmniejsenieWyskosciRaty())

watch(overpayment.value, debounce(() => {
  schedule.value = useNadplataRatyStale(props.credit, overpayment.value).getHarmonogramNadplataZmniejsenieWyskosciRaty();
}, 600))


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
          <input
            class="border-2 p-1 rounded-lg w-2/4"
            v-model="overpayment[index]"
            type="number">
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>



