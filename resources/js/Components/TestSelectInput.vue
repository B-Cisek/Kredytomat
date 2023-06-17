<script setup>
import {computed, ref, watch} from "vue";

const props = defineProps({
  heading: String,
  selectedType: String,
  commission: {
    type: Number,
    required: true
  },
  commissionType: {
    type: String,
    required: true
  }
});

const min = ref(0);
const max = ref(props.commissionType === "percent" ? 7 : 10000);
const step = ref(props.commissionType === "percent" ? 0.1 : 1);

const emit = defineEmits(['update:commission', 'update:commissionType']);

const commissionValue = computed({
  get() {
    return props.commission;
  },
  set(newValue) {
    emit('update:commission', newValue)
  }
});

const commissionTypeValue = computed({
  get() {
    return props.commissionType;
  },
  set(newValue) {
    emit('update:commissionType', newValue)
  }
});

watch(commissionTypeValue, newValue => {
  if (newValue === "number") {
    min.value = 0;
    max.value = 10000;
    step.value = 1;
    commissionValue.value = (commissionValue.value * 250000) / 100;
    commissionTypeValue.value = "number";
  }
});

watch(commissionTypeValue, newValue => {
  if (newValue === "percent") {
    min.value = 0;
    max.value = 7;
    step.value = 0.1;
    commissionValue.value = (commissionValue.value / 250000) * 100;
    commissionTypeValue.value = "percent";
  }
});
</script>

<template>
  <div>
    <div className="flex mb-3 items-center justify-between">

      <h3 className="font-semibold text-black">{{ heading }}</h3>

      <div className="relative">
        <input
          v-model="commission"
          type="number"
          class="border-2 border-gray-300 focus:border-indigo-700 focus:outline-none focus:shadow-none font-semibold input outline-none sm:w-full w-[180px]"
        />
        <select
          v-model="commissionType"
          class="appearance-none cursor-pointer absolute right-0 w-25 bg-indigo-700 h-full inline-flex items-center justify-center rounded-r-lg font-semibold text-white">
          <option selected value="number">PLN</option>
          <option value="percent">%</option>
        </select>
      </div>
    </div>

    <input
      v-model.number="commission"
      type="range"
      :min="min"
      :max="max"
      :step="step"
      class="range range-primary bg-[#d1d3d9]"
    />

    <label className="label">
      <span className="label-text-alt text-black">{{ min }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
      <span className="label-text-alt text-black">{{ max }} {{ commissionType == 'percent' ? '%' : 'zł' }}</span>
    </label>
  </div>
</template>
