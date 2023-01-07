<script setup>
import CreditSchedule from "@/Components/CreditSchedule.vue";
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import {useRatyStaleExtended} from "@/Composables/useRatyStaleExtended";
import {useRatyMalejaceExtended} from "@/Composables/useRatyMalejaceExtended";
import {useHelpers} from "@/Composables/useHelpers";
import TabsGroup from "@/Components/TabsGroup.vue";

const {formattedToPLN, formatHarmonogram} = useHelpers();

const props = defineProps({
  wiborList: Object,
});


// Inputs Form
const amountOfCredit = ref(250000);
const period = ref(25);
const margin = ref(1);
const commission = ref(0);
const wibor = ref(null);
const typeOfInstallment = ref(null);
const schedule = ref([])


const getResult = () => {
  const kredyt = {
    kwotaKredytu: amountOfCredit.value,
    okres: period.value,
    marza: margin.value,
    wibor: wibor.value,
    prowizja: commission.value
  }

  if (typeOfInstallment.value === "rowne") calculateFixedInstallments(kredyt);
  if (typeOfInstallment.value === "malejace") calculateDecreasingInstallments(kredyt);
}

const calculateFixedInstallments = (kredyt) => {
  const result = useRatyStaleExtended(kredyt);
  console.log('Rata Stała', formattedToPLN.format(result.getRataStala()));
  schedule.value = formatHarmonogram(result.getHarmonogram());
}

const calculateDecreasingInstallments = (kredyt) => {
  const result = useRatyMalejaceExtended(kredyt);
  console.log('Pierwsza rata malejąca', formattedToPLN.format(result.getPierwszaRata()))
  schedule.value = formatHarmonogram(result.getHarmonogram());
}

</script>


<template>
  <Layout>
    <template v-slot:header>Kalkulator rozszerzony</template>

    <template v-slot:default>
      <section class="w-full rounded-lg shadow-2xl border border-gray-200 bg-white p-5">
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <h3 class="font-semibold text-black">Kwota kredytu</h3>
              <div class="relative">
                <input
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="amountOfCredit"
                />
                <span
                  class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
                >PLN</span
                >
              </div>
            </div>
            <input
              type="range"
              min="50000"
              max="2000000"
              step="10000"
              v-model="amountOfCredit"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">50 000 zł</span>
              <span class="label-text-alt text-black">2 000 000 zł</span>
            </label>
          </div>
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <h3 class="font-semibold text-black">Okres spłaty</h3>
              <div class="relative">
                <input
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="period"
                />
                <span
                  class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
                >LAT</span
                >
              </div>
            </div>
            <input
              type="range"
              min="5"
              max="35"
              step="1"
              v-model="period"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">5 lat</span>
              <span class="label-text-alt text-black">35 lat</span>
            </label>
          </div>
        </div>
        <div class="lg:flex gap-x-16">
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <h3 class="font-semibold text-black">Marża</h3>
              <div class="relative">
                <input
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="margin"
                />
                <span
                  class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
                >%</span
                >
              </div>
            </div>
            <input
              type="range"
              min="0.00"
              max="15"
              step="0.01"
              v-model="margin"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">0,01%</span>
              <span class="label-text-alt text-black">15%</span>
            </label>
          </div>
          <div class="flex-1">
            <div class="flex mb-3 items-center justify-between">
              <label for="" class="font-semibold text-black">Prowizja</label>
              <div class="relative">
                <input
                  id="commission"
                  type="number"
                  class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[150px]"
                  v-model="commission"
                />
                <span
                  class="absolute right-0 w-10 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white"
                >%</span
                >
              </div>
            </div>
            <input
              type="range"
              min="0.00"
              max="15"
              step="0.01"
              v-model="commission"
              class="range range-primary bg-[#d1d3d9]"
            />
            <label class="label">
              <span class="label-text-alt text-black">0%</span>
              <span class="label-text-alt text-black">15%</span>
            </label>
          </div>
        </div>
        <div class="lg:flex gap-x-16 mt-7">
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">WIBOR</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                v-model="wibor"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option
                  v-for="wibor in props.wiborList"
                  :key="wibor.id"
                  :value="wibor.value"
                >{{ wibor.type + ` (${wibor.value}%)` }}
                </option>
              </select>
            </div>
          </div>
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">Rodzaj rat</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                v-model="typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="rowne">Równe</option>
                <option value="malejace">Malejące</option>
              </select>
            </div>
          </div>
        </div>
        <button @click="getResult" class="btn btn-primary mt-10 text-white w-full">
          Oblicz
        </button>
      </section>

      <section class="mt-5">
        <TabsGroup :schedule="schedule"/>
      </section>
    </template>
  </Layout>
</template>
