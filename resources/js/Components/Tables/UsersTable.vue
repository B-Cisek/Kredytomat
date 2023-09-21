<script setup>
import {defineProps, ref, watch} from "vue";
import RowLink from "@/Components/RowLink.vue";
import {EllipsisHorizontalIcon} from "@heroicons/vue/24/outline";

const props = defineProps({
  users: Object,
});

const emit = defineEmits(['massDelete']);

const checked = ref([]);
const checkedAll = ref(false);

watch(checked, () => {
  emit('massDelete', checked);
}, {deep: true})

watch(checkedAll, (value) => {
  if (value) {
    props.users.data.forEach((credit) => {
      checked.value.push(credit.id);
    });
  }

  if (!value) {
    props.users.data.forEach(() => {
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
                id="checkbox-all-search"
                type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500"
              />
              <label for="checkbox-all-search" class="sr-only">checkbox</label>
            </div>
          </th>
          <th scope="col" class="py-3">Nazwa użytkownika</th>
          <th scope="col" class="py-3">Email</th>
          <th scope="col" class="py-3">Konto utworzone</th>
          <th scope="col" class="py-3">Ostatnia zmiana</th>
          <th scope="col" class="py-3 text-center">Akcje</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="user in users.data"
          :key="user.id"
          class="bg-white border-b hover:bg-gray-50"
        >
          <td class="p-4 w-4">
            <div class="flex items-center">
              <input
                :value="user.id"
                v-model="checked"
                type="checkbox"
                class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500"
              />
              <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
            </div>
          </td>
          <td class="font-medium text-gray-900 whitespace-nowrap">
            {{ user.name }}
          </td>
          <td>
            {{ user.email }}
          </td>
          <td>
            {{ user.created_at }}
          </td>
          <td>
            {{ user.updated_at }}
          </td>
          <td class="text-center">
            <RowLink :href="route('admin.users.edit', user.id)">
              <EllipsisHorizontalIcon class="h-10 w-10 text-gray-700" />
            </RowLink>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
