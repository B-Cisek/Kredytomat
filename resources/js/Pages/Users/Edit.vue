<script setup>
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue"
import InputLabel from "@/Components/InputLabel.vue"
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import {useForm} from "@inertiajs/inertia-vue3";

const props = defineProps({
    user: Object
})

console.log(props.user.created_at)

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: ''
});
</script>
<template>
    <AdminDashboardLayout>
        <template #header>
            Użytkownicy / <span class="font-light">{{ user.name }}</span>
        </template>

        <template #default>
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
        </template>
    </AdminDashboardLayout>
</template>
