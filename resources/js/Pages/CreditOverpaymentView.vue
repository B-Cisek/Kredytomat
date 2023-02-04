<script setup>
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import RangeWithInput from "@/Components/RangeWithInput.vue";
import InputList from "@/Components/InputList.vue";

const props = defineProps({
  wiborList: Object,
});

const wiborChanges = ref([]);
const overpayments = ref([]);

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

const getResult = () => {
  const result = v$.value.$validate();
}

const getOverpayments = (value) => {
  overpayments.value = value;
}

const getWiborChanges = (value) => {
  wiborChanges.value = value;
  console.log(wiborChanges.value)
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
            <RangeWithInput
              v-model="formData.amountOfCredit"
              input-type-label="PLN"
              heading="Kwota kredytu"
              :min="50000"
              :max="2000000"
              :step="10000"
              label-left="50 000 zł"
              label-right="2 000 000 zł"
            />
          </div>
          <div class="flex-1">
            <RangeWithInput
              v-model="formData.period"
              input-type-label="LAT"
              heading="Okres spłaty"
              :min="5"
              :max="35"
              :step="1"
              label-left="5 lat"
              label-right="35 lat"
            />
          </div>
        </div>
        <div class="lg:flex gap-x-16 mt-7">
          <div class="flex-1">
            <RangeWithInput
              v-model="formData.margin"
              input-type-label="%"
              heading="Marża"
              :min="0.01"
              :max="15"
              :step="0.01"
              label-left="0,01%"
              label-right="15%"
            />
          </div>
          <div class="flex-1">
            <RangeWithInput
              v-model="formData.commission"
              input-type-label="%"
              heading="Prowizja"
              :min="0.00"
              :max="15"
              :step="0.01"
              label-left="0,01%"
              label-right="15%"
            />
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
        <div class="mt-7">
          <div>
            <span class="text-xl font-semibold mr-5">Nadpłata</span>
            <InputList
              @input-list="getOverpayments"
              placeholder="PLN"
              button-text="Dodaj nadpłate"
            />
          </div>
          <div>
            <span class="text-xl font-semibold mr-5">Zmiana wiboru</span>
            <InputList
              @input-list="getWiborChanges"
              placeholder="Nowy WIBOR"
              button-text="Dodaj zmina wiboru"
            />
          </div>
        </div>
        <button @click="getResult" class="btn btn-primary mt-10 text-white w-full">
          Oblicz
        </button>
      </section>
    </template>
  </Layout>
</template>
