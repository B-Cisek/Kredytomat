<script setup>
import {computed} from 'vue'
import {Link} from "@inertiajs/inertia-vue3";

const props = defineProps({status: Number})

const title = computed(() => {
  return {
    503: '503: Service Unavailable',
    500: '500: Server Error',
    404: '404: Page Not Found',
    403: '403: Forbidden',
  }[props.status]
})

const description = computed(() => {
  return {
    503: 'Przepraszamy, przeprowadzamy prace konserwacyjne. Prosimy o ponowne sprawdzenie wkrótce.',
    500: 'Ups, coś poszło nie tak na naszych serwerach.',
    404: 'Przepraszamy, szukana strona nie została znaleziona.',
    403: 'Przepraszamy, dostęp do tej strony jest zabroniony.',
  }[props.status]
})

</script>

<template>
  <!-- ====== Error 404 Section Start -->
  <section class="bg-primary w-full h-screen flex items-center justify-center">
    <div class="container mx-auto">
      <div class="-mx-4 flex">
        <div class="w-full px-4">
          <div class="mx-auto max-w-[400px] text-center">
            <h2
              class="mb-2 text-[50px] font-bold leading-none text-white sm:text-[80px] md:text-[100px]"
            >
              {{ status }}
            </h2>
            <h4 class="mb-3 text-[22px] font-semibold leading-tight text-white">
              {{ description }}
            </h4>

            <Link
              :href="route('home')"
              class="hover:text-primary inline-block rounded-lg border border-white px-8 py-3 text-center text-base font-semibold text-white transition hover:bg-white"
            >Przejdź do strony głównej
            </Link>
          </div>
        </div>
      </div>
    </div>
    <div
      class="absolute top-0 left-0 -z-10 flex h-full w-full items-center justify-between space-x-5 md:space-x-8 lg:space-x-14"
    >
      <div
        class="h-full w-1/3 bg-gradient-to-t from-[#FFFFFF14] to-[#C4C4C400]"
      ></div>
      <div class="flex h-full w-1/3">
        <div
          class="h-full w-1/2 bg-gradient-to-b from-[#FFFFFF14] to-[#C4C4C400]"
        ></div>
        <div
          class="h-full w-1/2 bg-gradient-to-t from-[#FFFFFF14] to-[#C4C4C400]"
        ></div>
      </div>
      <div
        class="h-full w-1/3 bg-gradient-to-b from-[#FFFFFF14] to-[#C4C4C400]"
      ></div>
    </div>
  </section>
  <!-- ====== Error 404 Section End -->
</template>
