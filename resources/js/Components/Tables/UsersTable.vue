<script setup>
import {defineProps, ref} from "vue";
import RowLink from "@/Components/RowLink.vue";

defineProps({
  users: Object,
});

const usersMassDelete = ref([]);

</script>

<template>
  <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left text-gray-500">
      <thead class="text-xs text-gray-700 uppercase bg-gray-50">
        <tr>
          <th scope="col" class="p-4">
            <div class="flex items-center">
              <input
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
          <th scope="col" class="py-3 px-6">Akcje</th>
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
                v-model="usersMassDelete[user.id]"
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
          <td class="flex items-center space-x-3">
            <RowLink :href="route('admin.users.edit', user.id)">
              <svg
                class="block w-6 h-6 fill-gray-400"
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 20 20"
              >
                <polygon
                  points="12.95 10.707 13.657 10 8 4.343 6.586 5.757 10.828 10 6.586 14.243 8 15.657 12.95 10.707"
                />
              </svg>
            </RowLink>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
