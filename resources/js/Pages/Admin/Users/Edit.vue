<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { useForm } from "@inertiajs/inertia-vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  user: Object,
});

const form = useForm({
  name: props.user.name,
  email: props.user.email,
});

const destroy = (id) => {
  if (confirm("Czy chcesz usunąć użytkownika?")) {
    Inertia.delete(route("admin.users.destroy", id));
  }
};

const update = () => {
  form.put(route("admin.users.update", props.user.id));
};

// TODO: Send link to reset password
const resetPassword = () => {};
</script>
<template>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.users.index')" class="hover:text-indigo-700"
        >Użytkownicy /
      </Link>
      <span class="font-light">Edycja użytkownika</span>
    </template>

    <template #default>
      <div class="bg-white rounded-lg p-5">
        <h1 class="font-bold text-2xl text-center">{{ user.name }}</h1>
        <div class="flex justify-between font-medium">
          <div>
            <p>Stworzony:</p>
            <h1>{{ user.created_at }}</h1>
          </div>
          <div>
            <p>Ostatnia zmiana:</p>
            <h1>{{ user.updated_at }}</h1>
          </div>
        </div>
        <form @submit.prevent="update">
          <div class="flex justify-center gap-5 mt-8">
            <div>
              <InputLabel for="name" value="Nazwa użytkownika" />
              <TextInput
                id="name"
                type="text"
                model-value="form.name"
                class="mt-1 block w-full"
                v-model="form.name"
                required
                autocomplete="name"
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
              <InputLabel for="email" value="Email" />
              <TextInput
                id="email"
                type="email"
                class="mt-1 block w-full"
                v-model="form.email"
                required
                autocomplete="email"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>
          </div>
          <div class="flex mt-4 justify-between">
            <div>
              <PrimaryButton
                @click="destroy(user.id)"
                type="button"
                class="bg-red-700 mr-2"
                >Usuń
              </PrimaryButton>
              <Link
                class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
              >
                Resetuj hasło
              </Link>
            </div>
            <PrimaryButton type="submit ">Zmień</PrimaryButton>
          </div>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
