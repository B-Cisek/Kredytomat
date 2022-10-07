<script setup>

import Header from "@/Components/Header.vue";
import AdminHeader from "@/Components/AdminHeader.vue";

</script>

<template>
    <Header/>

    <AdminHeader>
        <slot name="header"/>
    </AdminHeader>

    <main class="lg:px-8 max-w-7xl mx-auto px-2 py-3 sm:px-6">
        <slot />
    </main>
</template>

