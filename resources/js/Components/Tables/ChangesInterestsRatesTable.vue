<script setup>
import {useHelpers} from "@/Composables/useHelpers";
import {onMounted, ref} from "vue";
import {useCreditCalculation} from "@/Composables/useCreditCalculation";

const {
  totalCreditCost,
  totalCreditInterest,
  getPaidInterestToIndex,
  getPaidCapitalToIndex,
  formattedToPLN
} = useHelpers();

const props = defineProps({
  schedule: Object,
  credit: Object
});

const result = ref([]);

const interestRateChanges = [
  -5.00, -4.00, -3.00, -2.00, -1.75, -1.50, -1.25, -1.00, -0.75, -0.50, -0.25,
  0.25, 0.55, 0.75, 1.00, 1.25, 1.50, 1.75, 2.00, 3.00, 4.00, 5.00
];

const defaultResult = ref({
  interest: props.credit.margin + Number(props.credit.wibor),
  installment: props.schedule[0][5],
  creditInterests: props.schedule[0][3],
  creditCapital: props.schedule[0][4],
  creditInterest10years: getPaidInterestToIndex(props.schedule, 12 * 10),
  creditCapital10years: getPaidCapitalToIndex(props.schedule, 12 * 10),
  totalCreditInterest: totalCreditInterest(props.schedule),
  totalCreditCost: totalCreditCost(props.schedule)
});


onMounted(async () => {
  await calc();
  result.value.splice(11, 0, defaultResult.value);
});

async function calc() {
  for (let i = 0; i < 22; i++) {
    let newSchedule = await scheduleCalculate(i);

    result.value.push({
      interest: {
        value: props.credit.margin + Number(props.credit.wibor) + interestRateChanges[i],
        change: props.credit.margin + Number(props.credit.wibor) + interestRateChanges[i] - defaultResult.value.interest
      },
      installment: {
        value: newSchedule[0][5],
        change: changeCalc(defaultResult.value.installment, newSchedule[0][5])
      },
      creditInterests: {
        value: newSchedule[0][3],
        change: changeCalc(defaultResult.value.creditInterests, newSchedule[0][3])
      },
      creditCapital: {
        value: newSchedule[0][4],
        change: changeCalc(defaultResult.value.creditCapital, newSchedule[0][4])
      },
      creditInterest10years: {
        value: getPaidInterestToIndex(newSchedule, 12 * 10),
        change: changeCalc(
            getPaidInterestToIndex(props.schedule, 12 * 10),
            getPaidInterestToIndex(newSchedule, 12 * 10))
      },
      creditCapital10years: {
        value: getPaidCapitalToIndex(newSchedule, 12 * 10),
        change: changeCalc(
            getPaidCapitalToIndex(props.schedule, 12 * 10),
            getPaidCapitalToIndex(newSchedule, 12 * 10))
      },
      totalCreditInterest: {
        value: totalCreditInterest(newSchedule),
        change: changeCalc(totalCreditInterest(props.schedule), totalCreditInterest(newSchedule))
      },
      totalCreditCost: {
        value: totalCreditCost(newSchedule),
        change: changeCalc(totalCreditCost(props.schedule), totalCreditCost(newSchedule))
      }
    });
  }
}

const {getSchedulev2} = useCreditCalculation();

async function scheduleCalculate(index) {
  const defaultRes = await getSchedulev2(
      props.credit.typeOfInstallment,
      props.credit.date,
      props.credit.amountOfCredit,
      props.credit.period,
      'year',
      props.credit.margin,
      Number(props.credit.wibor) + interestRateChanges[index],
      props.credit.commission,
      'percent'
  );

  return defaultRes.data.schedule;
}

function changeCalc(oldValue, newValue) {
  return oldValue - newValue;
}

</script>

