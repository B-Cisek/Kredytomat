<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import Alert from "@/Components/Alert.vue";
import NavLink from "@/Components/NavLink.vue";
import CreditsTable from "@/Components/Tables/CreditsTable.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
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
        <NavLink :href="route('admin.credits.create')" class="bg-green-600 text-white">
          Dodaj kredyt
        </NavLink>
      </section>
      <section>
        <CreditsTable :credits="credits" />
        <Pagination :items="credits" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
