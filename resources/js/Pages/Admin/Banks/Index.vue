<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import BanksTable from "@/Components/Tables/BanksTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { defineProps } from "vue";
import { Head } from "@inertiajs/inertia-vue3";
import Alert from "@/Components/Alert.vue";
import NavLink from "@/Components/NavLink.vue";

defineProps({
  banks: Object,
});
</script>

<template>
  <Head title="Banki" />

  <AdminDashboardLayout>
    <template #header> Banki </template>

    <template #default>
      <section class="flex justify-end flex-wrap items-center mb-3">
        <Alert type="info" v-if="$page.props.flash.message">
          {{ $page.props.flash.message }}
        </Alert>
        <!--     FILTERS      -->
        <NavLink :href="route('admin.banks.create')" class="bg-green-600 text-white">
          Dodaj bank
        </NavLink>
      </section>
      <section>
        <BanksTable :banks="banks" />
        <Pagination :items="banks" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
