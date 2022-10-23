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
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard /
      </Link>
      <Link :href="route('admin.users.index')" class="hover:text-indigo-700"
        >Użytkownicy /
      </Link>
      <span class="font-light">Edycja użytkownika</span>
    </template>

    <template #default>
      <div class="w-full bg-white p-5 shadow-md sm:rounded-lg">
        <div>
          <h1 class="font-bold text-2xl">{{ user.name }}</h1>
          <div class="flex-col font-medium mt-5">
            <div class="mb-3">
              <p class="font-normal">Stworzony:</p>
              <h1 class="font-semibold">{{ user.created_at }}</h1>
            </div>
            <div>
              <p class="font-normal">Ostatnia zmiana:</p>
              <h1 class="font-semibold">{{ user.updated_at }}</h1>
            </div>
          </div>
        </div>
        <form @submit.prevent="update">
          <div class="flex-col mt-8">
            <div class="mb-4">
              <InputLabel
                for="name"
                value="Nazwa użytkownika"
                :class="form.errors.name ? 'text-red-700' : ''"
              />
              <TextInput
                id="name"
                type="text"
                model-value="form.name"
                v-model="form.name"
                required
                autocomplete="name"
                :class="
                  form.errors.name
                    ? 'mt-1 block w-full border-red-700'
                    : 'mt-1 block w-full'
                "
              />
              <InputError class="mt-2" :message="form.errors.name" />
            </div>
            <div>
              <InputLabel
                for="email"
                value="Email"
                :class="form.errors.email ? 'text-red-700' : ''"
              />
              <TextInput
                id="email"
                type="email"
                :class="
                  form.errors.email
                    ? 'mt-1 block w-full border-red-700'
                    : 'mt-1 block w-full'
                "
                v-model="form.email"
                required
                autocomplete="email"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>
          </div>
          <div class="flex mt-4 justify-between">
            <PrimaryButton type="submit ">Zmień</PrimaryButton>
            <div>
              <Link
                class="inline-flex items-center px-4 py-2 bg-indigo-700 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray transition ease-in-out duration-150"
              >
                Resetuj hasło
              </Link>
              <PrimaryButton
                @click="destroy(user.id)"
                type="button"
                class="bg-red-700 ml-2"
                >Usuń
              </PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>