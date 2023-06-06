<script setup>

import {ref, watch} from "vue";

const props = defineProps({
  inputTypeLabel: String,
  heading: String,
  labelLeft: String,
  labelRight: String,
  modelValue: Number
});

const min = ref(0);
const max = ref(7);
const step = ref(0.1);
const selectedType = ref("procent");


watch(selectedType, value => {
  if (value === "procent") {
    min.value = 0;
    max.value = 0;
    step.value = 0.1;
  } else {
    min.value = 0;
    max.value = 10000;
    step.value = 1;
  }
});

</script>

<template>
  <div className="flex mb-3 items-center justify-between">

    <h3 className="font-semibold text-black">{{ heading }}</h3>

    <div className="relative">
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', Number($event.target.value))"
        type="number"
        class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
      />
      <select v-model="selectedType" class="cursor-pointer absolute right-0 w-25 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white">
        <option selected value="number">PLN</option>
        <option value="procent">%</option>
      </select>
    </div>
  </div>

  <input
    type="range"
    :value="modelValue"
    @input="$emit('update:modelValue', Number($event.target.value))"
    :min="min"
    :max="max"
    :step="step"
    class="range range-primary bg-[#d1d3d9]"
  />

  <label className="label">
    <span className="label-text-alt text-black">{{ labelLeft }}</span>
    <span className="label-text-alt text-black">{{ labelRight }}</span>
  </label>
</template>
