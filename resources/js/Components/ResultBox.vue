<script setup>
import {onMounted, onUpdated, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN, totalCreditCost, totalCreditInterest} = useHelpers()

const props = defineProps({
  schedule: Object
});

const capitalWidth = ref(null);
const interestWidth = ref(null);

const totalInstallment = ref(0);
const capitalPart = ref(0);
const interestPart = ref(0);
const totalCostOfCredit = ref(0);
const totalInterest = ref(0);

const result = () => {
  totalInstallment.value = props.schedule[0][4];
  capitalPart.value = props.schedule[0][3];
  interestPart.value = props.schedule[0][2];

  totalCostOfCredit.value = totalCreditCost(props.schedule);
  totalInterest.value = totalCreditInterest(props.schedule);

  let sum = capitalPart.value + interestPart.value;
  capitalWidth.value = (capitalPart.value / sum * 1) * 100;
  interestWidth.value = (interestPart.value / sum * 1) * 100;
}

onUpdated(() => result())

onMounted(() => result())



</script>

<template>
<!-- #016ed6  003ec5-->
  <div class="bg-[#4338ca] p-5 w-full rounded">
    <div class="flex justify-between">
      <div class="block">
        <label class="text-white text-xl">Wysokość raty:</label>
        <h3 class="text-white text-3xl mt-1 font-semibold">{{ formattedToPLN.format(totalInstallment) }}</h3>
      </div>
      <div class="flex items-center">
        <label class="text-white mr-5">Pozostało rat:</label>
        <div class="border-4 px-3 py-5 rounded-full text-2xl font-bold text-white text-center w-[80px] h-[80px]">{{ props.schedule.length }}</div>
      </div>
    </div>
    <div class="mb-5 mt-5">
      <p class="text-white">W skład Twojej raty wchodzi:</p>
    </div>
    <div class="flex justify-between">
      <h6 class="text-white">RATA KAPITAŁOWA: {{ Math.round(capitalWidth) }}%</h6>
      <h6 class="text-white">RATA ODSETKOWA: {{ Math.round(interestWidth) }}%</h6>
    </div>
    <div class="flex justify-between mt-2 gap-3">
      <div class="bg-[#21A179] text-right rounded py-1 pr-2" :style="`width: ${(capitalWidth < 8 ? 20 : capitalWidth)}%`">
        <p class="text-center  text-white">{{ formattedToPLN.format(capitalPart) }}</p>
      </div>
      <div class="bg-[#DF2935] text-right rounded py-1 pr-2" :style="`width: ${interestWidth}%`">
        <p class="text-center font-semibold text-white">{{ formattedToPLN.format((interestPart < 8 ? 20 : interestPart)) }}</p>
      </div>
    </div>
    <div class="flex mt-7">
      <div class="flex-1">
        <p class="text-white text-xl">Odsetki do spłaty</p>
        <p class="text-white text-2xl mt-1 font-semibold">{{ formattedToPLN.format(totalInterest) }}</p>
      </div>
      <div class="flex-1 text-end">
        <p class="text-white text-xl">Całkowity koszt kredytu</p>
        <p class="text-white text-2xl mt-1 font-semibold">{{ formattedToPLN.format(totalCostOfCredit) }}</p>
      </div>
    </div>
  </div>
</template>



