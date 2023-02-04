<script setup>
import {onMounted, ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN} = useHelpers()

const props = defineProps({
  amountOfInstallment: Number,
  installmentsLeftToPay: Number,
  capitalInstallment: Number,
  interestInstallment: Number,
});

const capitalWidth = ref(27);
const interestWidth = ref(73);

// onMounted(() => {
//   let sum = props.capitalInstallment + props.interestInstallment;
//   capitalWidth.value = (props.capitalInstallment / sum * 1) * 100;
//   interestWidth.value = (props.interestInstallment / sum * 1) * 100;
//   console.log(capitalWidth.value.toFixed(2), interestWidth.value.toFixed(2));
// })
</script>

<template>
<!-- #016ed6  003ec5-->
  <div class="bg-[#4338ca] p-5 w-full rounded">
    <div class="flex justify-between">
      <div class="block">
        <label class="text-white text-xl">Wysokość raty:</label>
        <h3 class="text-white text-3xl mt-1 font-semibold">1 723,33 PLN</h3>
      </div>
      <div class="flex items-center">
        <label class="text-white mr-5">Pozostało rat:</label>
        <div class="border-4 px-3 py-5 rounded-full text-2xl font-bold text-white w-[80px] h-[80px]">300</div>
      </div>
    </div>
    <div class="mb-5 mt-5">
      <p class="text-white">W skład Twojej raty wchodzi:</p>
    </div>
    <div class="flex justify-between">
      <h6 class="text-white">RATA KAPITAŁOWA: 27%</h6>
      <h6 class="text-white">RATA ODSETKOWA: 73%</h6>
    </div>
    <div class="flex justify-between mt-2 gap-3">
      <div class="bg-[#21A179] text-right rounded py-1 pr-2" :style="`width: ${capitalWidth}%`">
        <p class="text-center font-semibold text-white"> 1 320,33 zł</p>
      </div>
      <div class="bg-[#DF2935] text-right rounded py-1 pr-2" :style="`width: ${interestWidth}%`">
        <p class="text-center font-semibold text-white"> 3 160,10 zł</p>
      </div>
    </div>
    <div class="flex mt-7">
      <div class="flex-1">
        <p class="text-white text-xl">Odsetki do spłaty</p>
        <p class="text-white text-3xl mt-1 font-semibold">695 030,29 zł</p>
      </div>
      <div class="flex-1 text-end">
        <p class="text-white text-xl">Całkowity koszt kredytu</p>
        <p class="text-white text-3xl mt-1 font-semibold">873 920,10 zł</p>
      </div>
    </div>
  </div>
</template>



