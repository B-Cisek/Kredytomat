<script setup>
import {defineProps} from "vue";
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Pagination from "@/Components/Pagination.vue";
import CreditsTable from "@/Components/Tables/CreditsTable.vue";
defineProps({
    loggedIn: Boolean
})
</script>

<template>
    <AdminDashboardLayout :logged-in="loggedIn">
        <section class="flex justify-between flex-wrap items-center">
            <h1 class="font-bold text-xl uppercase">Kredyty</h1>
            <div>
                <!--                <TextInput/>-->
                <!--                <Options/>-->
                <PrimaryButton class="bg-green-600">Dodaj</PrimaryButton>
            </div>
        </section>
        <section class="mt-4">
            <CreditsTable />
            <Pagination class="mt-3 rounded-lg"/>
        </section>
    </AdminDashboardLayout>
</template>
