<script setup>
import {Link} from "@inertiajs/inertia-vue3"

const props = defineProps(['href']);
</script>

<template>
    <Link :href="href">
        <div class="bg-gray-200 rounded-lg p-5 mb-4 hover:bg-gray-50">
            <h1 class="font-semibold text-xl">
                <slot/>
            </h1>
        </div>
    </Link>
</template>

