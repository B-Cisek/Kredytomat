<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import {defineProps} from "vue"
import Pagination from "@/Components/Pagination.vue"
import UsersTable from "@/Components/Tables/UsersTable.vue"
import NavLink from "@/Components/NavLink.vue"
import Alert from "@/Components/Alert.vue"
import {Head} from "@inertiajs/inertia-vue3"

defineProps({
    users: Object
})

</script>

<template>
    <Head title="Użytkownicy"/>

    <AdminDashboardLayout>

        <template #header>
            Użytkownicy
        </template>

        <template #default>
            <section class="flex justify-end flex-wrap items-center mb-3">
                <Alert type="info" v-if="$page.props.flash.message">
                    {{ $page.props.flash.message }}
                </Alert>
                <!--     FILTERS      -->
                <NavLink :href="route('admin.users.create')" class="bg-green-600 text-white">
                    Dodaj użytkownika
                </NavLink>
            </section>
            <section>
                <UsersTable :users="users"/>
                <Pagination :items="users" class="mt-3 rounded-lg"/>
            </section>
        </template>

    </AdminDashboardLayout>
</template>
