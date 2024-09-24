<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>

    <Head title="Forgot Password" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div class="mb-4 text-sm text-gray-600">
            ¿Olvidaste tu contraseña? Ningún problema. Simplemente háganos saber su dirección de correo electrónico y le
            enviaremos un enlace para restablecer su contraseña que le permitirá elegir una nueva.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <label for="email" class="form-label labelCeleste">Correo Electrónico</label>
                <TextInput id="email" v-model="form.email" type="email" class="form-control" required autofocus
                    autocomplete="username" />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <button class="btn btn-primary btnPrimary" :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing">
                    Restablecer contraseña de correo electrónico
                </button>
            </div>
        </form>
    </AuthenticationCard>
</template>

<style>
@import "../../../css/app.css";
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap');
</style>
