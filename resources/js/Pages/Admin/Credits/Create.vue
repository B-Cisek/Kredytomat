<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
  credit_name: "",
  amount_from: "",
  amount_to: "",
  period_from: "",
  period_to: "",
  margin: "",
  commission: "",
  wibor_id: "",
  bank_id: "",
  details: ""
});

defineProps({
  banks: Object,
  wibors: Object,
});

const store = () => {
  form.post(route("admin.credits.store"));
};
</script>

<template>
  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard >
      </Link>
      <Link :href="route('admin.credits.index')" class="hover:text-indigo-700"
        >Kredyty >
      </Link>
      <span class="font-light"> Nowy kredyt</span>
    </template>
    <template #default>
      <div class="w-full flex justify-center bg-white p-5 shadow-md sm:rounded-lg">
        <form @submit.prevent="store" class="w-full">
          <div>
            <InputLabel for="credit_name" value="Nazwa kredytu" />
            <TextInput
              id="credit_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.credit_name"
              required
            />
            <InputError class="mt-2" :message="form.errors.credit_name" />
          </div>
          <div class="flex gap-5">
            <div class="w-2/4">
              <div class="mt-2">
                <InputLabel for="amount_from" value="Kwota od" />
                <TextInput
                  id="amount_from"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.amount_from"
                  required
                />
                <InputError class="mt-2" :message="form.errors.amount_from" />
              </div>
              <div class="mt-2">
                <InputLabel for="amount_to" value="Kwota do" />
                <TextInput
                  id="amount_to"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.amount_to"
                  required
                />
                <InputError class="mt-2" :message="form.errors.amount_to" />
              </div>
              <div class="mt-2">
                <InputLabel for="period_from" value="Okres od" />
                <TextInput
                  id="period_from"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.period_from"
                  required
                />
                <InputError class="mt-2" :message="form.errors.period_from" />
              </div>
              <div class="mt-2">
                <InputLabel for="period_to" value="Okres do" />
                <TextInput
                  id="period_to"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.period_to"
                  required
                />
                <InputError class="mt-2" :message="form.errors.period_to" />
              </div>
            </div>
            <div class="w-2/4">
              <div class="mt-2">
                <InputLabel for="margin" value="Marża" />
                <TextInput
                  id="margin"
                  type="number"
                  step="0.01"
                  class="mt-1 block w-full"
                  v-model="form.margin"
                  required
                />
                <InputError class="mt-2" :message="form.errors.margin" />
              </div>
              <div class="mt-2">
                <InputLabel for="commission" value="Prowizja" />
                <TextInput
                  id="commission"
                  type="number"
                  step="0.01"
                  class="mt-1 block w-full"
                  v-model="form.commission"
                  required
                />
                <InputError class="mt-2" :message="form.errors.commission" />
              </div>
              <div class="mt-3">
                <InputLabel for="wibor" value="WIBOR" />
                <select
                  v-model="form.wibor_id"
                  class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"
                >
                  <option selected disabled value="">Wybierz WIBOR</option>
                  <option v-for="wibor in wibors" :value="wibor.id">
                    {{ wibor.type }}
                  </option>
                </select>
                <InputError class="mt-2" :message="form.errors.wibor_id" />
              </div>
              <div class="mt-3">
                <InputLabel for="bank" value="Bank" />
                <select
                  v-model="form.bank_id"
                  class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"
                >
                  <option selected disabled value="">Wybierz bank</option>
                  <option v-for="bank in banks" :value="bank.id">
                    {{ bank.bank_name }}
                  </option>
                </select>
                <InputError class="mt-2" :message="form.errors.bank_id" />
              </div>
            </div>
          </div>
          <div class="mt-3">
            <InputLabel for="details" value="Szczegóły oferty np. (Ubezpieczenie niskiego wkładu: 0,00 zł,)" />
            <textarea
              v-model="form.details"
              class="w-full h-fit rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
            </textarea>
          </div>
          <PrimaryButton class="mt-3 w-full" type="submit ">Dodaj</PrimaryButton>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
