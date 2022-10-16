<script setup>

import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import Alert from "@/Components/Alert.vue"
import NavLink from "@/Components/NavLink.vue"
import CreditsTable from "@/Components/Tables/CreditsTable.vue"
import Pagination from "@/Components/Pagination.vue"
import {Head} from "@inertiajs/inertia-vue3"
import {defineProps} from "vue";

defineProps({
    credits: Object
})

</script>


<template>
    <Head title="Kredyty"/>

    <AdminDashboardLayout>
        <template #header>
            Kredyty
        </template>
        <template #default>
            <section class="flex justify-end flex-wrap items-center mb-3">
                <Alert type="info" v-if="$page.props.flash.message">
                    {{ $page.props.flash.message }}
                </Alert>
                <!--     FILTERS      -->
                <NavLink :href="route('admin.credits.create')" class="bg-green-600 text-white">
                    Dodaj kredyt
                </NavLink>
            </section>
            <section>
                <CreditsTable :credits="credits"/>
                <Pagination :items="credits" class="mt-3 rounded-lg"/>
            </section>
        </template>
    </AdminDashboardLayout>
</template>

