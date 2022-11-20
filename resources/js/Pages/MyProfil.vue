<script setup>
import Layout from "@/Layouts/Layout.vue";
import { Link, usePage } from "@inertiajs/inertia-vue3";
import { computed } from "vue";

const auth = computed(() => usePage().props.value.auth.user);
</script>

<template>
  <Layout>
    <template #header>Profil</template>
    <template #default>
      <h1 class="text-2xl font-bold">{{ auth.name }}</h1>
      <h1 class="text-2xl font-bold">{{ auth.email }}</h1>
    </template>
  </Layout>
</template>
