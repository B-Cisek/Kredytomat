<script setup>
import ToastListItem from "@/Components/ToastListItem.vue";
import {computed, ref, watch} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

const props = computed(() => usePage().props);

const items = ref([]);

watch(props, (newValue) => {
  if (newValue.value.flash.alert_message) {
    items.value.unshift({
      key: Symbol(),
      message: newValue.value.flash.alert_message,
      type: newValue.value.flash.alert_type,
    });
  }
},{
  immediate: true,
  deep: true
});

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
      :type="item.type"
      @remove="remove(index)"
    />
  </TransitionGroup>
</template>



