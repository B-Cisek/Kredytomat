<script setup>

const props = defineProps({
  inputTypeLabel: String,
  heading: String,
  min: Number,
  max: Number,
  step: Number,
  labelLeft: String,
  labelRight: String,
  modelValue: Number
});

</script>

<template>
  <div class="flex mb-3 items-center justify-between">

    <h3 class="font-semibold text-black">{{ heading }}</h3>

    <div class="relative">
      <input
        :value="modelValue"
        @input="$emit('update:modelValue', Number($event.target.value))"
        type="number"
        class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
      />

      <span
        class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
      >{{ inputTypeLabel }}</span>
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

  <label class="label">
    <span class="label-text-alt text-black">{{ labelLeft }}</span>
    <span class="label-text-alt text-black">{{ labelRight }}</span>
  </label>
</template>
