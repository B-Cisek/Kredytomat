<script setup>
import { ref, onMounted } from "vue";

defineProps({
  spanText: String,
  modelValue: Number,
});

//const formatPLN = new Intl.NumberFormat("pl-PL");

defineEmits(["update:modelValue"]);
</script>
<template>
  <div class="relative">
    <input
      type="number"
      class="input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
    />
    <span
      class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
      >{{ spanText }}</span
    >
  </div>
</template>
