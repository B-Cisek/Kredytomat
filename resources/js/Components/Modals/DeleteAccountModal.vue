<script setup>
import {useForm} from "@inertiajs/inertia-vue3";

const form = useForm({
  password: ""
})

const deleteAccount = () => {
  form.post(route('profil.delete'), {
    preserveScroll: true
  });
}

</script>

<template>
  <div
    class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-50">
    <div class="fixed inset-0 transform transition-all">
      <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>
    <div
      class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full sm:mx-auto sm:max-w-2xl">
      <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">Usuń konto</div>
        <div class="mt-4 text-sm text-gray-600">
          Czy na pewno chcesz usunąć swoje konto? Kiedy twoje konto jest
          usunięte, wszystkie jego zasoby i dane zostaną trwale usunięte. Wprowadź hasło, aby potwierdzić
          chce trwale usunąć swoje konto.
          <div class="mt-4">
            <input
              class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-3/4"
              type="password"
              placeholder="Hasło"
              name="password"
              id="password"
              v-model="form.password"
            >
            <div v-show="form.errors.password" class="mt-2">
              <p class="text-sm text-red-600">{{ form.errors.password }}</p>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
        <button
          @click="$emit('close')"
          type="button"
          class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
          Cofnij
        </button>
        <button
          @click="deleteAccount"
          type="button"
          class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 ml-3">
          Usuń konto
        </button>
      </div>
    </div>
  </div>
</template>
