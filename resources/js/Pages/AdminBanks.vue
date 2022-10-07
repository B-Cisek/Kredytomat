<script setup>
import {defineProps} from "vue";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import BanksTable from "@/Components/Tables/BanksTable.vue";
defineProps({
    loggedIn: Boolean
})
</script>
<template>
    <AdminDashboardLayout :logged-in="loggedIn">
        <section class="flex justify-between flex-wrap items-center">
            <h1 class="font-bold text-xl uppercase">Banki</h1>
            <div>
                <!--                <TextInput/>-->
                <!--                <Options/>-->
                <PrimaryButton class="bg-green-600">Dodaj</PrimaryButton>
            </div>
        </section>
        <section class="mt-4">
            <BanksTable />
            <Pagination class="mt-3 rounded-lg"/>
        </section>
    </AdminDashboardLayout>
</template>

