<script setup>
import Layout from "@/Layouts/Layout.vue";
import {useHelpers} from "@/Composables/useHelpers";
import ConfirmationModal from "@/Components/Modals/ConfirmationModal.vue";
import {Inertia} from "@inertiajs/inertia";
import {ref} from "vue";

const {formattedToPLN} = useHelpers();

const props = defineProps({
  overpaymentSimulation: Object
});

const downloadPdf = () => {

}

const remove = () => {
  confirmationModalOpen.value = false;
  Inertia.delete(route('profil.overpayment.destroy', props.overpaymentSimulation));
}

const confirmationModalOpen = ref(false);
</script>

<template>
  <Layout>
    <template v-slot:header>Symulacja kredytowa</template>
    <template v-slot:default>
      <ConfirmationModal
        v-if="confirmationModalOpen"
        @close="confirmationModalOpen = false"
        @submit="remove"
        title="Usuwanie symulacji"
        description="Czy napewno chcesz usunąć symulacje nadpłaty kredytu?"
        confirm-button-text="Usuń"
      />
      <div class="w-full rounded-lg shadow-md border border-gray-200 bg-white p-5">
        <div class="w-full flex items-center">
          <div class="text-sm text-gray-900 flex-1">
            <div class="divide-y divide-gray-200 rounded-md border border-gray-200">
              <div class="flex items-center justify-between py-3 pl-3 pr-4 text-sm">
                <div class="flex w-0 flex-1 items-center">
                  <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                       aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                          clip-rule="evenodd"/>
                  </svg>
                  <span class="ml-2 w-0 flex-1 truncate">resume_back_end_developer.pdf</span>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="downloadPdf" class="font-medium text-indigo-600 hover:text-indigo-500">Pobierz
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="flex gap-3 ml-3">
            <button class="hover:opacity-75">
              <svg xmlns="http://www.w3.org/2000/svg" width="46px" height="46px">
                <path fill="#7cb342" d="M24 3A21 21 0 1 0 24 45A21 21 0 1 0 24 3Z"/>
                <path fill="#dcedc8"
                      d="M24,36.1c-6.6,0-12-5.4-12-12c0-3.6,1.6-7,4.4-9.3l2.5,3.1c-1.8,1.5-2.9,3.8-2.9,6.2c0,4.4,3.6,8,8,8 s8-3.6,8-8c0-2.1-0.8-4-2.2-5.5l2.9-2.7C34.8,18,36,21,36,24.1C36,30.7,30.6,36.1,24,36.1z"/>
                <path fill="#dcedc8" d="M12 13L21 13 21 22z"/>
              </svg>
            </button>
            <button @click="confirmationModalOpen = true">
              <img class="h-[46px] hover:opacity-75"
                   src="https://img.icons8.com/external-smashingstocks-circular-smashing-stocks/100/null/external-delete-webmobile-applications-smashingstocks-circular-smashing-stocks.png" alt=""/>
            </button>
          </div>
        </div>
      </div>
      <section id="pdf-export">
        <div class="w-full rounded-lg border border-gray-200 bg-white">
          <div class="flex items-center justify-between cursor-pointer p-5 w-full">
            <h1 class="font-semibold text-xl">Twoje wyniki</h1>
          </div>
        </div>
        <div>
          <div class="bg-[#21a142] px-5 pt-5 rounded-t text-white flex">
            <div class="flex flex-col justify-between flex-1 gap-3">
              <div>
                <p class="mt-1">Kwota kredytu:</p>
                <span class="text-xl font-semibold">{{ formattedToPLN.format(overpaymentSimulation.amount_of_credit) }}</span>
              </div>
              <div>
                <p class="mt-1">Okres spłaty:</p>
                <span class="text-xl font-semibold">{{ overpaymentSimulation.period }} lat</span>
              </div>
              <div>
                <p class="mt-1">Oprocentowanie:</p>
                <p class="text-xl font-semibold">{{ Number(overpaymentSimulation.wibor.value) + Number(overpaymentSimulation.margin) }}% <span
                  class="text-sm font-normal">(WIBOR {{ overpaymentSimulation.wibor.value }}% + marża {{ overpaymentSimulation.margin }}%)</span></p>
              </div>
            </div>
            <div class="flex flex-col justify-between items-end text-right flex-1">
              <div>
                <p class="mt-1">Rodzaj raty:</p>
                <span class="text-xl font-semibold">
                  {{ overpaymentSimulation.type_of_installment === 'rowne' ? 'Równe' : 'Malejące' }}</span>
              </div>
              <div>
                <p class="mt-1">WIBOR:</p>
                <span class="text-xl font-semibold">3M</span>
              </div>
              <div>
                <p class="mt-1">Prowizja:</p>
                <span class="text-xl font-semibold">{{ overpaymentSimulation.commission }}%</span>
              </div>
            </div>
          </div>
          <div class="bg-[#21a142] px-5 text-white pb-5 pt-3 rounded-b-md flex">
            <div class="flex-1">
              <p>Nadpłaty:</p>
              <ul class="list-disc ml-5">
                <li
                  class="text-xl font-semibold"
                  v-for="(overpayment, index) in JSON.parse(props.overpaymentSimulation.overpayments)"
                  :key="index"
                >{{ overpayment.start.month + 1 }}/{{ overpayment.start.year }} -
                  {{ overpayment.end.month + 1 }}/{{ overpayment.end.year }}:
                  {{ formattedToPLN.format(overpayment.overpayment) }}
                </li>
              </ul>
            </div>
            <div class="text-right">
              <p class="mt-1">Nadpłata na:</p>
              <span class="text-xl font-semibold">
                {{ overpaymentSimulation.overpayment_type === 'period' ? 'Skrócenie okresu kredytowania' : 'Zminiejszenie raty' }}
              </span>
            </div>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>

