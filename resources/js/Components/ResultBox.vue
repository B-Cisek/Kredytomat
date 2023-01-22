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

const capitalWidth = ref(0);
const interestWidth = ref(0);

onMounted(() => {
  let sum = props.capitalInstallment + props.interestInstallment;
  capitalWidth.value = (props.capitalInstallment / sum * 1) * 100;
  interestWidth.value = (props.interestInstallment / sum * 1) * 100;
  console.log(capitalWidth.value.toFixed(2), interestWidth.value.toFixed(2));
})
</script>

<template>
<!-- #016ed6  003ec5-->
  <div class="bg-[#016ed6] p-10 w-[550px] rounded">
    <div class="flex justify-between">
      <div class="block">
        <label class="text-white">Wysokość raty:</label>
        <h3 class="text-white text-4xl mt-1">{{ formattedToPLN.format(amountOfInstallment) }} PLN</h3>
      </div>
      <div class="flex items-center">
        <label class="text-white mr-5">Pozostało rat:</label>
        <div class="border-4 px-3 py-5 rounded-full text-2xl font-bold text-white">{{ installmentsLeftToPay }}</div>
      </div>
    </div>
    <div class="mb-5 mt-5">
      <p class="text-white">W skład Twojej raty wchodzi:</p>
    </div>
    <div class="flex justify-between">
      <h6 class="text-white">RATA KAPITAŁOWA</h6>
      <h6 class="text-white">RATA ODSETKOWA</h6>
    </div>
    <div class="flex justify-between mt-2 gap-3">
      <div class="bg-red-200 text-right rounded py-1 pr-2" :style="`width: ${capitalWidth}%`">{{ capitalInstallment }}
        PLN
      </div>
      <div class="bg-yellow-200 text-right rounded py-1 pr-2" :style="`width: ${interestWidth}%`">{{
          interestInstallment
        }} PLN
      </div>
    </div>
    <p class="mt-6 text-sm text-white">Podana wielkość rat może nie uwzględniać innych opłat takich jak dodatkowe
      ubezpieczenia lub prowizje, które może mieć kredytobiorca.</p>
  </div>
</template>



