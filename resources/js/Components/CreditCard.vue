<script setup>
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
const props = defineProps({
  credits: Object,
});

const showed = ref([]);

const add = (index) => {
  if (showed.value.includes(index)) {
    showed.value = showed.value.filter((v) => v !== index);
  } else {
    showed.value.push(index);
  }
  console.log(showed.value);
};

const formatToPLN = (amount) => {
  return new Intl.NumberFormat("pl-PL", {
    style: "currency",
    currency: "PLN",
    maximumFractionDigits: 0,
  }).format(amount);
};
</script>

<template>
  <section
    v-for="(credit, index) in credits"
    :key="credit.id"
    class="bg-white rounded-lg p-2 mt-2 shadow-md"
  >
    <div class="w-full py-1">
      <span>{{ index + 1 }}.</span>
      <span class="ml-5">{{ credit.credit_name }}</span>
    </div>
    <div class="w-full flex justify-around items-center py-3 text-center">
      <div>
        <div class="w-[150px] h-[60px]">
          <img
            :src="credit.bank.logo_path"
            :alt="credit.bank.bank_name"
            class="w-full h-full object-contain"
          />
        </div>
      </div>
      <div class="">
        <span class="text-gray-500">Okres</span>
        <p class="font-semibold text-lg">
          {{ credit.period_from / 12 }}-{{ credit.period_to / 12 }} lat
        </p>
      </div>
      <div class="">
        <span class="text-gray-500">Prowizja</span>
        <p class="font-semibold">{{ credit.commission }}%</p>
      </div>
      <div class="">
        <span class="text-gray-500">Marża</span>
        <p class="font-semibold">{{ credit.margin }}%</p>
      </div>
      <div class="">
        <span class="text-gray-500">RRSO</span>
        <p class="font-semibold">9,33%</p>
      </div>
      <div class="">
        <span class="text-gray-500">Kwota</span>
        <p class="font-semibold">{{ formatToPLN(credit.amount_from) }} -</p>
        <p class="font-semibold">{{ formatToPLN(credit.amount_to) }}</p>
      </div>
      <div>
        <PrimaryButton>Sprawdź</PrimaryButton>
      </div>
    </div>
    <div class="w-full border-t py-1 flex justify-center">
      <button class="flex justify-center items-center gap-1" @click="add(index)">
        <span>Szczegóły</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 24 24"
          fill="currentColor"
          class="w-4 h-4"
        >
          <path
            fill-rule="evenodd"
            d="M12.53 16.28a.75.75 0 01-1.06 0l-7.5-7.5a.75.75 0 011.06-1.06L12 14.69l6.97-6.97a.75.75 0 111.06 1.06l-7.5 7.5z"
            clip-rule="evenodd"
          />
        </svg>
      </button>
    </div>
    <div class="bg-white mt-7" v-show="showed.includes(index)">
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta magni voluptatibus
        voluptates officiis doloremque. Id harum commodi dicta laboriosam voluptates rem
        illum ratione, qui sequi eos adipisci temporibus iste. Ex!
      </p>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta magni voluptatibus
        voluptates officiis doloremque. Id harum commodi dicta laboriosam voluptates rem
        illum ratione, qui sequi eos adipisci temporibus iste. Ex!
      </p>
      <p>
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta magni voluptatibus
        voluptates officiis doloremque. Id harum commodi dicta laboriosam voluptates rem
        illum ratione, qui sequi eos adipisci temporibus iste. Ex!
      </p>
    </div>
    <!-- TODO: rozwijane szczegóły -->
  </section>
</template>
