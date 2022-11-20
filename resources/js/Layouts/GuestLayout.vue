<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
</script>

<template>
  <Header />
  <div
    class="h-[calc(100vh-80px-68px)] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
  >
    <div
      class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-2xl border border-gray-200 overflow-hidden sm:rounded-lg"
    >
      <slot />
    </div>
  </div>
  <Footer />
</template>
