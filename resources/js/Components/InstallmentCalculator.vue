<script setup>
import { ref } from "vue";
import { useInstallment } from "@/Composables/creditCalculation.js";
import InputCalculator from "@/Components/InputCalculator.vue";

const { interestRate } = useInstallment();

const amountOfCredit = ref(250000);
const period = ref(25);
const rate = ref(7);
const commission = ref(0);
const installment = ref("");

const show = () => {
  console.log(interestRate(amountOfCredit.value, period.value, rate.value));
  installment.value = interestRate(amountOfCredit.value, period.value, rate.value);
};
</script>
<template>
  <section class="flex flex-col gap-5 w-2/4 mx-auto rounded-lg shadow-md bg-white p-5">
    <div>
      <div class="flex mb-3 items-center justify-between">
        <h3 class="font-semibold text-black">Kwota kredytu</h3>
        <div class="form-control">
          <input
            v-model="amountOfCredit"
            type="number"
            class="text-center bg-white appearance-none border-2 border-indigo-700 rounded w-full py-2 px-4 text-black leading-tight focus:outline-none focus:bg-white font-semibold"
          />
          <InputCalculator span-text="PLN" />
        </div>
      </div>
      <input
        type="range"
        min="50000"
        max="2000000"
        step="10000"
        v-model="amountOfCredit"
        class="range range-primary"
      />
      <label class="label">
        <span class="label-text-alt text-black">50 000 zł</span>
        <span class="label-text-alt text-black">2 000 000 zł</span>
      </label>
    </div>
    <div>
      <div class="flex mb-3 items-center justify-between">
        <h3 class="font-semibold text-black">Okres spłaty</h3>
        <div class="form-control">
          <input
            v-model="period"
            type="number"
            class="text-center bg-white appearance-none border-2 border-indigo-700 rounded w-full py-2 px-4 text-black leading-tight focus:outline-none focus:bg-white font-semibold"
          />
        </div>
      </div>
      <input type="range" min="5" max="35" v-model="period" class="range range-primary" />
      <label class="label">
        <span class="label-text-alt text-black">5 lat</span>
        <span class="label-text-alt text-black">35 lat</span>
      </label>
    </div>
    <div>
      <div class="flex mb-3 items-center justify-between">
        <h3 class="font-semibold text-black">Oprocentowanie</h3>
        <div class="form-control">
          <input
            v-model="rate"
            type="number"
            class="text-center bg-white appearance-none border-2 border-indigo-700 rounded w-full py-2 px-4 text-black leading-tight focus:outline-none focus:bg-white font-semibold"
          />
        </div>
      </div>
      <input
        type="range"
        min="0.01"
        max="15"
        step="0.01"
        v-model="rate"
        class="range range-primary"
      />
      <label class="label">
        <span class="label-text-alt text-black">0,01%</span>
        <span class="label-text-alt text-black">15%</span>
      </label>
    </div>
    <div>
      <div class="flex mb-3 items-center justify-between">
        <h3 class="font-semibold text-black">Prowizja</h3>
        <div class="form-control">
          <input
            v-model="commission"
            type="number"
            class="text-center bg-white appearance-none border-2 border-indigo-700 rounded w-full py-2 px-4 text-black leading-tight focus:outline-none focus:bg-white font-semibold"
          />
        </div>
      </div>
      <input
        type="range"
        min="0.01"
        max="15"
        step="0.01"
        v-model="commission"
        class="range range-primary"
      />
      <label class="label">
        <span class="label-text-alt text-black">0,01%</span>
        <span class="label-text-alt text-black">15%</span>
      </label>
    </div>
    <button @click="show" class="btn btn-primary mt-10 text-white">
      Oblicz ratę i koszt
    </button>
    <h1>
      Rata miesięczna: <span class="font-bold text-xl">{{ installment }}</span>
    </h1>
  </section>
</template>
