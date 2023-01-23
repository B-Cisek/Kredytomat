<script setup>
import Datepicker from '@vuepic/vue-datepicker';
import {XCircleIcon} from "@heroicons/vue/24/outline";
import '@vuepic/vue-datepicker/dist/main.css'
import {ref} from "vue";

const date = ref({
  start: {
    month: new Date().getMonth(),
    year: new Date().getFullYear()
  },
  end: {
    month: new Date().getMonth(),
    year: new Date().getFullYear()
  }
});

const overpayment = ref(null);

const remove = () => {

}

</script>

<template>
  <div class="flex gap-6">
    <Datepicker v-model="date.start" month-picker locale="pl" auto-apply></Datepicker>
    <Datepicker v-model="date.end" month-picker locale="pl" auto-apply></Datepicker>
    <input v-model="overpayment" class="border-gray-200 h-10 rounded-md" type="number" placeholder="PLN">
    <button>
      <XCircleIcon class="h-6 w-6"/>
    </button>
  </div>
</template>
