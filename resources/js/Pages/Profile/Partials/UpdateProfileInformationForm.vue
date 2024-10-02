<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import DeleteUserForm from './DeleteUserForm.vue';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    name: props.user.name,
    email: props.user.email,
    photo: null,
});

const verificationLinkSent = ref(null);

const updateProfileInformation = () => {
    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
        preserveScroll: true
    });
};

const sendEmailVerification = () => {
    verificationLinkSent.value = true;
};
</script>

<template>
    <form @submit.prevent="updateProfileInformation">

        <!-- Descripción -->
        <p>Actualiza la información de tu cuenta y el correo electrónico</p>

        <!-- Formulario -->
        <div class="mt-4">
            <!-- Nombre -->
            <div class="col-span-6 sm:col-span-4">
                <label for="name" class="labelCeleste">Nombre</label>
                <input id="name" v-model="form.name" type="text" class="form-control w-25" required
                    autocomplete="name" />
                <span v-if="form.errors.name" class="text-red-500 text-sm mt-2">{{ form.errors.name }}</span>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <label for="email" class="labelCeleste">Correo electrónico</label>
                <input id="email" v-model="form.email" type="email" class="form-control w-25" required
                    autocomplete="username" />
                <span v-if="form.errors.email" class="text-red-500 text-sm mt-2">{{ form.errors.email }}</span>

                <div v-if="$page.props.jetstream.hasEmailVerification && user.email_verified_at === null" class="mt-2">
                    <p class="text-sm text-gray-600">
                        Your email address is unverified.

                        <button @click.prevent="sendEmailVerification"
                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Click here to re-send the verification email.
                        </button>
                    </p>

                    <div v-show="verificationLinkSent" class="mt-2 font-medium text-sm text-green-600">
                        A new verification link has been sent to your email address.
                    </div>
                </div>
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <div class="mt-4">
            <span v-if="form.recentlySuccessful" class="text-green-500">Saved.</span>
        </div>

        <!-- Botón de guardar -->
        <div class="mt-4">
            <button type="submit" :disabled="form.processing" class="btn btn-primary mt-3 btnPrimary"
                :class="{ 'opacity-25': form.processing }">
                Guardar
            </button>
        </div>
        <div class="mt-8">
            <DeleteUserForm />
        </div>
    </form>
</template>

<style>
@import "../../../../css/app.css";
</style>
