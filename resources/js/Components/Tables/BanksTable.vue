<script setup>
import {defineProps, ref, watch} from "vue"
import {Link} from "@inertiajs/inertia-vue3"
import {EllipsisHorizontalIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
  banks: Object
});

const emit = defineEmits(['massDelete']);

const checked = ref([]);
const checkedAll = ref(false);

watch(checked, () => {
  emit('massDelete', checked);
}, {deep: true})

watch(checkedAll, (value) => {
  if (value) {
    props.banks.data.forEach((credit) => {
      checked.value.push(credit.id);
    });
  }

  if (!value) {
    props.banks.data.forEach(() => {
      checked.value = [];
    });
  }
});
</script>

<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500 ">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
      <tr>
        <th scope="col" class="p-4">
          <div class="flex items-center">
            <input
              @click="checkedAll = !checkedAll"
              v-model="checkedAll"
              id="checkbox-all-search"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
            <label for="checkbox-all-search" class="sr-only">checkbox</label>
          </div>
        </th>
        <th scope="col" class="py-3 px-6">
          Nazwa banku
        </th>
        <th scope="col" class="py-3 px-6">
          Ścieżka(logo)
        </th>
        <th scope="col" class="py-3 px-6">
          Logo
        </th>
        <th scope="col" class="py-3 px-6 text-center">
          Akcje
        </th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="bank in banks.data"
          :key="bank.id"
          class="bg-white border-b">
        <td class="p-4 w-4">
          <div class="flex items-center">
            <input
              :value="bank.id"
              v-model="checked"
              id="checkbox-table-search-1"
              type="checkbox"
              class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500"
            >
            <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
          </div>
        </td>
        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap ">
          {{ bank.bank_name }}
        </th>
        <td class="py-4 px-6">
          {{ bank.logo_path }}
        </td>
        <td>
          <img :src="bank.logo_path" alt="logo" width="120" height="53px">
        </td>
        <td class="text-center">
          <Link :href="route('admin.banks.edit', bank.id)">
            <EllipsisHorizontalIcon class="h-10 w-10 text-gray-700"/>
          </Link>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

