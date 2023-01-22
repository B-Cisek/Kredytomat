<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {ref} from "vue";



const items = ref([
  {
    key: Symbol(),
    message: "Set yourself free."
  },
  {
    key: Symbol(),
    message: "Testowy tesks sada"
  },
  {
    key: Symbol(),
    message: "Poprawnie dodano kredyt."
  },
]);

function remove(index) {
  items.value.splice(index, 1);
}

</script>

<template>
  <TransitionGroup
    class="fixed right-4 top-4  z-50 space-y-4 w-full max-w-xs"
    tag="div"
    enter-from-class="translate-x-full opacity-0"
    enter-active-class="duration-500"
    leave-active-class="duration-500"
    leave-to-class="translate-x-full opacity-0"
  >
    <ToastListItem
      v-for="(item, index) in items"
      :key="item.key"
      :message="item.message"
      type="danger"
      @remove="remove(index)"
    />
  </TransitionGroup>
</template>



