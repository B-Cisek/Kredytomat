<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Link} from "@inertiajs/inertia-vue3";
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN} = useHelpers();
const props = defineProps({
  creditSimulations: Object
});

console.log(props.creditSimulations)
</script>

<template>
  <Layout>
    <template v-slot:header>Zapisane Kalkulacje kredytowe</template>
    <template v-slot:default>
      <div class="w-full">
        <div class="flex flex-wrap -mx-4">
          <div class="w-full px-4">
            <div class="max-w-full overflow-x-auto rounded-lg">
              <table class="table-auto w-full shadow-md">
                <thead>
                <tr class="bg-[#4338ca] text-center">
                  <th
                    class=" w-1/9 text-lg font-semibold text-white py-3 px-3 lg:px-4 border-l border-transparent">
                    ID
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    Kwota kredytu
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    Okres
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    Marża
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    Prowizja
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    WIBOR
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4">
                    Typ rat
                  </th>
                  <th class="w-1/9 text-lg font-semibold text-white px-3 lg:px-4"></th>
                </tr>
                </thead>
                <tbody>
                <tr
                  v-for="credit in props.creditSimulations.data"
                  :key="credit.id"
                >
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                    {{ credit.id }}
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-white border-b border-l border-[#E8E8E8]">
                    {{ formattedToPLN.format(credit.amount_of_credit) }}
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                    {{ credit.period }} lat
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-white border-b border-l border-[#E8E8E8]">
                    {{ credit.margin }}%
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                    {{ credit.commission }}%
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-white border-b border-l border-[#E8E8E8]">
                    {{ credit.wibor.type }}
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-1 px-2 bg-[#F3F6FF] border-b border-l border-[#E8E8E8]">
                    {{ credit.type_of_installment === 'rowne' ? 'Równe' : 'Malejące' }}
                  </td>
                  <td
                    class="text-center text-dark font-medium text-base py-2 px-2 bg-white border-b border-r border-[#E8E8E8]">
                    <Link
                      :href="route('profil.credit.show', credit.id)"
                      class="border border-primary py-1 px-6 text-primary inline-block rounded hover:bg-primary hover:text-white"
                    >
                      Pokaż
                    </Link>
                  </td>
                </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="flex justify-between pt-2">
          <div>
            <Link :href="props.creditSimulations.prev_page_url" class="inline-flex items-center px-4 py-2 mr-3 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700">
              <svg aria-hidden="true" class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.707 14.707a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 010 1.414z" clip-rule="evenodd"></path></svg>
              Poprzednia
            </Link>
          </div>
          <div>
            <Link :href="props.creditSimulations.next_page_url" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 hover:text-gray-700">
              Następna
              <svg aria-hidden="true" class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </Link>
          </div>
        </div>

      </div>
    </template>
  </Layout>
</template>
