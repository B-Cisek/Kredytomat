<script setup>
import Datepicker from '@vuepic/vue-datepicker';
import {XCircleIcon, PlusCircleIcon} from "@heroicons/vue/24/outline";
import '@vuepic/vue-datepicker/dist/main.css'
import {ref, watch} from "vue";

const props = defineProps({
  title: String,
  placeholder: String,
  data: String
});

const emit = defineEmits(["inputList"]);
const list = ref(JSON.parse(props.data) ?? []);
const date = new Date();

const add = () => {
  list.value.push({
    start: {month: date.getMonth(), year: date.getFullYear()},
    end: {month: date.getMonth(), year: date.getFullYear()},
    fee: null
  })
}

const remove = (id) => {
  list.value.splice(id, 1);
}

watch(list.value, () => {
  emit("inputList", list.value);
});

</script>

<template>
  <div class="flex justify-between items-center mb-5">
    <div class="flex">
      <span class="text-xl font-semibold mr-5">{{ props.title }}</span>
      <button @click="add">
        <PlusCircleIcon class="h-6 h-6 text-gray-500"/>
      </button>
    </div>
  </div>
  <div
    v-for="(input, index) in list"
    :key="index"
    class="flex gap-6 mt-3"
  >
    <Datepicker v-model="input.start" month-picker locale="pl" auto-apply/>
    <Datepicker v-model="input.end" month-picker locale="pl" auto-apply/>
    <input
      v-model="input.fee"
      class="border-gray-200 h-10 rounded-md"
      type="number"
      :placeholder="props.placeholder"
    >
    <button @click="remove(index)">
      <XCircleIcon class="h-6 w-6"/>
    </button>
  </div>
</template>
