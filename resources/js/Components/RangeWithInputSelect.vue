<script setup>
import {ref, watch} from "vue";

const props = defineProps({
  heading: String,
});

const min = ref(0);
const max = ref(7);
const step = ref(0.1);
const selectedType = ref("percent");
const modelValue = ref(0);

watch(selectedType, value => {
  if (value === "percent") {
    min.value = 0;
    max.value = 7;
    step.value = 0.1;
    modelValue.value = 0;
  } else {
    min.value = 0;
    max.value = 10000;
    step.value = 1;
    modelValue.value = 0;
  }
});
</script>

<template>
  <div>
    <div className="flex mb-3 items-center justify-between">

      <h3 className="font-semibold text-black">{{ heading }}</h3>

      <div className="relative">
        <input
          v-model="modelValue"
          @change="$emit('update:modelValue', Number($event.target.value))"
          type="number"
          class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
        />
        <select v-model="selectedType"
                @change="$emit('selectedType', $event.target.value)"
                class="cursor-pointer absolute right-0 w-25 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white">
          <option selected value="number">PLN</option>
          <option value="percent">%</option>
        </select>
      </div>
    </div>

    <input
      type="range"
      v-model="modelValue"
      @change="$emit('update:modelValue', Number($event.target.value))"
      :min="min"
      :max="max"
      :step="step"
      class="range range-primary bg-[#d1d3d9]"
    />

    <label className="label">
      <span className="label-text-alt text-black">{{ min }} {{ selectedType == 'percent' ? '%' : 'zł' }}</span>
      <span className="label-text-alt text-black">{{ max }} {{ selectedType == 'percent' ? '%' : 'zł' }}</span>
    </label>
  </div>
</template>
