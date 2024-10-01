<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Registro" />

    <div class="min-vh-100 d-flex justify-content-center align-items-center main">
        <div class="card p-4 authCard">
            <div class="mb-4 text-center">
                <AuthenticationCardLogo />
            </div>

            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label for="name" class="form-label labelCeleste">Nombre</label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="form-control"
                        required
                        autofocus
                        autocomplete="name"
                    />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.name" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label labelCeleste">Correo Electrónico</label>
                    <input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="form-control"
                        required
                        autocomplete="username"
                    />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.email" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label labelCeleste">Contraseña</label>
                    <input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="form-control"
                        required
                        autocomplete="new-password"
                    />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.password" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label labelCeleste">Confirmar Contraseña</label>
                    <input
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        type="password"
                        class="form-control"
                        required
                        autocomplete="new-password"
                    />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.password_confirmation" />
                    </div>
                </div>

                <div v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature" class="form-check mb-3">
                    <Checkbox id="terms" v-model="form.terms" class="form-check-input" />
                    <label for="terms" class="form-check-label labelCeleste">
                        Acepto los <a target="_blank" :href="route('terms.show')" class="labelCeleste">Términos de Servicio</a> y la <a target="_blank" :href="route('policy.show')" class="labelLogin">Política de Privacidad</a>.
                    </label>
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.terms" />
                    </div>
                </div>

                <div class="d-flex flex-column justify-content-center align-content-center">
                    <Link :href="route('login')" class="underline text-sm labelCeleste">
                        ¿Ya tiene cuenta?
                    </Link>

                    <button type="submit" class="btn btn-primary mt-2 btnPrimary" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
@import "../../../css/app.css";
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@100..800&display=swap');
</style>
