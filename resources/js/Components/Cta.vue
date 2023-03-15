<script setup>
import {Link} from "@inertiajs/inertia-vue3";
</script>

<template>
  <section class="rounded-lg shadow-2xl border border-gray-200 bg-white">
    <div class="mx-auto max-w-7xl py-12 px-4 sm:px-6 lg:flex lg:items-center lg:justify-between lg:py-16 lg:px-8">
      <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-4xl">
        <span class="block">Porównywarka kredytów hipotecznych</span>
        <span class="block text-indigo-600">Znajdź najlepszy kredyt.</span>
      </h2>
      <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0">
        <div class="inline-flex rounded-md shadow">
          <Link
            :href="route('offer.all')"
            class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-3 text-base font-medium text-white hover:bg-indigo-700"
          >Zobacz wszystkie oferty
          </Link>
        </div>
      </div>
    </div>
  </section>
</template>
