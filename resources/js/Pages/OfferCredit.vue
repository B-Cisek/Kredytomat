<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useHelpers} from "@/Composables/useHelpers";
import {onMounted, ref} from "vue";
import {Head, Link} from "@inertiajs/inertia-vue3";

const {formattedToPLN} = useHelpers();

const props = defineProps({
  credit: Object
});

let details = ref(null);

onMounted(() => details.value = JSON.parse(props.credit.details));
</script>

<template>
  <Head title="Oferta"/>
  <Layout>
    <template v-slot:header>
      {{ credit.bank.bank_name }} - {{ credit.credit_name }}
    </template>
    <template v-slot:default>
      <div class="overflow-hidden bg-white shadow sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6 flex flex-col sm:flex-row justify-between">
          <div>
            <h3 class="text-base font-semibold leading-6 text-gray-900">{{ credit.bank.bank_name }}</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">{{ credit.credit_name }}</p>
          </div>
          <img class="w-[200px] mt-3 sm:mt-0" :src="credit.bank.logo_path" alt="Logo banku">
        </div>
        <div class="border-t border-gray-200">
          <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Oprocentowanie</dt>
              <dd class="mt-1 text-sm font-bold text-gray-900 sm:col-span-2 sm:mt-0">
                {{ (Number(credit.margin) + Number(credit.wibor.value)).toFixed(2) }}%
              </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Marża</dt>
              <dd class="mt-1 text-sm font-bold text-gray-900 sm:col-span-2 sm:mt-0">{{ credit.margin }}%</dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">WIBOR</dt>
              <dd class="mt-1 text-sm font-bold text-gray-900 sm:col-span-2 sm:mt-0">{{ credit.wibor.value }}%</dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Prowizja</dt>
              <dd class="mt-1 text-sm font-bold text-gray-900 sm:col-span-2 sm:mt-0">{{ credit.commission }}%</dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
              <dt class="text-sm font-medium text-gray-500">Kwota</dt>
              <dd class="mt-1 text-sm font-bold text-gray-900 sm:col-span-2 sm:mt-0">
                {{ formattedToPLN.format(credit.amount_from) }} - {{ formattedToPLN.format(credit.amount_to) }}
              </dd>
            </div>

            <div v-for="(value, key, index) in details"
                 class="px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                 :class="{'bg-gray-50': index % 2 !== 0, 'bg-white': index % 2 === 0}"
            >
              <dt class="text-sm font-medium text-gray-500">{{ key.charAt(0).toUpperCase() + key.slice(1) }}</dt>
              <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ value }}</dd>
            </div>

            <div class="bg-white px-4 py-5 ">
              <Link
                :href="route('calculator.extended', {margin: credit.margin, wibor: credit.wibor.value, commission: credit.commission})"
                class="text-sm font-medium text-blue-500 text-right block"
              >Użyj danych kredytu w kalkulatorze
              </Link>
            </div>
          </dl>
        </div>
      </div>
    </template>
  </Layout>
</template>
