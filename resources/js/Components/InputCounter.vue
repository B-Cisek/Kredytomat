<script setup>
import {ref} from "vue";

const amountCredit = ref(250000)

</script>

<template>
    <div class="w-48">
        <label for="price" class="block text-sm font-medium text-gray-700">Price</label>
        <div>
            <div class="flex">
                <button
                    @click="amountCredit -= 5000"
                    class="bg-white border border-gray-300 w-10">-</button>
                <div class="relative rounded-md shadow-sm">
                    <input type="number"
                           name="price"
                           id="price"
                           class="block w-full border-gray-300 pl-3 pr-7 focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                           :placeholder="amountCredit">
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                        <span class="text-gray-500 sm:text-sm">zł</span>
                    </div>
                </div>
                <button
                    @click="amountCredit += 5000"
                    class="bg-white border border-gray-300 w-10">+</button>
            </div>
        </div>
    </div>
</template>
