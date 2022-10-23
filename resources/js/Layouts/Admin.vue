<script setup>

import Header from "@/Components/Header.vue";
import Sidebar from "@/Components/Sidebar.vue";
</script>

<template>
    <div class="w-full">
        <div class="w-full">
            <Header :logged-in="true" />
        </div>
        <div class="flex">
            <slot />
        </div>

    </div>
</template>
