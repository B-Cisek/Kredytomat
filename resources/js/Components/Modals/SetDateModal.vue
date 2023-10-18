<script setup>
import {CalendarDaysIcon} from "@heroicons/vue/24/outline";
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import {ref} from "vue";

const props = defineProps({
  date: Object
});

const date = ref({
  year: props.date.year,
  month: props.date.month
});

</script>

<template>
  <div class="z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-to-class="opacity-0"
        leave-from-class="opacity-100"
        leave-active-class="ease-in duration-200"
    >
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    </Transition>
    <div class="fixed inset-0 z-10 overflow-y-auto">
      <div class="flex min-h-full items-end justify-center p-4 text-center items-center sm:p-0">
        <Transition
            enter-to-class="opacity-100 translate-y-0 sm:scale-100"
            enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-active-class="ease-out duration-300"
            leave-active-class="ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
          <!-- main div-->
          <div
              class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="flex flex-col gap-5">
                <div class="mx-auto flex gap-3 h-12 flex-shrink-0 items-center justify-center sm:mx-0 sm:h-15 sm:w-15">
                  <CalendarDaysIcon class="h-10 w-10 text-gray-700"/>
                  <h3 class="text-base font-semibold leading-6 text-gray-900">Data rozpoczęcia kredytu</h3>
                </div>
                <Datepicker
                    v-model="date"
                    month-picker
                    locale="pl"
                    auto-apply
                />
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
              <button
                  @click="$emit('changeDate', date)"
                  type="button"
                  class="inline-flex w-full justify-center rounded-md bg-indigo-700 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 sm:ml-3 sm:w-auto">
                Zapisz
              </button>
              <button
                  @click="$emit('close')"
                  type="button"
                  class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                Anuluj
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </div>
  </div>
</template>

