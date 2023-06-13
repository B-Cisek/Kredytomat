<script setup>
import {Head} from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {Link} from "@inertiajs/inertia-vue3";

const props = defineProps({
  banks: Object
});
</script>


<template>
  <Head title="Oferta"/>
  <Layout>
    <template v-slot:header>Oferta Banków</template>
    <template v-slot:default>
      <section class="w-full rounded-lg shadow-2xl border border-gray-200 bg-white p-5">
        <div class="grid gap-4 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
          <div
            v-for="bank in props.banks"
            :key="bank.id"
          >
            <Link :href="route('offer.show', bank.slug)">
              <div class="bg-gray-200 w-full text-center rounded-lg">
                <img
                  :src="bank.logo_path"
                  alt="logo"
                  class="h-[105px] w-full border rounded-t-lg"
                >
                <span class="py-2 block font-semibold text-ml">{{ bank.credits_count }} Ofert</span>
              </div>
            </Link>
          </div>
        </div>
      </section>
    </template>
  </Layout>
</template>
