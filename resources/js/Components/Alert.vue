<script setup>
import { ref, onMounted } from "vue";

const props = defineProps({
  type: String,
});

let open = ref(true);

// close Alert component after 5 seconds
onMounted(() => {
  setTimeout(() => {
    open.value = false;
  }, 5000);
});

// Styles for different types of alert
const types = {
  info:
    "flex px-3 py-2 mr-3 text-blue-700 bg-blue-100 dark:bg-blue-200 dark:text-blue-800 rounded-lg",
  danger:
    "flex px-3 py-2 mr-3 text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800",
  success:
    "flex px-3 py-2 mr-3 text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800",
  warning:
    "flex px-3 py-2 mr-3 text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800",
};
</script>

<template>
  <div :class="types[props.type]" v-if="open" role="alert">
    <svg
      aria-hidden="true"
      class="flex-shrink-0 inline w-5 h-5 mr-3"
      fill="currentColor"
      viewBox="0 0 20 20"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        fill-rule="evenodd"
        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
        clip-rule="evenodd"
      ></path>
    </svg>
    <span class="sr-only">Info</span>
    <div>
      <span class="font-medium">
        <slot />
      </span>
    </div>
  </div>
</template>
