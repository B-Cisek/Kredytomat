<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import {computed, defineProps} from "vue"
import {Link} from "@inertiajs/inertia-vue3";
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {useForm, usePage} from "@inertiajs/inertia-vue3"
import PrimaryButton from "@/Components/PrimaryButton.vue"

const auth = computed(() => usePage().props.value.auth)

const form = useForm({
    name: '',
    email: '',
    password: ''
});

const store = () => {
    form.post(route('admin.users.store'))
}

</script>

<template>
    <AdminDashboardLayout>
        <template #header>
            <Link :href="route('admin.users.index')" class="hover:text-indigo-700">Użytkownicy / </Link><span class="font-light">nowy użytkownik</span>
        </template>
        <div class="w-full flex justify-center">
            <form @submit.prevent="store">
                <div>
                    <InputLabel
                        for="name"
                        value="Nazwa użytkownika"/>
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required autofocus
                        autocomplete="name"/>
                    <InputError
                        class="mt-2"
                        :message="form.errors.name"/>
                </div>
                <div>
                    <InputLabel
                        for="email"
                        value="Email"/>
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required autofocus
                        autocomplete="email"/>
                    <InputError
                        class="mt-2"
                        :message="form.errors.email"/>
                </div>
                <div>
                    <InputLabel
                        for="password"
                        value="Hasło"/>
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required autofocus
                        autocomplete="password"/>
                    <InputError
                        class="mt-2"
                        :message="form.errors.password"/>
                </div>
                <PrimaryButton class="mt-3" type="submit ">Dodaj</PrimaryButton>
            </form>
        </div>
    </AdminDashboardLayout>
</template>

