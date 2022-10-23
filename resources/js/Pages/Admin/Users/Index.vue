<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import { defineProps } from "vue";
import Pagination from "@/Components/Pagination.vue";
import UsersTable from "@/Components/Tables/UsersTable.vue";
import NavLink from "@/Components/NavLink.vue";
import Alert from "@/Components/Alert.vue";
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
  users: Object,
});
</script>

<template>
  <Head title="Użytkownicy" />

  <AdminDashboardLayout>
    <template #header>
      <Link :href="route('admin.dashboard')" class="hover:text-indigo-700"
        >Dashboard /</Link
      >
      Użytkownicy
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
        <NavLink :href="route('admin.users.create')" class="bg-green-600 text-white">
          Dodaj użytkownika
        </NavLink>
      </section>
      <section>
        <UsersTable :users="users" />
        <Pagination :items="users" class="mt-3 rounded-lg" />
      </section>
    </template>
  </AdminDashboardLayout>
</template>
