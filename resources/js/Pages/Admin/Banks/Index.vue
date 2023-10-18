<script setup>
import AdminDashboardLayout from "@/Layouts/AdminLayout.vue";
import BanksTable from "@/Components/Tables/BanksTable.vue";
import Pagination from "@/Components/Pagination.vue";
import {defineProps, ref} from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Arrow from "@/Components/Arrow.vue";
import {Inertia} from "@inertiajs/inertia";

defineProps({
  banks: Object,
});

const showButton = ref(false);
const massDelete = ref([]);

const handle = (value) => {
  showButton.value = !!value.value.length;

  massDelete.value = value.value;
}

const handleMassDelete = () => {
  Inertia.post(route('admin.banks.massDelete'), {ids: massDelete.value});
  showButton.value = false;
}
</script>

<template>
  <Head title="Banki" />

  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700">Dashboard</Link>
      <Arrow />
      <span>Banki</span>
    </template>

    <template #default>
      <section class="flex justify-end flex-wrap items-center mb-3">
        <div class="flex justify-end">
          <button
            v-show="showButton"
            @click="handleMassDelete"
            class="text-white px-3 py-2 rounded-md font-medium bg-red-600 hover:bg-gray-700 mr-2"
          >
            Usuń zaznaczone
          </button>
          <Link :href="route('admin.banks.create')" class="text-white px-3 py-2 rounded-md font-medium bg-green-600 hover:bg-gray-700">
            Dodaj bank
          </Link>
        </div>
      </section>
      <section>
        <BanksTable :banks="banks" @mass-delete="handle" />
        <Pagination :items="banks" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
