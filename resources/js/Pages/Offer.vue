<script setup>
import { Head } from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import {CreditCalculation} from "@/Composables/CreditCalculation";
import {useHelpers} from "@/Composables/useHelpers";
import OverpaymentInputsList from "@/Components/OverpaymentInputsList.vue";
import OverpaymentInput from "@/Components/OverpaymentInput.vue";
import OverpaymentInputs from "@/Components/OverpaymentInputs.vue";
import Datepicker from '@vuepic/vue-datepicker';

const {formatHarmonogram} = useHelpers();

const credit = ref({
  date: new Date(2023,1),
  amountOfCredit: 300000,
  period: 5,
  margin: 1.93,
  wibor: 7,
  commission: 0
});

const overpaymentList = ref([]);
const wiborList = ref([]);

wiborList.value.splice(5,0,8);
// console.log(wiborList.value)
// console.table(formatHarmonogram(CreditCalculation(credit.value, overpaymentList.value, wiborList.value).getSchedule()));
const schedule = formatHarmonogram(CreditCalculation(credit.value, overpaymentList.value, wiborList.value).getSchedule());


const test = ref(null);
</script>


<template>
  <Head title="Oferta" />
  <Layout>
    <template v-slot:header>Oferta</template>
    <template v-slot:default>
      <OverpaymentInputs />
<!--      <CreditSchedule :schedule="schedule"/>-->
    </template>
  </Layout>
</template>
