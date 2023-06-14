<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Alert from "@/Components/Alert.vue";
import NavLink from "@/Components/NavLink.vue";
import CreditsTable from "@/Components/Tables/CreditsTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import { defineProps } from "vue";

defineProps({
  credits: Object,
});
</script>

<template>
  <Head title="Kredyty" />

  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard ></Link
      >
      Kredyty
    </template>
    <template #default>
      <section class="flex justify-end items-center mb-3">
        <Alert
          :type="$page.props.flash.alert_type"
          v-if="$page.props.flash.alert_message"
        >
          {{ $page.props.flash.alert_message }}
        </Alert>
        <!--     FILTERS      -->
        <Link :href="route('admin.banks.create')" class="text-white px-3 py-2 rounded-md font-medium bg-green-600 hover:bg-gray-700">
          Dodaj kredyt
        </Link>
      </section>
      <section>
        <CreditsTable :credits="credits" />
        <Pagination :items="credits" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
