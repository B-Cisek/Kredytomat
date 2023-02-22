<script setup>
import Layout from "@/Layouts/Layout.vue";
import {onMounted, ref, watch} from "vue";
import useVuelidate from "@vuelidate/core";
import {required} from "@vuelidate/validators";
import RangeWithInput from "@/Components/RangeWithInput.vue";
import {useEqualInstallments} from "@/Composables/useEqualInstallments"
import {useDecreasinginstallments} from "@/Composables/useDecreasinginstallments";
import {useHelpers} from "@/Composables/useHelpers";
import CreditScheduleOverpayment from "@/Components/Tables/CreditScheduleOverpayment.vue";
import OverpaymentInputsList from "@/Components/OverpaymentInputsList.vue";
import Collapse from "@/Components/Collapse.vue";

const {formatHarmonogram, totalCreditCost, totalCreditInterest, formattedToPLN} = useHelpers();

const props = defineProps({
  wiborList: Object,
});

const overpayments = ref([]);
const overpaymentsStorage = ref(localStorage.getItem("overpayment-values"));
const overpaymentType = ref(localStorage.getItem("overpayment-type") ?? "period");
const schedule = ref([]);

const formData = ref({
  amountOfCredit: Number(localStorage.getItem("overpayment-amountOfCredit") ?? 300000),
  period: Number(localStorage.getItem("overpayment-period") ?? 25),
  margin: Number(localStorage.getItem("overpayment-margin") ?? 2),
  commission: Number(localStorage.getItem("overpayment-commission") ?? 0),
  wibor: Number(localStorage.getItem("overpayment-wibor")),
  typeOfInstallment: localStorage.getItem("overpayment-typeOfInstallment") ?? "rowne"
});

watch(formData.value, (newValue, oldValue) => {
  localStorage.setItem("overpayment-amountOfCredit", newValue.amountOfCredit.toString());
  localStorage.setItem("overpayment-period", newValue.period.toString());
  localStorage.setItem("overpayment-margin", newValue.margin.toString());
  localStorage.setItem("overpayment-commission", newValue.commission.toString());
  localStorage.setItem("overpayment-wibor", newValue.wibor.toString());
  localStorage.setItem("overpayment-typeOfInstallment", newValue.typeOfInstallment.toString());
});

const rules = {
  amountOfCredit: {required},
  period: {required},
  margin: {required},
  commission: {required},
  wibor: {required},
  typeOfInstallment: {required}
}

const decreasingInstallment = () => {
  if (overpaymentType.value === "period") {
    return useDecreasinginstallments({
      date: new Date(2023, 0),
      ...formData.value
    }, overpayments.value, []).getScheduleShorterPeriod();
  } else {
    return useDecreasinginstallments({
      date: new Date(2023, 0),
      amountOfCredit: formData.value.amountOfCredit,
      period: formData.value.period,
      margin: formData.value.margin,
      wibor: formData.value.wibor,
      commission: formData.value.commission
    }, overpayments.value, []).getScheduleSmallerInstallment();
  }
}

const equalInstallment = () => {
  if (overpaymentType.value === "period") {
    return useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: formData.value.amountOfCredit,
      period: formData.value.period,
      margin: formData.value.margin,
      wibor: formData.value.wibor,
      commission: formData.value.commission
    }, overpayments.value, []).getScheduleShorterPeriod();
  } else {
    return useEqualInstallments({
      date: new Date(2023, 0),
      amountOfCredit: formData.value.amountOfCredit,
      period: formData.value.period,
      margin: formData.value.margin,
      wibor: formData.value.wibor,
      commission: formData.value.commission
    }, overpayments.value, []).getScheduleSmallerInstallment();
  }
}

const v$ = useVuelidate(rules, formData);

const totalCost = ref(0);

const getResult = async () => {
  const result = await v$.value.$validate();

  if (!result) {
    return;
  }

  let creditResult;

  if (formData.value.typeOfInstallment === "rowne") creditResult = equalInstallment();
  if (formData.value.typeOfInstallment === "malejace") creditResult = decreasingInstallment();

  schedule.value = formatHarmonogram(creditResult);
  console.table(formatHarmonogram(creditResult));
  console.log(formattedToPLN.format(totalCreditInterest(creditResult)));
  totalCost.value = totalCreditCost(creditResult);

  console.log(formattedToPLN.format(totalCreditCost(creditResult)));
}

const getOverpayments = (value) => {
  overpayments.value = value;
  localStorage.setItem("overpayment-values", JSON.stringify(overpayments.value));
}

const getType = (value) => {
  overpaymentType.value = value;
  localStorage.setItem("overpayment-type", value);
}

onMounted(() => overpayments.value = JSON.parse(overpaymentsStorage.value));


const getInfo = () => {

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
                  :value="Number(wibor.value)"
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
            <OverpaymentInputsList
              :data="overpaymentsStorage"
              @input-list="getOverpayments"
              @type="getType"
              placeholder="PLN"
            />
          </div>
        </div>
        <button @click="getResult" class="btn btn-primary mt-10 text-white w-full">
          Oblicz
        </button>
      </section>

      <section
        v-if="schedule.length"
        class="flex flex-col gap-y-2"
      >
        <Collapse title="Jaki skutek przyniesie nadpłata kredytu?" :collapsed="true">
          <div v-if="overpaymentType === 'period'">
            <h1>Zmniejszenie raty</h1>
            <div class="flex">
              <p>
                Koszt kredytu: {{ formattedToPLN.format(totalCost) }}
              </p>
              <p>Oszczędność na całym kredycie</p>
            </div>
          </div>
          <div v-if="overpaymentType === 'installment'">
            mniejsza rata
          </div>
        </Collapse>

        <Collapse title="Harmonogram" :collapsed="false">
          <CreditScheduleOverpayment :schedule="schedule"/>
        </Collapse>
      </section>
    </template>
  </Layout>
</template>
