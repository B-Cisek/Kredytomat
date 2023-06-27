<script setup>
import InstallmentCalculator from "@/Components/InstallmentCalculator.vue";
import Layout from "@/Layouts/Layout.vue";
import {Head} from "@inertiajs/inertia-vue3";
</script>

<template>
  <Head title="Kalkulator raty kredytu"/>
  <Layout>
    <template v-slot:header>Kalkulator raty kredytu</template>

    <template v-slot:default>
      <InstallmentCalculator/>
    </template>
  </Layout>
</template>
