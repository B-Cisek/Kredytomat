<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps({
  credit: Object,
  banks: Object,
  wibors: Object,
});

const form = useForm({
  credit_name: props.credit.credit_name,
  amount_from: props.credit.amount_from,
  amount_to: props.credit.amount_to,
  period_from: props.credit.period_from,
  period_to: props.credit.period_to,
  margin: props.credit.margin,
  commission: props.credit.commission,
  wibor_id: props.credit.wibor_id,
  bank_id: props.credit.bank_id,
});

const update = () => {
  form.put(route("admin.credits.update", props.credit.id));
};

const destroy = (id) => {
  if (confirm("Czy chcesz usunąć kredyt?")) {
    form.delete(route("admin.credits.destroy", id));
  }
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
      <span class="font-light">Edycja kredytu</span>
    </template>
    <template #default>
      <div class="bg-white rounded-lg p-5 flex-col justify-between gap-3">
        <h1 class="font-bold text-2xl mb-7">{{ credit.credit_name }}</h1>
        <form @submit.prevent="update" class="w-full">
          <div>
            <InputLabel for="credit_name" value="Nazwa kredytu" />
            <TextInput
              id="credit_name"
              type="text"
              class="mt-1 block w-full"
              model-value="form.credit_name"
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
                  model-value="form.amount_from"
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
                  model-value="form.amount_to"
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
                  model-value="form.period_from"
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
                  model-value="form.period_to"
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
                  model-value="form.margin"
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
                  model-value="form.commission"
                  v-model="form.commission"
                  required
                />
                <InputError class="mt-2" :message="form.errors.commission" />
              </div>
              <div class="mt-3">
                <InputLabel for="wibor_id" value="WIBOR" />
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
                  model-value="form.bank_id"
                  v-model="form.bank_id"
                  class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full"
                >
                  <option v-for="bank in banks" :value="bank.id">
                    {{ bank.bank_name }}
                  </option>
                </select>
                <InputError class="mt-2" :message="form.errors.bank_id" />
              </div>
            </div>
          </div>
          <div class="flex mt-3 justify-between">
            <PrimaryButton @click="destroy(credit.id)" type="button" class="bg-red-700"
              >Usuń
            </PrimaryButton>
            <PrimaryButton type="submit ">Zmień</PrimaryButton>
          </div>
        </form>
      </div>
    </template>
  </AdminDashboardLayout>
</template>
