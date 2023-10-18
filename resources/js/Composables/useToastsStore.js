import {computed, ref, watch} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

export function useToastsStore() {
    const items = ref([]);
    const props = computed(() => usePage().props);

    function add(message, type) {
        items.value.unshift({
            key: Symbol(),
            message: message,
            type: type
        })
    }

    function remove(index) {
        items.value.splice(index, 1);
    }

    watch(props, (newValue) => {
        if (newValue.value.flash.alertMessage) {
            add(newValue.value.flash.alertMessage, newValue.value.flash.alertType);
        }
    }, {immediate: true, deep: true});

    return {
        remove,
        add,
        items
    }
}
