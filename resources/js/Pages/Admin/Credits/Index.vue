<script setup>
import AdminDashboardLayout from "@/Layouts/AdminLayout.vue";
import CreditsTable from "@/Components/Tables/CreditsTable.vue";
import Pagination from "@/Components/Pagination.vue";
import {Head, Link} from "@inertiajs/inertia-vue3";
import {defineProps, ref} from "vue";
import Arrow from "@/Components/Arrow.vue";
import {Inertia} from "@inertiajs/inertia";

defineProps({
  credits: Object,
});

const showButton = ref(false);
const massDelete = ref([]);

const handle = (value) => {
  showButton.value = !!value.value.length;

  massDelete.value = value.value;
}

const handleMassDelete = () => {
  Inertia.post(route('admin.credits.massDelete'), {ids: massDelete.value});
  showButton.value = false;
}
</script>

<template>
  <Head title="Kredyty"/>

  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700">Dashboard</Link>
      <Arrow/>
      <span>Kredyty</span>
    </template>
    <template #default>
      <section class="mb-3">
        <div class="flex justify-end">
          <button
            v-show="showButton"
            @click="handleMassDelete"
            class="text-white px-3 py-2 rounded-md font-medium bg-red-600 hover:bg-gray-700 mr-2"
          >
            Usuń zaznaczone
          </button>
          <Link :href="route('admin.credits.create')"
                class="text-white px-3 py-2 rounded-md font-medium bg-green-600 hover:bg-gray-700 text-right">
            Dodaj kredyt
          </Link>
        </div>
      </section>
      <section>
        <CreditsTable :credits="credits" @mass-delete="handle"/>
        <Pagination :items="credits" class="mt-3 rounded-lg"/>
      </section>
    </template>
  </AdminDashboardLayout>
</template>
