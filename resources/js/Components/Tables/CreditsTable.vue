<script setup>
import { Link } from "@inertiajs/inertia-vue3";
import { ref, watch } from "vue";

const props = defineProps({
  credits: Object,
});

// const emit = defineEmits(["checkedCredits", "showButton"]);

const checked = ref([]);
const checkedAll = ref(false);

const selectAll = () => {
  checkedAll.value = !checkedAll.value;
  //   emit("checkedCredits", checked);
  //   emit("showButton", true);
};

watch(checkedAll, (value) => {
  if (value) {
    props.credits.data.forEach((credit) => {
      checked.value.push(credit.id);
    });
  }

  if (!value) {
    props.credits.data.forEach(() => {
      checked.value = [];
    });
  }
});
</script>

<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th scope="col" class="p-4">
            <div class="flex items-center">
              <input
                @click="checkedAll = !checkedAll"
                v-model="checkedAll"
                id="checkbox-all-rows"
                type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500"
              />
              <label for="checkbox-all-search" class="sr-only">checkbox</label>
            </div>
          </th>
          <th scope="col" class="py-3">Nazwa kredytu</th>
          <th scope="col" class="py-3">Bank</th>
          <th scope="col" class="py-3">Marża</th>
          <th scope="col" class="py-3">Prowizja</th>
          <th scope="col" class="py-3">WIBOR</th>
          <th scope="col" class="py-3">Ostatnia zmiana</th>
          <th scope="col" class="py-3 px-6">Akcje</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="credit in credits.data"
          :key="credit.id"
          class="bg-white border-b hover:bg-gray-50"
        >
          <td class="p-4 w-4">
            <div class="flex items-center">
              <input
                :value="credit.id"
                v-model="checked"
                id="checkbox-row"
                type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500"
              />
              <label for="checkbox-row" class="sr-only">checkbox</label>
            </div>
          </td>
          <td class="font-medium text-gray-900 whitespace-nowrap">
            {{ credit.credit_name }}
          </td>
          <td>
            {{ credit.bank.bank_name }}
          </td>
          <td>
            {{ credit.margin }}
          </td>
          <td>
            {{ credit.commission }}
          </td>
          <td>
            {{ credit.wibor.value }}
          </td>
          <td>
            {{ credit.updated_at }}
          </td>
          <td class="flex items-center py-4 px-6 space-x-3">
            <Link :href="route('admin.credits.edit', credit.id)">
              <svg
                class="block w-6 h-6 fill-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <polygon
                  points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"
                />
              </svg>
            </Link>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
