<script setup>
import {Link} from "@inertiajs/inertia-vue3";
</script>

<template>
  <footer
    class="p-4 shadow md:flex md:items-center md:justify-between md:p-6 bg-gray-800"
  >
    <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
      © 2023
      <Link :href="route('home')" class="hover:underline">Porównywarka kredytów hipotecznych™</Link>
    </span>
    <ul class="flex flex-wrap items-center mt-3 text-sm text-gray-400 sm:mt-0">
      <li>
        <Link :href="route('home')" class="mr-4 hover:underline md:mr-6">Home</Link>
      </li>
      <li>
        <Link :href="route('offer')" class="mr-4 hover:underline md:mr-6">Oferty Banków</Link>
      </li>
      <li>
        <Link :href="route('about-credit')" class="mr-4 hover:underline md:mr-6">O kredycie</Link>
      </li>
      <li>
        <Link :href="route('offer.all')" class="hover:underline">Wszystkei oferty</Link>
      </li>
    </ul>
  </footer>
</template>
