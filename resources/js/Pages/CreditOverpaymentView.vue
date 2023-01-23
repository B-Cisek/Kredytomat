<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import CreditOverpaymentSchedule from "@/Components/CreditOverpaymentSchedule.vue";
import OverpaymentInputs from "@/Components/OverpaymentInputs.vue";


const props = defineProps({
  wiborList: Object,
});

const date = ref({
  month: new Date().getMonth(),
  year: new Date().getFullYear()
})

const formData = ref({
  amountOfCredit: 250000,
  period: 25,
  margin: 1,
  commission: 0,
  wibor: null,
  typeOfInstallment: null
});


const kredyt = {
  kwotaKredytu: formData.value.amountOfCredit,
  okres: formData.value.period,
  marza: formData.value.margin,
  wibor: formData.value.wibor,
  prowizja: formData.value.commission
}

const rules = {
  amountOfCredit: {required},
  period: {required},
  margin: {required},
  commission: {required},
  wibor: {required},
  typeOfInstallment: {required}
}

const v$ = useVuelidate(rules, formData)


const schedule = ref([])

const test = ref(null);
const getResult = () => {
  const result = v$.value.$validate();
  console.log(date.value)

}

const inputs = ref([]);


const overpaymentList = ref([]);

const addOverpaymentInput = (overpayment) => {
  inputs.value.push(OverpaymentInputs);
}


</script>

<template>
  <Layout>
    <template v-slot:header>
      Kalulator nadpłaty kredytu
    </template>
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
                  v-model="formData.amountOfCredit"
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
              v-model="formData.amountOfCredit"
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
                  v-model="formData.period"
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
              v-model="formData.period"
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
                  v-model="formData.margin"
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
              v-model="formData.margin"
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
                  v-model="formData.commission"
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
              v-model="formData.commission"
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
                :class="{'border-red-700': v$.wibor.$errors.length !== 0}"
                v-model="formData.wibor"
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
            <span
              v-for="error in v$.wibor.$errors"
              :key="error.$uid"
              class="text-red-700"
            >Wybierz wibor</span>
          </div>
          <div class="flex-1">
            <div class="flex justify-between items-center">
              <label class="font-semibold text-black" for="wibor">Rodzaj rat</label>
              <select
                class="select select-bordered max-w-xs border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none w-[260px]"
                :class="{'border-red-700': v$.typeOfInstallment.$errors.length !== 0}"
                v-model="formData.typeOfInstallment"
              >
                <option disabled :value="null" selected>Wybierz</option>
                <option value="rowne">Równe</option>
                <option value="malejace">Malejące</option>
              </select>
            </div>
            <span
              v-for="error in v$.typeOfInstallment.$errors"
              :key="error.$uid"
              class="text-red-700"
            >Wybierz rodzaj raty</span>
          </div>
        </div>
        <div class="lg:flex gap-x-16 mt-7">
          <div class="flex-1">
            <button @click="addOverpaymentInput" class="btn bg-gray-700">Dodaj nadpłate</button>
            <div class="space-y-1 mt-2">
              <component v-for="input in inputs" :key="input.key" :is="input" />
            </div>
          </div>
        </div>
        <button @click="getResult" class="btn btn-primary mt-10 text-white w-full">
          Oblicz
        </button>
      </section>
      <section
        v-if="test != null"
        class="w-full mx-auto p-5 mt-8 flex flex-col rounded-lg shadow-2xl border border-gray-200 bg-white">
        <CreditOverpaymentSchedule :credit="kredyt" :overpayment="[]"/>
      </section>
    </template>
  </Layout>
</template>
