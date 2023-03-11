<script setup>
import {onMounted, ref, watch} from "vue";
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN, getPaidInterestToIndex, getPaidCapitalToIndex, getCapitalToPayFromIndex} = useHelpers();
const props = defineProps({
  schedule: Object
});

const period = ref(0);
const capitalWidth = ref(0);
const interestWidth = ref(0);

const calculation = () => {
  let sum = props.schedule[period.value][3] + props.schedule[period.value][2];
  capitalWidth.value = (props.schedule[period.value][3] / sum * 1) * 100;
  interestWidth.value = (props.schedule[period.value][2] / sum * 1) * 100;
}

watch(period, () => {
  calculation();
});

onMounted(() => {
  calculation();
});

</script>

<template>
  <div>
    <div class="px-3">
      <input
        type="range"
        v-model="period"
        min="0"
        step="1"
        :max="props.schedule.length - 1"
        class="range range-primary bg-[#d1d3d9]"
      />
      <label class="label">
        <span class="label-text-alt text-black">1</span>
        <span class="label-text-alt text-black">300</span>
      </label>
    </div>

    <div class="flex justify-center">
      <div class="relative w-[300px]">
        <input
          v-model="period"
          type="number"
          readonly
          class="border-2 pl-24 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-full"
        />
        <button
          @click="period--"
          class="absolute left-0 w-10 bg-indigo-700 h-full rounded-l-lg inline-flex items-center justify-center font-semibold text-white"
        >-
        </button>
        <button
          @click="period++"
          class="absolute right-20 w-10 bg-indigo-700 h-full inline-flex items-center justify-center font-semibold text-white"
        >+
        </button>
        <span
          class="absolute right-10 w-10 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-black"
        >{{ Math.round(period / 12) }}</span>
        <span
          class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
        >LAT</span>
      </div>
    </div>
  </div>

  <div class="bg-[#465775] rounded-b-lg mt-5">
    <div class="p-5 flex flex-col gap-5">
      <div class="flex justify-between">
        <div>
          <label class="text-white/80">Rata kredytu</label>
          <h1 class="text-white font-semibold text-3xl">{{ formattedToPLN.format(props.schedule[period][4]) }}</h1>
        </div>
        <div>
          <label class="text-white/80">Miesiąc spłaty</label>
          <h1 class="text-white font-semibold text-3xl">{{ Number(period) + 1 }}</h1>
        </div>
        <div>
          <label class="text-white/80">Rok spłaty</label>
          <h1 class="text-white font-semibold text-3xl">{{ Math.floor(Number(period / 12)) + 1 }}</h1>
        </div>
      </div>

      <div>
        <div class="flex justify-between">
          <h6 class="text-white">RATA KAPITAŁOWA: {{ Math.round(capitalWidth) }}%</h6>
          <h6 class="text-white">RATA ODSETKOWA: {{ Math.round(interestWidth) }}%</h6>
        </div>
        <div class="flex justify-between mt-2 gap-3">
          <div class="bg-[#21A179] text-right rounded py-1 pr-2" :style="`width: ${(capitalWidth < 8) ? 8 : capitalWidth}%`">
            <p class="text-center  text-white">{{ formattedToPLN.format(props.schedule[period][3]) }}</p>
          </div>
          <div class="bg-[#DF2935] text-right rounded py-1 pr-2" :style="`width: ${(interestWidth < 8) ? 8 : interestWidth}%`">
            <p class="text-center font-semibold text-white">{{ formattedToPLN.format(props.schedule[period][2]) }}</p>
          </div>
        </div>
      </div>

      <div>
        <div>
          <label class="text-white/80">Zapłacone odsetki</label>
          <h1 class="text-white font-semibold text-3xl">
            {{ formattedToPLN.format(getPaidInterestToIndex(props.schedule, period)) }}</h1>
        </div>
        <div>
          <label class="text-white/80">Kapitał spłacony</label>
          <h1 class="text-white font-semibold text-3xl">
            {{ formattedToPLN.format(getPaidCapitalToIndex(props.schedule, period)) }}</h1>
        </div>
        <div>
          <label class="text-white/80">Kapitał do spłaty</label>
          <h1 class="text-white font-semibold text-3xl">
            {{ formattedToPLN.format(getCapitalToPayFromIndex(props.schedule, period)) }}</h1>
        </div>
      </div>
    </div>
  </div>

</template>
