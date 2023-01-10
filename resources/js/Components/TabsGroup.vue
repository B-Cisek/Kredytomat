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
    title: 'Test 1',
    value: 'test1',
    disabled: false
  },
  {
    title: 'Test 2',
    value: 'test2',
    disabled: false
  },
  {
    title: 'Test 3',
    value: 'test3',
    disabled: false
  }
]);
</script>
<template>
  <Tabs :tabs="tabs" active="harmonogram">
    <template v-slot:content="{active}">

      <template v-if="active === 'harmonogram'">
        <CreditSchedule :schedule="schedule"/>
      </template>

      <template v-if="active === 'test1'">
        <p>test1 - Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum cumque hic impedit itaque laudantium molestiae reiciendis, sint? Cumque cupiditate deleniti distinctio eos, et facilis laudantium nihil, nisi nulla perspiciatis temporibus!</p>
      </template>

    </template>
  </Tabs>
</template>
