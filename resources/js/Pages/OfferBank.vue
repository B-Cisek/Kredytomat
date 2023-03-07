<script setup>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
const props = defineProps({
  credits: Object
});

const filterOpen = ref(false);

console.log(props.credits);
</script>


<template>
  <Head title="Oferta"/>
  <Layout>
    <template v-slot:header>Oferta Banków</template>
    <template v-slot:default>
      <section class="w-full flex flex-col gap-3">
        <div class="relative">
          <button
            @click="filterOpen = !filterOpen"
            class="inline-flex items-center text-gray-500 bg-white shadow-2xl border border-gray-200 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-3 py-1.5"
            type="button">
            <span class="sr-only">Action button</span>
            Action
            <svg class="w-3 h-3 ml-2" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                 xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <!-- Dropdown menu -->
          <div
            v-show="filterOpen"
            class="z-10 bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
            <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-black">Reward</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-black">Promote</a>
              </li>
              <li>
                <a href="#" class="block px-4 py-2 hover:bg-gray-100 text-black">Activate account</a>
              </li>
            </ul>
            <div class="py-1">
              <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delete User</a>
            </div>
          </div>
        </div>

        <div
          class="rounded-lg shadow-2xl border border-gray-200 bg-white p-5"
          v-for="credit in props.credits"
          :key="credit.id"
        >
          <Link :href="route('offer.show.credit', [credit.bank.slug, credit.slug])">
            <h1>{{ credit.credit_name }}</h1>
            <h1>{{ credit.margin }}</h1>
            <h1>{{ credit.period_to }}</h1>
          </Link>
        </div>
      </section>
    </template>
  </Layout>
</template>
