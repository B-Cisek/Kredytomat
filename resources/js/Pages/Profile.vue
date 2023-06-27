<script setup>
import Layout from "@/Layouts/Layout.vue";
import {Head, useForm, usePage} from "@inertiajs/inertia-vue3";
import {computed, ref} from "vue";
import DeleteAccountModal from "@/Components/Modals/DeleteAccountModal.vue";
import {Inertia} from "@inertiajs/inertia";

const auth = computed(() => usePage().props.value.auth.user);

const showModal = ref(false);

const formProfile = useForm({
  name: auth.value.name,
  email: auth.value.email,
});

const formPassword = useForm({
  current_password: "",
  password: "",
  password_confirmation: "",
});

const updateProfile = () => {
  formProfile.post(route('profil.update'), {
    preserveScroll: true
  })
}

const updatePassword = () => {
  formPassword.post(route('profil.password.update'), {
    preserveScroll: true,
    onSuccess: () => formPassword.reset()
  })
}

</script>

<template>
  <Head title="Profil"/>
  <Layout>
    <template #header>Profil</template>
    <template #default>
      <div class="md:grid md:grid-cols-3 md:gap-6">
        <div class="md:col-span-1 flex justify-between">
          <div class="px-4 sm:px-0">
            <h3 class="text-lg font-medium text-gray-900">Informacje o Profilu</h3>
            <p class="mt-1 text-sm text-gray-600">Zaktualizuj nazwe użytkownika i adres e-mail swojego konta.</p>
          </div>
          <div class="px-4 sm:px-0"></div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <form @submit.prevent="updateProfile">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700"
                         for="name"><span>Nazwa użytkownika</span></label>
                  <input
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    id="name"
                    type="text"
                    autocomplete="name"
                    v-model="formProfile.name"
                  >
                  <div class="mt-2" style="">
                    <p class="text-sm text-red-600">{{ formProfile.errors.name }}</p>
                  </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700"
                         for="email"><span>Email</span></label>
                  <input
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    id="email"
                    type="email"
                    autocomplete="username"
                    v-model="formProfile.email"
                  >
                  <div class="mt-2" style="">
                    <p class="text-sm text-red-600">{{ formProfile.errors.email }}</p>
                  </div>
                </div>
              </div>
            </div>
            <div
              class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
              <button type="submit"
                      class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Zapisz
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="hidden sm:block">
        <div class="py-8">
          <div class="border-t border-gray-200"></div>
        </div>
      </div>
      <div class="md:grid md:grid-cols-3 md:gap-6 mt-10 sm:mt-0">
        <div class="md:col-span-1 flex justify-between">
          <div class="px-4 sm:px-0"><h3 class="text-lg font-medium text-gray-900">Zmień Hasło</h3>
            <p class="mt-1 text-sm text-gray-600">Upewnij się, że Twoje konto używa długiego, losowego hasła dla poprawy
              bezpieczeństwa.</p></div>
          <div class="px-4 sm:px-0"></div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <form @submit.prevent="updatePassword">
            <div class="px-4 py-5 bg-white sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
              <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700"
                         for="current_password"><span>Aktualne hasło</span>
                  </label>
                  <input
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    id="current_password"
                    type="password"
                    autocomplete="current-password"
                    v-model="formPassword.current_password"
                  >
                  <div class="mt-2" style="">
                    <p class="text-sm text-red-600">{{ formPassword.errors.current_password }}</p>
                  </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700"
                         for="password"><span>Nowe hasło</span>
                  </label>
                  <input
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    id="password"
                    type="password"
                    autocomplete="new-password"
                    v-model="formPassword.password"
                  >
                  <div class="mt-2" style="">
                    <p class="text-sm text-red-600">{{ formPassword.errors.password }}</p>
                  </div>
                </div>
                <div class="col-span-6 sm:col-span-4">
                  <label class="block font-medium text-sm text-gray-700"
                         for="password_confirmation">
                    <span>Powtórz hasło</span>
                  </label>
                  <input
                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full"
                    id="password_confirmation"
                    type="password"
                    autocomplete="new-password"
                    v-model="formPassword.password_confirmation"
                  >
                </div>
              </div>
            </div>
            <div
              class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
              <button type="submit"
                      class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Zapisz
              </button>
            </div>
          </form>
        </div>
      </div>
      <div class="hidden sm:block">
        <div class="py-8">
          <div class="border-t border-gray-200"></div>
        </div>
      </div>
      <div class="md:grid md:grid-cols-3 md:gap-6 mt-10 sm:mt-0">
        <div class="md:col-span-1 flex justify-between">
          <div class="px-4 sm:px-0"><h3 class="text-lg font-medium text-gray-900">Usuń konto</h3>
            <p class="mt-1 text-sm text-gray-600">Trwale usuń swoje konto.</p></div>
          <div class="px-4 sm:px-0"></div>
        </div>
        <div class="mt-5 md:mt-0 md:col-span-2">
          <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl text-sm text-gray-600">Po usunięciu konta wszystkie jego zasoby i dane
              zostaną trwale usunięte. Przed usunięciem konta pobierz wszelkie dane lub informacje, które
              chcesz zachować.
            </div>
            <div class="mt-5">
              <button
                @click="showModal = true"
                type="button"
                class="inline-flex items-center justify-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Usuń konto
              </button>
            </div>
          </div>
        </div>
      </div>
      <DeleteAccountModal
        v-if="showModal"
        @close="showModal = false"
      />
    </template>
  </Layout>
</template>
