<script setup>

import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import {Link, useForm} from "@inertiajs/inertia-vue3"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import InputFile from "@/Components/InputFile.vue";
import InputLabel from "@/Components/InputLabel.vue";


const form = useForm({
    bankName: null,
    logo: File,
})



const store = () => {
    form.post(route('admin.banks.store'))
}

</script>


<template>
    <AdminDashboardLayout>
        <template #header>
            <Link :href="route('admin.banks.index')" class="hover:text-indigo-700">Banki / </Link><span class="font-light">nowy bank</span>
        </template>
        <template #default>
            <div class="w-full flex justify-center">
                <form @submit.prevent="store">
                    <div>
                        <InputLabel
                            for="name"
                            value="Nazwa banku"/>
                        <TextInput
                            id="bankName"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.bankName"
                            required autofocus
                            autocomplete="bankName"/>
                        <InputError
                            class="mt-2"
                            :message="form.errors.bankName"/>
                    </div>
                    <div>
                        <InputLabel
                            for="logo"
                            value="Logo"/>
                        <InputFile @input="form.logo = $event.target.files[0]" />
                        <InputError
                            class="mt-2"
                            :message="form.errors.logo"/>
                    </div>
                    <PrimaryButton class="mt-3" type="submit ">Dodaj</PrimaryButton>
                </form>
            </div>
        </template>
    </AdminDashboardLayout>
</template>
