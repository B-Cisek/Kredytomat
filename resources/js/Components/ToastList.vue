<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {computed, onUnmounted, ref, watch} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";


const flash = computed(() => usePage().props.value.flash)

const items = ref([]);

let removeFinishEventListener = Inertia.on('finish', () => {
  if (flash.alert_type) {
    items.value.unshift({
      key: Symbol(),
      message: flash.alert_message,
      type: flash.alert_type,
    });
  }
});

// watch(flash, (flash) => {
//   if (flash.alert_type) {
//     console.log(flash.alert_message, flash.alert_type)
//     items.value.unshift({
//       key: Symbol(),
//       message: flash.alert_message,
//       type: flash.alert_type,
//     });
//   }
// },{deep: true})


function remove(index) {
  items.value.splice(index, 1);
}

onUnmounted(() => removeFinishEventListener());

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
      :type="item.type"
      @remove="remove(index)"
    />
  </TransitionGroup>
</template>



