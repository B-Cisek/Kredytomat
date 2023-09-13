<script setup>
import Datepicker from '@vuepic/vue-datepicker';
import {XCircleIcon, PlusCircleIcon} from "@heroicons/vue/24/outline";
import '@vuepic/vue-datepicker/dist/main.css'
import {ref, watch} from "vue";

const props = defineProps({
  placeholder: String,
  data: String
});

const emit = defineEmits(["inputList", "type"]);

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
  emit("inputList", list.value);
});

watch(overpaymentType, () => {
  emit("type", overpaymentType.value);
});

const updateEndDate = (value, index) => {
  let end = list.value[index].end;

  if (end.year < value.year) {
    list.value[index].end = {
      year: value.year,
      month: value.month,
    }
  } else if (end.month < value.month) {
    list.value[index].end = {
      year: value.year,
      month: value.month,
    }
  }
}
</script>

<template>
  <div class="flex justify-between items-center mb-5">
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
  <div v-for="(input, index) in list" :key="index" class="flex gap-2 mt-3 lg:flex-row flex-col">
    <div class="flex gap-2">
      <Datepicker
          v-model="input.start"
          month-picker
          locale="pl"
          auto-apply
          @update:model-value="updateEndDate($event, index)"
      />
      <Datepicker
          v-model="input.end"
          month-picker
          locale="pl"
          auto-apply
          :min-date="new Date(input.start.year, input.start.month)"
      />
    </div>
    <div class="flex md:flex-row md:gap-2">
      <div>
        <input v-model="input.overpayment"
               class="border-gray-200 rounded-md h-[38px] w-[230px]"
               type="number"
               :placeholder="props.placeholder">
      </div>
      <div class="flex">
        <button @click="remove(index)">
          <XCircleIcon class="h-6 w-6"/>
        </button>
      </div>
    </div>
  </div>
</template>
