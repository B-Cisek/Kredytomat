<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import BanksTable from "@/Components/Tables/BanksTable.vue";
import Pagination from "@/Components/Pagination.vue";
import { defineProps } from "vue";
import { Head, Link } from "@inertiajs/inertia-vue3";
import Alert from "@/Components/Alert.vue";
import NavLink from "@/Components/NavLink.vue";

defineProps({
  banks: Object,
});
</script>

<template>
  <Head title="Banki" />

  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard ></Link
      >
      Banki
    </template>

    <template #default>
      <section class="flex justify-end flex-wrap items-center mb-3">
        <Alert
          :type="$page.props.flash.alert_type"
          v-if="$page.props.flash.alert_message"
        >
          {{ $page.props.flash.alert_message }}
        </Alert>
        <!--     FILTERS      -->
        <Link :href="route('admin.banks.create')" class="text-white px-3 py-2 rounded-md font-medium bg-green-600 hover:bg-gray-700">
          Dodaj bank
        </Link>
      </section>
      <section>
        <BanksTable :banks="banks" />
        <Pagination :items="banks" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
