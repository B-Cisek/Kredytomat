<script setup>
import {ref} from "vue";

const props = defineProps({
  type: String,
  modelValue: {
    type: Number,
    required: true
  },
  error: Boolean
});

const emit = defineEmits(['update:type', 'update:modelValue'])

const min = ref(5);
const max = ref(35);
const step = ref(1);
const labelLeft = ref('lat');
const labelRight = ref('lat');

const changeType = (val) => {
  if (val === 'month') {
    min.value = 12 * 5;
    max.value = 12 * 35;
    labelLeft.value = 'miesięcy';
    labelRight.value = 'miesięcy';
    emit('update:modelValue', 60)
  } else {
    min.value = 5;
    max.value = 35;
    labelLeft.value = 'lat';
    labelRight.value = 'lat';
    emit('update:modelValue', 5)
  }

  emit('update:type', val)
}
</script>

<template>
  <div>
    <div class="flex mb-3 items-center justify-between">
      <h3 class="font-semibold text-black">Okres</h3>

      <div class="relative">
        <input
          :value="modelValue"
          @input="$emit('update:modelValue', Number($event.target.value))"
          type="number"
          class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
          :class="error ? 'border-red-700' : ''"
        />

        <select
          :value="type"
          @input="changeType($event.target.value)"
          class="appearance-none cursor-pointer absolute right-0 w-25 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
          :class="error ? 'bg-red-700' : ''"
        >
          <option selected value="year">LAT</option>
          <option value="month">MSC</option>
        </select>
      </div>
    </div>

    <input
      v-model="modelValue"
      @input="$emit('update:modelValue', Number($event.target.value))"
      type="range"
      :min="min"
      :max="max"
      :step="step"
      class="range range-primary bg-[#d1d3d9]"
    />

    <label class="label">
      <span class="label-text-alt text-black">{{ min }} {{ labelLeft }}</span>
      <span class="label-text-alt text-black">{{ max }} {{ labelRight }}</span>
    </label>
  </div>
</template>
