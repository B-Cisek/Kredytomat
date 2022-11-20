<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

defineProps({
  items: Object,
});
</script>

<template>
  <div class="flex items-center justify-between">
    <div class="flex flex-1 justify-between sm:hidden">
      <a
        href="#"
        class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >Poprzednie</a
      >
      <a
        href="#"
        class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
        >Następna</a
      >
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <div>
        <p class="text-sm text-gray-700">
          Strony od
          <span class="font-medium">{{ items.from ? items.from : 1 }}</span>
          do
          <span class="font-medium">{{ items.to ? items.to : 1 }}</span>
          z
          <span class="font-medium">{{ items.total ? items.total : 1 }}</span>
        </p>
      </div>
      <div>
        <nav
          class="isolate inline-flex -space-x-px rounded-md shadow-sm"
          aria-label="Pagination"
        >
          <!--                    <Link v-if="users.prev_page_url"  :href="users.prev_page_url"-->
          <!--                          class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-2-->
          <!--                              py-2 text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-20">-->
          <!--                        <span class="sr-only">Poprzedna</span>-->
          <!--                        &lt;!&ndash; Heroicon name: mini/chevron-left &ndash;&gt;-->
          <!--                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"-->
          <!--                             aria-hidden="true">-->
          <!--                            <path fill-rule="evenodd"-->
          <!--                                  d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z"-->
          <!--                                  clip-rule="evenodd"/>-->
          <!--                        </svg>-->
          <!--                    </Link>-->
          <!-- Current: "z-10 bg-indigo-50 border-indigo-500 text-indigo-600", Default: "bg-white border-gray-300 text-gray-500 hover:bg-gray-50" -->
          <Link
            v-for="link in items.links"
            :href="link.url"
            v-html="link.label"
            aria-current="page"
            :class="link.url ? '' : 'text-gray-200'"
            class="relative z-10 inline-flex items-center border border-gray-300 px-4 py-2 text-sm font-medium text-gray-500 focus:z-20 bg-white hover:bg-gray-50"
          />
          <!--                    <span class="relative inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700">...</span>-->

          <!--                    <Link v-if="users.next_page_url" :href="users.next_page_url"-->
          <!--                          class="relative inline-flex items-center rounded-r-md border border-gray-300 bg-white px-2 py-2 text-sm font-medium-->
          <!--                              text-gray-500 hover:bg-gray-50  focus:z-20">-->
          <!--                        <span class="sr-only">Następna</span>-->
          <!--                        &lt;!&ndash; Heroicon name: mini/chevron-right &ndash;&gt;-->
          <!--                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"-->
          <!--                             aria-hidden="true">-->
          <!--                            <path fill-rule="evenodd"-->
          <!--                                  d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"-->
          <!--                                  clip-rule="evenodd"/>-->
          <!--                        </svg>-->
          <!--                    </Link>-->
        </nav>
      </div>
    </div>
  </div>
</template>
