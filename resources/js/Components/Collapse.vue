<script setup>
import {ArrowDownCircleIcon} from "@heroicons/vue/24/outline";
import {XCircleIcon} from "@heroicons/vue/24/outline";
import {onMounted, ref} from "vue";
import autoAnimate from "@formkit/auto-animate"

const props = defineProps({
  title: {
    type: String,
    default: 'Title',
    required: true
  },
  collapsed: {
    type: Boolean,
    default: false,
    required: true
  }
})

const collapsed = ref(props.collapsed);
const collapse = ref();

onMounted(() => {
  autoAnimate(collapse.value);
})
</script>

<template>
  <section ref="collapse" class="w-full rounded-lg border border-gray-200 shadow-md bg-white" id="collapse">
    <div @click="collapsed = !collapsed" class="flex items-center justify-between cursor-pointer p-5">
      <h1 class="font-semibold text-xl">{{ props.title }}</h1>
      <ArrowDownCircleIcon v-if="!collapsed" class="h-10 h-10 text-gray-500"/>
      <XCircleIcon v-show="collapsed" class="h-10 h-10 text-gray-500"/>
    </div>
    <div v-if="collapsed">
      <slot/>
    </div>
  </section>
</template>
