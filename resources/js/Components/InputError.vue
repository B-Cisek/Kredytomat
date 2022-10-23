<script setup>
defineProps(["message"]);
</script>

<template>
  <div v-show="message" class="">
    <p class="text-sm text-red-600">
      {{ message }}
    </p>
  </div>
</template>
