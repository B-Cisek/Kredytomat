<script setup>
import HeaderLogged from "@/Components/HeaderLogged.vue";
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <!-- This example requires Tailwind CSS v2.0+ -->
            <!--
              This example requires updating your template:

              ```
              <html class="h-full bg-gray-100">
              <body class="h-full">
              ```
            -->
            <div class="min-h-full">
                <HeaderLogged />

                <header class="bg-white shadow">
                    <div class="mx-auto max-w-7xl py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header"/>
                    </div>
                </header>
                <main>
                    <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
