<script setup>
import {ArrowDownCircleIcon} from "@heroicons/vue/24/outline";
import {XCircleIcon} from "@heroicons/vue/24/outline";
import {ref} from "vue";

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

</script>

<template>
  <transition
    enter-to-class="duration-300"
  >
    <section class="w-full rounded-lg border border-gray-200 shadow-md bg-white">
      <div @click="collapsed = !collapsed" class="flex items-center justify-between cursor-pointer p-5">
        <h1 class="font-semibold text-xl">{{ props.title }}</h1>
        <ArrowDownCircleIcon v-show="!collapsed" class="h-10 h-10 text-gray-500"/>
        <XCircleIcon v-show="collapsed" class="h-10 h-10 text-gray-500"/>
      </div>
      <div v-show="collapsed">
        <slot/>
      </div>
    </section>
  </transition>
</template>
