<script setup>

import {ref} from "vue";

const props = defineProps({
  tabs: {
    type: Array,
    default: [],
    required: true
  },
  active: {
    type: String,
    default: null
  }
});

const activeTab = ref(props.active || props.tabs[0].value);

</script>
<template>
  <div class="w-full rounded-lg shadow-2xl border border-gray-200 bg-white p-5">
    <div class="flex justify-around">
      <div v-for="(tab, index) in tabs"
           :key="index"
           class="cursor-pointer py-2 px-4"
           :class="tab.value === activeTab ? 'border-b-4 border-indigo-700' : ''"
           @click="activeTab = tab.value"
      >
        <span class="text-black font-semibold text-base">{{ tab.title }}</span>
      </div>
    </div>
    <div>
      <slot name="content" :active="activeTab"/>
    </div>
  </div>
</template>
