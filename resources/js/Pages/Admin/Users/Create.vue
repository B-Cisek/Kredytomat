<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { Link } from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
  name: "",
  email: "",
  password: "",
  password_confirmation: "",
});

const store = () => {
  form.post(route("admin.users.store"));
};
</script>

<template>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard >
      </Link>
      <Link :href="route('admin.users.index')" class="hover:text-indigo-700"
        >Użytkownicy >
      </Link>
      <span class="font-light">Nowy użytkownik</span>
    </template>
    <template #default>
      <div class="w-full flex justify-center bg-white p-5 shadow-md sm:rounded-lg">
        <form @submit.prevent="store" class="sm:w-2/4">
          <div class="mb-4">
            <InputLabel
              for="name"
              value="Nazwa użytkownika"
              :class="form.errors.name ? 'text-red-700' : ''"
            />
            <TextInput
              id="name"
              type="text"
              :class="
                form.errors.name
                  ? 'mt-1 block w-full border-red-700'
                  : 'mt-1 block w-full'
              "
              v-model="form.name"
              required
              autocomplete="name"
            />
            <InputError :message="form.errors.name" />
          </div>
          <div class="mb-4">
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
            <InputError :message="form.errors.email" />
          </div>
          <div class="mb-4">
            <InputLabel
              for="password"
              value="Hasło"
              :class="form.errors.password ? 'text-red-700' : ''"
            />
            <TextInput
              id="password"
              type="password"
              :class="
                form.errors.password
                  ? 'mt-1 block w-full border-red-700'
                  : 'mt-1 block w-full'
              "
              v-model="form.password"
              required
            />
            <InputError :message="form.errors.password" />
          </div>
          <div class="mb-4">
            <InputLabel
              for="password_confirmation"
              value="Powtórz Hasło"
              :class="form.errors.password_confirmation ? 'text-red-700' : ''"
            />
            <TextInput
              id="password_confirmation"
              type="password"
              :class="
                form.errors.password_confirmation
                  ? 'mt-1 block w-full border-red-700'
                  : 'mt-1 block w-full'
              "
              v-model="form.password_confirmation"
              required
            />
            <InputError :message="form.errors.password_confirmation" />
          </div>
          <PrimaryButton
            class="w-full text-sm block text-center"
            :disabled="form.processing"
            type="submit"
          >
            Dodaj
          </PrimaryButton>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
