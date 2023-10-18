<script setup>
import {ref, watch} from "vue";

const props = defineProps({
  type: String,
  modelValue: {
    type: Number,
    required: true
  },
  error: Boolean
});

const emit = defineEmits(['update:type', 'update:modelValue'])

const min = ref(0);
const max = ref(7);
const step = ref(0.01);
const labelLeft = ref('%');
const labelRight = ref('%');

const changeType = (val) => {
  if (val === 'number') {
    min.value = 0;
    max.value = 10000;
    step.value = 100;
    labelLeft.value = 'zł';
    labelRight.value = 'zł';
    emit('update:modelValue', 0)
  } else {
    min.value = 0;
    max.value = 7;
    step.value = 0.01;
    labelLeft.value = '%';
    labelRight.value = '%';
    emit('update:modelValue', 0)
  }

  emit('update:type', val)
}

watch(props, (value) => {
  if (value.type === 'number') {
    min.value = 0;
    max.value = 10000;
    step.value = 100;
    labelLeft.value = 'zł';
    labelRight.value = 'zł';
  } else {
    min.value = 0;
    max.value = 7;
    step.value = 0.01;
    labelLeft.value = '%';
    labelRight.value = '%';
  }
})

</script>

<template>
  <div>
    <div class="flex mb-3 items-center justify-between">
      <h3 class="font-semibold text-black">Prowizja</h3>

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
          <option selected value="percent">%</option>
          <option value="number">PLN</option>
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
