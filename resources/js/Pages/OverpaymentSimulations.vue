<script setup>
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
  overpaymentSimulation: Object
});

</script>

<template>
  <Layout>
    <template v-slot:header>Kalkulacje nadpłaty kredytu</template>
    <template v-slot:default>
      <div
        v-for="simulation in props.overpaymentSimulation"
        :key="simulation.id"
        class="w-full bg-gray-200 p-5">
        <h1></h1>
      </div>
    </template>
  </Layout>
</template>
