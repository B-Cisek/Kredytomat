<script setup>
import { ref, onMounted } from "vue";
const input = ref(null);

defineProps({
  spanText: String,
});

console.log(input);
</script>
<template>
  <div class="relative">
    <input
      type="number"
      placeholder="300 000"
      class="input w-full border-gray-300 border-2 outline-none focus:outline-none focus:border-indigo-700 focus:shadow-none"
      ref="input"
      @click="input.value = ''"
    />
    <span
      class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
      >{{ spanText }}</span
    >
  </div>
</template>
