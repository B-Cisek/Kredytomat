<script setup>
import {ref} from "vue";
import Tabs from "@/Components/Tabs.vue";
import CreditSchedule from "@/Components/CreditSchedule.vue";

const props = defineProps({
  schedule: Array
});

const tabs = ref([
  {
    title: 'Harmonogram spłaty',
    value: 'harmonogram',
    disabled: false
  },
  {
    title: 'Symulacja dla różnych kwot',
    value: 'rozneKwoty',
    disabled: false
  },
  {
    title: 'Symulacja zmiany kwoty kredytu',
    value: 'zmiana',
    disabled: false
  },
  {
    title: 'Nadpłata kredytu',
    value: 'nadplata',
    disabled: false
  }
]);
</script>
<template>
  <Tabs :tabs="tabs" active="harmonogram">
    <template v-slot:content="{active}">

      <template v-if="active === 'harmonogram'">
        <CreditSchedule class="mt-10" :schedule="schedule"/>
      </template>

      <template v-if="active === 'rozneKwoty'">
        <p class="mt-10">Symulacja dla różnych kwot - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum cumque hic impedit itaque laudantium molestiae reiciendis, sint? Cumque cupiditate deleniti distinctio eos, et facilis laudantium nihil, nisi nulla perspiciatis temporibus!</p>
      </template>

      <template v-if="active === 'zmiana'">
        <p class="mt-10">Symulacja zmiany kwoty kredytu - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum cumque hic impedit itaque laudantium molestiae reiciendis, sint? Cumque cupiditate deleniti distinctio eos, et facilis laudantium nihil, nisi nulla perspiciatis temporibus!</p>
      </template>

      <template v-if="active === 'nadplata'">
        <p class="mt-10">Nadpłata kredytu - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum cumque hic impedit itaque laudantium molestiae reiciendis, sint? Cumque cupiditate deleniti distinctio eos, et facilis laudantium nihil, nisi nulla perspiciatis temporibus!</p>
      </template>

    </template>
  </Tabs>
</template>
