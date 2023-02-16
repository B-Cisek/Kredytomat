<script setup>
import Datepicker from '@vuepic/vue-datepicker';
import {XCircleIcon, PlusCircleIcon} from "@heroicons/vue/24/outline";
import '@vuepic/vue-datepicker/dist/main.css'
import {ref, watch} from "vue";

const props = defineProps({
  placeholder: String,
  data: String
});

const emit = defineEmits(['inputList']);

const list = ref(JSON.parse(props.data) ?? []);
const overpaymentType = ref("period");
const date = new Date();

const add = () => {
  list.value.push({
    start: {month: date.getMonth(), year: date.getFullYear()},
    end: {month: date.getMonth(), year: date.getFullYear()},
    overpayment: null
  })
}

const remove = (id) => {
  list.value.splice(id, 1);
}

watch(list.value, () => {
  emit('inputList', list.value, overpaymentType.value);
});
</script>

<template>
  <div class="flex justify-between items-center mb-10">
    <div class="flex">
      <span class="text-xl font-semibold mr-5">Nadpłata</span>
      <button @click="add">
        <PlusCircleIcon class="h-6 h-6 text-gray-500"/>
      </button>
    </div>
    <div>
      <div class="flex items-center mb-4">
        <input checked id="default-radio-1" type="radio" value="period" v-model="overpaymentType" name="default-radio"
               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
        <label for="default-radio-1" class="ml-2 text-sm font-medium text-gray-900">
          Nadpłata na skrócenie okresu kredytowania</label>
      </div>
      <div class="flex items-center">
        <input id="default-radio-2" type="radio" value="installment" v-model="overpaymentType" name="default-radio"
               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2">
        <label for="default-radio-2" class="ml-2 text-sm font-medium text-gray-900">
          Nadpłata na zminiejszenie raty</label>
      </div>
    </div>
  </div>
  <div v-for="(input, index) in list" :key="index" class="flex gap-6 mt-3">
    <Datepicker v-model="input.start" month-picker locale="pl" auto-apply/>
    <Datepicker v-model="input.end" month-picker locale="pl" auto-apply/>
    <input v-model="input.overpayment" class="border-gray-200 h-10 rounded-md" type="number"
           :placeholder="props.placeholder">
    <button @click="remove(index)">
      <XCircleIcon class="h-6 w-6"/>
    </button>
  </div>
</template>
