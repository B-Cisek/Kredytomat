<script setup>

import MainHeader from "@/Components/Header.vue";

defineProps({
    loggedIn: Boolean
})
</script>

<template>
    <div class="h-full">
        <MainHeader :loggedIn="loggedIn"/>

        <header class="bg-white shadow">
            <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    <slot name="header"/>
                </h1>
            </div>
        </header>

        <main>
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <slot/>
            </div>
        </main>
    </div>
</template>
