<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import {defineProps} from "vue"
import Pagination from "@/Components/Pagination.vue"
import UsersTable from "@/Components/Tables/UsersTable.vue"
import NavLink from "@/Components/NavLink.vue"
import Alert from "@/Components/Alert.vue";

defineProps({
    users: Object
})

</script>

<template>
    <AdminDashboardLayout>

        <template #header>
            Użytkownicy
        </template>

        <template #default>
            <section class="flex justify-end flex-wrap items-center mb-3">
                    <Alert message="success" v-if="$page.props.flash.message">
                        {{ $page.props.flash.message }}
                    </Alert>
                    <!--     FILTERS      -->
                    <NavLink :href="route('admin.users.create')" class="bg-green-600 text-white">
                        Dodaj użytkownika
                    </NavLink>
            </section>
            <!--        <Message v-if="$page.props">{{ $page.props }}</Message>-->
            <section>
                <UsersTable :users="users"/>
                <Pagination :users="users" class="mt-3 rounded-lg"/>
            </section>
        </template>

    </AdminDashboardLayout>
</template>
