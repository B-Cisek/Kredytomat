<script setup>
import Layout from "@/Layouts/Layout.vue";
import CreditOverpaymentSchedule from "@/Components/CreditOverpaymentSchedule.vue";
import ResultBox from "@/Components/ResultBox.vue";


</script>

<template>
  <Layout>
    <template #header> Oferta</template>
    <template #default>
<!--      <CreditOverpaymentSchedule />-->
      <ResultBox
        :amount-of-installment="2027.74"
        :installments-left-to-pay="205"
        :capital-installment="1007.74"
        :interest-installment="200.00"
      />
    </template>
  </Layout>
</template>
