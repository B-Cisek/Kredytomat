<script setup>
import { ref } from "vue";
import InputCalculator from "@/Components/InputCalculator.vue";
import Layout from "@/Layouts/Layout.vue";

const amountOfCredit = ref(250000);
const period = ref(25);
const commission = ref(0);
const margin = ref(0);
</script>

<template>
  <Layout>
    <template #header> Kalkulator RRSO </template>
    <template #default>
      <section
        class="bg-white shadow-2xl border border-gray-200 rounded-lg md:flex p-4 gap-10"
      >
        <div class="flex-1">
          <div>
            <div class="flex w-full justify-between items-center mb-5">
              <h3 class="font-semibold text-black">Kwota kredytu</h3>
              <InputCalculator span-text="PLN" v-model="amountOfCredit" />
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
            <div class="flex w-full justify-between items-center mb-5">
              <h3 class="font-semibold text-black">Marża banku</h3>
              <InputCalculator span-text="%" v-model="margin" />
            </div>
            <input
              type="range"
              min="0.01"
              max="5"
              step="0.01"
              v-model="margin"
              class="range range-primary"
            />
            <label class="label">
              <span class="label-text-alt text-black">0 %</span>
              <span class="label-text-alt text-black">5 %</span>
            </label>
          </div>
        </div>
        <div class="flex-1">
          <div>
            <div class="flex w-full justify-between items-center mb-5">
              <h3 class="font-semibold text-black">Okres spłaaty</h3>
              <InputCalculator span-text="LAT" v-model="period" />
            </div>
            <input
              type="range"
              min="5"
              max="35"
              step="1"
              v-model="period"
              class="range range-primary"
            />
            <label class="label">
              <span class="label-text-alt text-black">5 lat</span>
              <span class="label-text-alt text-black">35 lat</span>
            </label>
          </div>
          <div>
            <div class="flex w-full justify-between items-center mb-5">
              <h3 class="font-semibold text-black">Prowizja</h3>
              <InputCalculator span-text="%" v-model="commission" />
            </div>
            <input
              type="range"
              min="0.01"
              max="5"
              step="0.01"
              v-model="commission"
              class="range range-primary"
            />
            <label class="label">
              <span class="label-text-alt text-black">0 %</span>
              <span class="label-text-alt text-black">5 %</span>
            </label>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>
