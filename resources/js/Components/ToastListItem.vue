<script setup>
import {onMounted, ref} from "vue";

const props = defineProps({
  message: String,
  type: String
});

const types = {
  danger: {
    'class': 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg',
    'borderColor': 'border-red-500'
  },
  success: {
    'class': 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-green-500 bg-green-100 rounded-lg',
    'borderColor': 'border-green-500'
  },
  warning: {
    'class': 'inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-orange-500 bg-orange-100 rounded-lg',
    'borderColor': 'border-orange-500'
  },
};

const emit = defineEmits(['remove']);
const currentStyles = ref(types.success);

onMounted(() => {
  switch (props.type) {
    case 'success':
      currentStyles.value = types.success;
      break;
    case 'warning':
      currentStyles.value = types.warning;
      break;
    case 'danger':
      currentStyles.value = types.danger;
  }

  setTimeout(() => emit('remove'), 6000);
});

</script>

<template>
  <div
      class="border border-1 flex items-center p-4 mb-4 text-gray-500 bg-white rounded-lg shadow"
      :class="[
      props.type === 'success' ? types.success.borderColor : '',
      props.type === 'danger' ? types.danger.borderColor : '',
      props.type === 'warning' ? types.warning.borderColor : '',
    ]"
      role="alert">
    <div :class="[
      props.type === 'success' ? types.success.class : '',
      props.type === 'danger' ? types.danger.class : '',
      props.type === 'warning' ? types.warning.class : '',
    ]">
      <svg
          v-if="props.type === 'success'"
          aria-hidden="true"
          class="w-5 h-5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clip-rule="evenodd"></path>
      </svg>

      <svg
          v-if="props.type === 'danger'"
          aria-hidden="true"
          class="w-5 h-5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
      </svg>

      <svg
          v-if="props.type === 'warning'"
          aria-hidden="true"
          class="w-5 h-5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
              clip-rule="evenodd"></path>
      </svg>
      <span class="sr-only">Check icon</span>
    </div>
    <div class="ml-3 text-sm font-normal">{{ props.message }}</div>
    <button
        @click="emit('remove')"
        type="button"
        class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex h-8 w-8"
        aria-label="Close">
      <span class="sr-only">Close</span>
      <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
           xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd"
              d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
              clip-rule="evenodd"></path>
      </svg>
    </button>
  </div>
</template>


