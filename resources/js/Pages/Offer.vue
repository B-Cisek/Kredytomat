<script setup>
import Layout from "@/Layouts/Layout.vue";
import {RatyMalejaceOprocentowanieDzienne} from "@/Composables/RatyMalejaceOprocentowanieDzienne";
import TabsGroup from "@/Components/TabsGroup.vue";
import ResultBox from "@/Components/ResultBox.vue";

const kredyt = {
  kwotaKredytu: 300000,
  okres: 25,
  marza: 1,
  wibor: 7.93,
  prowizja: 0
}

console.table(RatyMalejaceOprocentowanieDzienne(kredyt).getHarmonogramRatyMalejace())





</script>

<template>
  <Layout>
    <template #header> Oferta</template>
    <template #default>
      <ResultBox :amount-of-installment="43242" :interest-installment="342432" :capital-installment="434324" :installments-left-to-pay="54354"/>
<!--      <TabsGroup />-->
    </template>
  </Layout>
</template>
