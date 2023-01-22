<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useNadplataRatyMalejace} from "@/Composables/useNadplataRatyMalejace";
import {useHelpers} from "@/Composables/useHelpers";
import CreditOverpaymentSchedule from "@/Components/CreditOverpaymentSchedule.vue";

</script>

<template>
  <Layout>
    <template #header> O Kredycie </template>
    <template #default>
      <input type="number">
      <section class="w-full">
        <div class="mb-5">
          <h2 class="font-bold text-xl">Marża</h2>
          <p>
            Cena po jakiej bank pożycza pieniądze. Im jest wyższa tym więcej musisz
            zapłacić. Nie zmienia się w trakcie trwania kredytu
          </p>
        </div>
        <div class="mb-5">
          <h2 class="font-bold text-xl">Marża</h2>
          <p>
            Cena po jakiej bank pożycza pieniądze. Im jest wyższa tym więcej musisz
            zapłacić. Nie zmienia się w trakcie trwania kredytu
          </p>
        </div>
        <div class="mb-5">
          <h2 class="font-bold text-xl">Marża</h2>
          <p>
            Cena po jakiej bank pożycza pieniądze. Im jest wyższa tym więcej musisz
            zapłacić. Nie zmienia się w trakcie trwania kredytu
          </p>
        </div>
      </section>
      <CreditOverpaymentSchedule />
    </template>
  </Layout>
</template>
