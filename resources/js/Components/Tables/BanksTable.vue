<script setup>

</script>

<template>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500 ">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
            <tr>
                <th scope="col" class="p-4">
                    <div class="flex items-center">
                        <input id="checkbox-all-search" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300
                        focus:ring-blue-500">
                        <label for="checkbox-all-search" class="sr-only">checkbox</label>
                    </div>
                </th>
                <th scope="col" class="py-3 px-6">
                    Nazwa banku
                </th>
                <th scope="col" class="py-3 px-6">
                    Ścieżka(logo)
                </th>
                <th scope="col" class="py-3 px-6">
                    Akcje
                </th>
            </tr>
            </thead>
            <tbody>
            <tr class="bg-white border-b">
                <td class="p-4 w-4">
                    <div class="flex items-center">
                        <input id="checkbox-table-search-1" type="checkbox"
                               class="w-4 h-4 text-blue-600 bg-gray-100 rounded border-gray-300 focus:ring-blue-500">
                        <label for="checkbox-table-search-1" class="sr-only">checkbox</label>
                    </div>
                </td>
                <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap ">
                    mBank
                </th>
                <td class="py-4 px-6">
                    /sadsad/sadasdsadas/sadsa
                </td>
                <td class="flex items-center py-4 px-6 space-x-3">
                    <a href="#" class="font-medium text-blue-600 hover:underline">Edytuj</a>
                    <a href="#" class="font-medium text-red-600 hover:underline">Usuń</a>
                </td>
            </tr>

            </tbody>
        </table>
    </div>
</template>

