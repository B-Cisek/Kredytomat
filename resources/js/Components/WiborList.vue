<script setup>
import Datepicker from '@vuepic/vue-datepicker';
import {XCircleIcon} from "@heroicons/vue/24/outline";
import '@vuepic/vue-datepicker/dist/main.css'
import {ref, watch} from "vue";

const props = defineProps({
  buttonText: String,
  placeholder: String
});

const emit = defineEmits(['inputList']);

const list = ref([]);

const date = new Date();

const add = () => {
  list.value.push({
    start: {month: date.getMonth(), year: date.getFullYear()},
    wibor: null
  })
}

const remove = (id) => {
  list.value.splice(id, 1);
}

watch(list.value, () => {
  emit('inputList', list.value);
});
</script>

<template>
  <button @click="add" class="btn bg-gray-700">{{ props.buttonText }}</button>
  <div v-for="(input, index) in list" :key="index" class="flex gap-6 mt-3">
    <Datepicker v-model="input.start" month-picker locale="pl" auto-apply/>
    <input v-model="input.wibor" class="border-gray-200 h-10 rounded-md" type="number" :placeholder="props.placeholder">
    <button @click="remove(index)">
      <XCircleIcon class="h-6 w-6"/>
    </button>
  </div>
</template>
