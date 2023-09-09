<script setup>
import {Head, Link} from "@inertiajs/inertia-vue3";
import Layout from "@/Layouts/Layout.vue";
import {ref} from "vue";
import {useHelpers} from "@/Composables/useHelpers";

const {formattedToPLN} = useHelpers();

const props = defineProps({
  credits: Object
});

const filterOpen = ref(false);
</script>

<template>
  <Head title="Oferta"/>
  <Layout>
    <template v-slot:header>Oferta banku: <span>{{ credits[0].bank.bank_name ?? '' }}</span></template>
    <template v-slot:default>
      <section class="w-full flex flex-col gap-3">
        <div class="text-right">
          <div class="relative inline-block text-left">
            <div>
              <button
                @click="filterOpen = !filterOpen"
                type="button"
                class="inline-flex w-full justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                id="menu-button"
                aria-expanded="true"
                aria-haspopup="true"
              >
                Sortuj
                <svg class="-mr-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                  <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd"/>
                </svg>
              </button>
            </div>

            <transition
              enter-active-class="transition ease-out duration-100"
              enter-to-class="transform opacity-100 scale-100"
              enter-from-class="transform opacity-0 scale-95"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-show="filterOpen"
                class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                <div class="py-1" role="none">
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, sort: 'desc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?sort=desc`}"
                    role="menuitem"
                    tabindex="-1"
                  >
                    Najnowsze Oferty
                  </Link>
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, sort: 'asc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?sort=asc`}"
                    role="menuitem"
                    tabindex="-1"
                  >Najstarsze Oferty
                  </Link>
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, marza: 'asc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?marza=asc`}"
                    role="menuitem"
                    tabindex="-1"
                  >Marża: rosnąco
                  </Link>
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, marza: 'desc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?marza=desc`}"
                    role="menuitem"
                    tabindex="-1"
                  >Marża: malejąco
                  </Link>
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, prowizja: 'asc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?prowizja=asc`}"
                    role="menuitem"
                    tabindex="-1"
                  >Prowizja: rosnąco
                  </Link>
                  <Link
                    :href="route('offer.show', {bank: props.credits[0].bank.slug, prowizja: 'desc'})"
                    class="text-gray-700 block px-4 py-2 text-sm"
                    :class="{'bg-gray-100 text-gray-900': $page.url === `/oferta/${props.credits[0].bank.slug}?prowizja=desc`}"
                    role="menuitem"
                    tabindex="-1"
                  >Prowizja: malejąco
                  </Link>
                </div>
              </div>
            </transition>
          </div>

        </div>
        <div
          class="rounded-lg hover:border-gray-300 hover:shadow-inner border border-gray-200 bg-white p-5"
          v-for="(credit, index) in props.credits"
          :key="credit.id"
        >
          <Link :href="route('offer.show.credit', [credit.bank.slug, credit.slug])">
            <div class="w-full py-1">
              <span>{{ index + 1 }}.</span>
              <span class="ml-5">{{ credit.credit_name }}</span>
            </div>
            <div class="w-full flex flex-col md:flex-row justify-around items-center py-3 text-center gap-4">
              <div>
                <div class="w-[150px] h-[60px]">
                  <img
                    :src="credit.bank.logo_path"
                    :alt="credit.bank.bank_name"
                    class="w-full h-full object-fit"
                  />
                </div>
              </div>
              <div>
                <span class="text-gray-500">Okres</span>
                <p class="font-semibold text-lg">
                  {{ credit.period_from / 12 }} - {{ credit.period_to / 12 }} lat
                </p>
              </div>
              <div class="">
                <span class="text-gray-500">Prowizja</span>
                <p class="font-semibold">{{ credit.commission }}%</p>
              </div>
              <div class="">
                <span class="text-gray-500">Marża</span>
                <p class="font-semibold">{{ credit.margin }}%</p>
              </div>
              <div class="">
                <span class="text-gray-500">RRSO</span>
                <p class="font-semibold">9,33%</p>
              </div>
              <div class="">
                <span class="text-gray-500">Kwota do</span>
                <p class="font-semibold">{{ formattedToPLN.format(credit.amount_to) }}</p>
              </div>
            </div>
          </Link>
        </div>
      </section>
    </template>
  </Layout>
</template>
