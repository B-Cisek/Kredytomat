<script setup>
import { ref } from "vue";
const props = defineProps({
  credits: Object,
});

const more = ref(false);

const showMore = () => {
  more.value = !more.value;
};
</script>

<template>
  <section
    v-for="(credit, index) in credits"
    class="bg-white rounded-lg p-2 mt-2 shadow-md"
  >
    <div class="w-full">
      <span>{{ index + 1 }}.</span>
      <span class="ml-5">{{ credit.credit_name }}</span>
    </div>
    <div class="w-full flex justify-around items-center mt-3 text-center">
      <div>
        <img :src="credit.bank.logo_path" alt="" width="150" />
      </div>
      <div class="flex flex-col">
        <span class="font-semibold">Prowizja</span>
        <span>{{ credit.commission }}%</span>
      </div>
      <div class="flex flex-col">
        <span class="font-semibold">Oprocentowanie</span>
        <span>8,66%</span>
      </div>
      <div class="flex flex-col">
        <span class="font-semibold">Okres</span>
        <span>{{ credit.period_from / 12 }}-{{ credit.period_to / 12 }}lat</span>
      </div>
      <div class="flex flex-col">
        <span class="font-semibold">Kwota do spłaty</span>
        <span>609 517,38 zł</span>
      </div>
      <div class="flex flex-col">
        <span class="font-semibold">Rata</span>
        <span>1 860,51 zł</span>
      </div>
      <div>
        <a
          href="#"
          class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-3 py-1 text-base font-medium text-white hover:bg-indigo-700"
          >Sprawdź</a
        >
      </div>
    </div>
    <div class="w-full text-center mt-3">
      <p>Szczegóły</p>
    </div>
  </section>
</template>