<template>
  <div class="overflow-x-auto relative">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr class="bg-gray-200 text-center">
        <th scope="col" class="py-3">%</th>
        <th scope="col" class="py-3">Rata</th>
        <th scope="col" class="py-3">Odsetki</th>
        <th scope="col" class="py-3">Kapitał</th>
        <th scope="col" class="py-3">Odsetki 10 lat</th>
        <th scope="col" class="py-3">Kapitał 10 lat</th>
        <th scope="col" class="py-3">Odsetki suma</th>
        <th scope="col" class="py-3">Koszt całkowity</th>
      </tr>
      </thead>
      <tbody>
      <tr
          v-for="(row, index) in result"
          :key="index"
          class="bg-white border-b hover:bg-gray-50 text-center"
          :class="row.interest.value == null ? 'bg-blue-200 hover:bg-blue-600 hover:text-white' : ''"
      >
        <td class="whitespace-nowrap p-3">
          <p class="font-medium text-gray-900">
            {{ Math.round((row.interest.value ?? defaultResult.interest) * 100) / 100 }} %</p>
          <p
              v-if="row.interest.value"
              :class="Number(row.interest.change) < 0 ? 'text-xs text-green-600 font-medium' : 'text-xs text-red-600 font-medium'"
          >
            {{ Number(row.interest.change).toFixed(2) }} pp</p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.installment.value ?? defaultResult.installment) }}</p>
          <p
              v-if="row.installment.value"
              class="text-xs font-semibold"
              :class="Number(row.installment.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.installment.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.installment.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round((row.installment.change / defaultResult.installment) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.creditInterests.value ?? defaultResult.creditInterests) }}</p>
          <p
              v-if="row.creditInterests.value"
              class="text-xs font-semibold"
              :class="Number(row.creditInterests.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.creditInterests.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.creditInterests.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round((row.creditInterests.change / defaultResult.creditInterests) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.creditCapital.value ?? defaultResult.creditCapital) }}</p>
          <p
              v-if="row.creditCapital.value"
              class="text-xs font-semibold"
              :class="Number(-row.creditCapital.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.creditCapital.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(-row.creditCapital.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round(-(row.creditCapital.change / defaultResult.creditCapital) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.creditInterest10years.value ?? defaultResult.creditInterest10years) }}</p>
          <p
              v-if="row.creditInterest10years.value"
              class="text-xs font-semibold"
              :class="Number(row.creditInterest10years.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.creditInterest10years.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.creditInterest10years.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round((row.creditInterest10years.change / defaultResult.creditInterest10years) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.creditCapital10years.value ?? defaultResult.creditCapital10years) }}</p>
          <p
              v-if="row.creditCapital10years.value"
              class="text-xs font-semibold"
              :class="Number(-row.creditCapital10years.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.creditCapital10years.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.creditCapital10years.change) < 0 ? 'bg-green-200' : 'bg-red-200'">
            {{ Math.round(-(row.creditCapital10years.change / defaultResult.creditCapital10years) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.totalCreditInterest.value ?? defaultResult.totalCreditInterest) }}</p>
          <p
              v-if="row.totalCreditInterest.value"
              class="text-xs font-semibold"
              :class="Number(row.totalCreditInterest.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.totalCreditInterest.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.totalCreditInterest.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round((row.totalCreditInterest.change / defaultResult.totalCreditInterest) * 100) }}%
          </span></p>
        </td>
        <td>
          <p>{{ formattedToPLN.format(row.totalCreditCost.value ?? defaultResult.totalCreditCost) }}</p>
          <p
              v-if="row.totalCreditCost.value"
              class="text-xs font-semibold"
              :class="Number(row.totalCreditCost.change) < 0 ? 'text-red-700' : 'text-green-700'"
          >{{ formattedToPLN.format(-row.totalCreditCost.change) }}
            <span class="p-1 rounded-lg text-xs ml-2"
                  :class="Number(row.totalCreditCost.change) < 0 ? 'bg-red-200' : 'bg-green-200'">
            {{ Math.round((row.totalCreditCost.change / defaultResult.totalCreditCost) * 100) }}%
          </span></p>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>
