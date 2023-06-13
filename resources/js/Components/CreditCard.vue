<script setup>
import {ref} from "vue";
import {Link} from "@inertiajs/inertia-vue3";
import {ChevronUpIcon, ChevronDownIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
  credits: Object,
});

const showed = ref([]);

const add = index => {
  if (showed.value.includes(index)) {
    showed.value = showed.value.filter((v) => v !== index);
  } else {
    showed.value.push(index);
  }
};

const formatToPLN = amount => {
  return new Intl.NumberFormat("pl-PL", {
    style: "currency",
    currency: "PLN",
    maximumFractionDigits: 0,
  }).format(amount);
};
</script>

<template>
  <div>
    <span class="font-semibold text-gray-600 ml-2">Najnowsze 6 ofert:</span>
  </div>
  <section
    v-for="(credit, index) in credits"
    :key="credit.id"
    class="rounded-lg p-2 mt-2 shadow-2xl border border-gray-200 bg-white"
  >
    <div class="w-full py-1">
      <span>{{ index + 1 }}.</span>
      <span class="ml-5">{{ credit.credit_name }}</span>
    </div>
    <div class="w-full flex justify-between px-8 items-center py-3 text-center flex-col lg:flex-row">
      <div>
        <div class="w-[150px] h-[60px]">
          <img
            :src="credit.bank.logo_path"
            :alt="credit.bank.bank_name"
            class="w-full h-full object-fit"
          />
        </div>
      </div>
      <div class="flex flex-col md:flex-row w-full justify-around px-10 my-5 lg:my-0">
        <div>
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
        <div class="sm:block hidden">
          <span class="text-gray-500">RRSO</span>
          <p class="font-semibold">9,33%</p>
        </div>
        <div class="">
          <span class="text-gray-500">Kwota do</span>
          <p class="font-semibold">{{ formatToPLN(credit.amount_to) }}</p>
        </div>
      </div>
      <div>
        <Link
          :href="route('offer.show.credit', [credit.bank.slug, credit.slug])"
          class="px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150">
          Sprawdź
        </Link>
      </div>
    </div>
    <div class="w-full border-t py-1 flex justify-center">
      <button class="flex justify-center items-center gap-1" @click="add(index)">
        <span class="text-gray-500">Szczegóły</span>
        <ChevronUpIcon v-show="showed.includes(index)" class="h-5 w-5 text-gray-500"/>
        <ChevronDownIcon v-show="!showed.includes(index)" class="h-5 w-5 text-gray-500"/>
      </button>
    </div>
    <div class="bg-white mt-1" v-show="showed.includes(index)">
      <div class="w-full">
        <p v-for="(detail, index) in JSON.parse(credit.details)" class="border-b border-gray py-2 px-2 last:border-b-0">
          <span>{{ index }}:</span>
          <span class="font-semibold">{{ detail }}</span>
        </p>
      </div>
    </div>
  </section>
</template>
