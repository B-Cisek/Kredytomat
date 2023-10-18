<script setup>
import {Head} from '@inertiajs/inertia-vue3'
import AdminDashboardLayout from "@/Layouts/AdminLayout.vue"
import Tile from "@/Components/Tile.vue"
import {UsersIcon} from "@heroicons/vue/24/solid";
import {DocumentTextIcon} from "@heroicons/vue/24/solid";
import {BuildingLibraryIcon} from "@heroicons/vue/24/solid";
</script>

<template>
  <Head title="Dashboard"/>

  <AdminDashboardLayout>
    <template #header>
      Dashboard
    </template>
    <template #default>
      <section class="flex flex-col gap-3 mt-2">
        <Tile :href="route('admin.users.index')">
          <template v-slot:title>Użytkownicy</template>
          <template v-slot:icon>
            <UsersIcon class="h-9 w-9 text-black"/>
          </template>
        </Tile>
        <Tile :href="route('admin.credits.index')">
          <template v-slot:title>Kredyty</template>
          <template v-slot:icon>
            <DocumentTextIcon class="h-9 w-9 text-black"/>
          </template>
        </Tile>
        <Tile :href="route('admin.banks.index')">
          <template v-slot:title>Banki</template>
          <template v-slot:icon>
            <BuildingLibraryIcon class="h-9 w-9 text-black"/>
          </template>
        </Tile>
      </section>
    </template>
  </AdminDashboardLayout>
</template>
