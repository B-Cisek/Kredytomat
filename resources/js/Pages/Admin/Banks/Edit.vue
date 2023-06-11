<script setup>
import { Link, useForm } from "@inertiajs/inertia-vue3";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputFile from "@/Components/InputFile.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import {ref} from "vue";
import ConfirmationModal from "@/Components/Modals/ConfirmationModal.vue";

const props = defineProps({
  bank: Object,
});

const confirmationModalOpen = ref(false);

const form = useForm({
  bank_name: props.bank.bank_name,
  logo: File,
  _method: "patch",
});

const destroy = id => {
  form.delete(route("admin.banks.destroy", id));
};

const update = () => {
  form.post(route("admin.banks.update", props.bank.id));
};
</script>

<template>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard >
      </Link>
      <Link :href="route('admin.banks.index')" class="hover:text-indigo-700"
        >Banki >
      </Link>
      <span class="font-light">Edycja banku</span>
    </template>

    <template #default>
      <ConfirmationModal
        v-if="confirmationModalOpen"
        @close="confirmationModalOpen = false"
        @submit="destroy(bank.id)"
        title="Usuwanie banku"
        description="Czy napewno chcesz usunąć bank?"
        confirm-button-text="Usuń"
      />
      <div class="bg-white rounded-lg p-5">
          <div class="w-full flex flex-col px-48">
            <div class="flex justify-between items-center">
              <h1 class="font-bold text-2xl text-center">{{ bank.bank_name }}</h1>
              <div>
                <h2>Aktualne logo</h2>
                <div class="w-[300px] h-[120px] border-2">
                  <img :src="props.bank.logo_path" alt="logo" class="w-full h-full object-contain" />
                </div>
              </div>
            </div>
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
                  <PrimaryButton @click="confirmationModalOpen = true" type="button" class="bg-red-700">usuń bank</PrimaryButton>
                  <PrimaryButton type="submit" class="">aktualizuj</PrimaryButton>
                </div>
              </div>
            </form>
          </div>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
