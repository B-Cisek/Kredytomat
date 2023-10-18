<script setup>
import {Link} from "@inertiajs/inertia-vue3";
const props = defineProps(["href"]);
</script>

<template>
  <Link :href="href">
    <div class="bg-white rounded-lg border border-gray-200 hover:bg-gray-200 py-6 px-5 flex justify-center items-center gap-3">
      <h1 class="font-semibold text-xl">
        <slot name="title"/>
      </h1>
      <div>
        <slot name="icon"/>
      </div>
    </div>
  </Link>
</template>
