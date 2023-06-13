<script setup>
import {defineProps, ref} from "vue";
import RowLink from "@/Components/RowLink.vue";
import { PaperAirplaneIcon } from "@heroicons/vue/24/outline";

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
          <td class="text-center">
            <RowLink :href="route('admin.users.edit', user.id)">
              <PaperAirplaneIcon class="h-6 w-6 text-gray-500" />
            </RowLink>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
