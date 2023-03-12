<script setup>
import Layout from "@/Layouts/Layout.vue";
import Tile from "@/Components/Tile.vue";

defineProps({
  creditSimulationCount: Number,
  overpaymentSimulationCount: Number
});
</script>

<template>
  <Layout>
    <template v-slot:header>
      Zapisane symulacje
    </template>
    <template v-slot:default>
      <section class="flex flex-col gap-3 mt-2">
        <Tile :href="route('profil.credit.index')">
          <template v-slot:title>Kalkulacje kredytu</template>
          <template v-slot:icon>
            <h1 class="font-semibold text-xl">({{ creditSimulationCount }})</h1>
          </template>
        </Tile>
        <Tile :href="route('profil.saved-simulations.overpayment')">
          <template v-slot:title>Kalkulacje nadpłaty kredytu</template>
          <template v-slot:icon>
            <h1 class="font-semibold text-xl">({{ overpaymentSimulationCount }})</h1>
          </template>
        </Tile>
      </section>
    </template>
  </Layout>
</template>
