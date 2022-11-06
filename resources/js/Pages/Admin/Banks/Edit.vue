<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputFile from "@/Components/InputFile.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
  bank: Object,
});

const form = useForm({
  bank_name: props.bank.bank_name,
  logo: File,
  _method: "patch",
});

const destroy = (id) => {
  if (confirm("Czy chcesz usunąć bank?")) {
    form.delete(route("admin.banks.destroy", id));
  }
};

const update = () => {
  form.post(route("admin.banks.update", props.bank.id));
};
</script>

<template>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard /
      </Link>
      <Link :href="route('admin.banks.index')" class="hover:text-indigo-700"
        >Banki /
      </Link>
      <span class="font-light">Edycja banku</span>
    </template>

    <template #default>
      <div class="bg-white rounded-lg p-5 lg:flex justify-between gap-3">
        <div class="lg:w-2/4">
          <h1 class="font-bold text-2xl">{{ bank.bank_name }}</h1>
          <form @submit.prevent="update" class="mt-7">
            <div class="flex-col">
              <div>
                <InputLabel
                  for="name"
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
                  model-value="form.bank_name"
                  v-model="form.bank_name"
                  required
                  autocomplete="bank_name"
                />
                <InputError class="mt-2" :message="form.errors.bank_name" />
              </div>
              <div class="mt-3">
                <InputLabel
                  for="logo"
                  value="Logo"
                  :class="form.errors.logo ? 'text-red-700' : ''"
                />
                <InputFile
                  @input="form.logo = $event.target.files[0]"
                  :class="form.errors.logo ? 'w-full border-red-700' : 'w-full'"
                />
                <InputError class="mt-2" :message="form.errors.logo" />
              </div>
              <div class="mt-7 flex justify-between">
                <PrimaryButton type="submit" class="">Zmień</PrimaryButton>
                <PrimaryButton @click="destroy(bank.id)" type="button" class="bg-red-700"
                  >Usuń
                </PrimaryButton>
              </div>
            </div>
          </form>
        </div>
        <div class="lg:w-2/4">
          <h2>Aktualne logo</h2>
          <div class="border w-[580px] h-[250px]">
            <img :src="props.bank.logo_path" alt="logo" class="max-w-full max-h-full" />
          </div>
        </div>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
