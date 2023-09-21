<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/inertia-vue3";
import Spinner from "@/Components/Spinner.vue";

defineProps({
  status: String,
});

const form = useForm({
  email: "",
});

const submit = () => {
  form.post(route("password.email"));
};
</script>

<template>
  <GuestLayout>
    <Head title="Zapomniałeś hasła?" />

    <h1 class="text-xl font-bold text-center tracking-wide">
      Wyślij link
    </h1>
    <h1 class="text-xl font-bold text-center mb-10 tracking-wide">
      do resetowania hasła
    </h1>
    <div class="mb-4 text-sm text-gray-600 text-center">
      <p>Zapomniałeś hasła? Nie ma problemu.</p>
      <p>Podaj swój adres e-mail, a my wyślemy Ci link do resetowania hasła.</p>
    </div>

    <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
      {{ status }}
    </div>

    <form @submit.prevent="submit">
      <div>
        <InputLabel for="email" value="Email" />
        <TextInput
          id="email"
          type="email"
          class="mt-1 block w-full"
          v-model="form.email"
          required
          autofocus
          autocomplete="username"
        />
        <InputError class="mt-2" :message="form.errors.email" />
      </div>

      <div class="flex items-center justify-end mt-4">
        <PrimaryButton
          class="w-full h-[38px] justify-center inline-flex items-center"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        ><Spinner v-if="form.processing"/>
          {{ form.processing ? '' : 'Wyślij' }}
        </PrimaryButton>
      </div>
    </form>
  </GuestLayout>
</template>
