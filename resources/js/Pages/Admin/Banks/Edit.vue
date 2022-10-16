<script setup>
import {Link, useForm} from "@inertiajs/inertia-vue3"
import AdminDashboardLayout from "@/Layouts/AdminDashboardLayout.vue";
import TextInput from "@/Components/TextInput.vue"
import InputError from "@/Components/InputError.vue"
import InputFile from "@/Components/InputFile.vue"
import PrimaryButton from "@/Components/PrimaryButton.vue"
import {Inertia} from "@inertiajs/inertia";

const props = defineProps({
    bank: Object
})

const form = useForm({
    bankName: props.bank.bank_name,
    logo: props.bank.logo,
});


const destroy = (id) => {
    if (confirm('Czy chcesz usunąć bank?')){
        Inertia.delete(route('admin.banks.destroy', id))
    }
}

const update = () => {
    form.put(route('admin.banks.update', props.bank.id))
}

</script>

<template>
    <AdminDashboardLayout>

        <template #header>
            <Link :href="route('admin.banks.index')" class="hover:text-indigo-700">Banki / </Link>
            <span class="font-light">{{ bank.bank_name }}</span>
        </template>

        <template #default>
            <div class="bg-white rounded-lg p-5">
                <form @submit.prevent="update">
                    <div class="flex justify-center gap-5">
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
                            <InputFile @input="form.logo = $event.target.files[0]"/>
                            <InputError
                                class="mt-2"
                                :message="form.errors.logo"/>
                        </div>
                        <div>
                            <PrimaryButton @click="destroy(bank.id)" type="button" class="bg-red-700">Usuń
                            </PrimaryButton>
                            <PrimaryButton type="submit ">Zmień</PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </template>
    </AdminDashboardLayout>
</template>

