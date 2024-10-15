<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => {
            // Eliminar el objeto del localStorage
            localStorage.removeItem('ambienteNotLogged');
            // Reiniciar el campo 'password'
            form.reset('password');
        },
    });
};
</script>

<template>

    <Head title="Iniciar Sesión" />

    <div class="min-vh-100 d-flex justify-content-center align-items-center main">
        <div class="card p-4 authCard">
            <div class="mb-4 text-center">
                <AuthenticationCardLogo />
            </div>

            <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div class="mb-3">
                    <label for="email" class="form-label labelCeleste">Correo electrónico</label>
                    <input id="email" v-model="form.email" type="email" class="form-control" required autofocus
                        autocomplete="username" />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.email" />
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label labelCeleste">Contraseña</label>
                    <input id="password" v-model="form.password" type="password" class="form-control" required
                        autocomplete="current-password" />
                    <div class="text-danger mt-2">
                        <InputError :message="form.errors.password" />
                    </div>
                </div>

                <div class="form-check mb-3">
                    <Checkbox v-model:checked="form.remember" name="remember" class="form-check-input" />
                    <label for="remember" class="form-check-label labelCeleste">
                        Recordar credenciales
                    </label>
                </div>

                <div class="d-flex flex-column justify-content-center align-content-center">
                    <div>
                        <Link :href="route('register')" class="nav-link labelCeleste mt-2 anchor">
                        No tengo cuenta
                        </Link>
                    </div>

                    <button type="submit" class="btn btn-primary mt-3 btnPrimary"
                        :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Iniciar sesión
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<style>
@import "../../../css/app.css";
</style>
