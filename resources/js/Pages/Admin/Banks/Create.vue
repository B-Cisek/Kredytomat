<script setup>
import AdminDashboardLayout from "@/Layouts/AdminLayout.vue";
import {Head, Link, useForm} from "@inertiajs/inertia-vue3";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputFile from "@/Components/InputFile.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Arrow from "@/Components/Arrow.vue";

const form = useForm({
  bank_name: "",
  logo: File,
});

const store = () => {
  form.post(route("admin.banks.store"));
};
</script>

<template>
  <Head title="Nowy bank"/>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700">Dashboard</Link>
      <Arrow />
      <Link :href="route('admin.banks.index')" class="hover:text-indigo-700">Banki</Link>
      <Arrow />
      <span class="font-light">Nowy bank</span>
    </template>
    <template #default>
      <div class="w-full flex justify-center bg-white p-5 shadow-md sm:rounded-lg">
        <form @submit.prevent="store">
          <div class="mb-3">
            <InputLabel
              for="bank_name"
              value="Nazwa banku"
              :class="form.errors.bank_name ? 'text-red-700' : ''"
            />
            <TextInput
              id="bank_name"
              type="text"
              :class="
                form.errors.bank_name
                  ? 'mt-1 block w-full border-red-700'
                  : 'mt-1 block w-full'
              "
              v-model="form.bank_name"
              required
              autocomplete="bank_name"
            />
            <InputError class="mt-2" :message="form.errors.bank_name" />
          </div>
          <div>
            <InputLabel
              for="logo"
              value="Logo"
              :class="form.errors.logo ? 'text-red-700' : ''"
            />
            <InputFile
              @input="form.logo = $event.target.files[0]"
              :class="
                form.errors.logo
                  ? 'mt-1 block w-full border-red-700'
                  : 'mt-1 block w-full'
              "
            />
            <InputError class="mt-2" :message="form.errors.logo" />
          </div>
          <PrimaryButton class="mt-4 w-full" type="submit ">Dodaj</PrimaryButton>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
