<script setup>
import Header from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import ToastList from "@/Components/ToastList.vue";
import {computed} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

defineProps({
  loggedIn: Boolean,
});

const auth = computed(() => usePage().props.value);
</script>

<template>
  <div class="min-h-screen flex-col flex bg-gray-100">
    <Header :loggedIn="loggedIn" />

    <header class="bg-gray-100 shadow">
      <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold tracking-tight text-gray-900">
          <slot name="header" />
        </h1>
      </div>
    </header>

    <main class="flex-1">
      <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
    <Footer />
  </div>
</template>
