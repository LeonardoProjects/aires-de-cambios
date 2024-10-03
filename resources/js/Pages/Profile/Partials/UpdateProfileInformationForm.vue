<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

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
    <form @submit.prevent="updateProfileInformation" class="formActualizar text-center">

        <!-- Descripción -->
        <h3>Editar perfil</h3>

        <!-- Formulario -->
        <div class="mt-4">
            <!-- Nombre -->
            <div class="col-span-6 sm:col-span-4">
                <label for="name" class="labelCeleste">Nombre</label>
                <input id="name" v-model="form.name" type="text" class="form-control" required autocomplete="name" />
                <span v-if="form.errors.name" class="text-red-500 text-sm mt-2">{{ form.errors.name }}</span>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4 mt-4">
                <label for="email" class="labelCeleste">Correo electrónico</label>
                <input id="email" v-model="form.email" type="email" class="form-control" required
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
            <span v-if="form.recentlySuccessful"
                class="alert alert-success align-content-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-check2-circle" viewBox="0 0 16 16">
                    <path
                        d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                    <path
                        d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                </svg>
                Guardado</span>
        </div>

        <!-- Botón de guardar -->
        <div class="mb-4 d-flex justify-content-center align-content-center">
            <button type="submit" :disabled="form.processing" class="btn btn-primary mt-3 btnPrimary"
                :class="{ 'opacity-25': form.processing }">
                Guardar
            </button>
        </div>
    </form>
</template>

<style>
@import "../../../../css/app.css";

.formActualizar {
    width: 40vw;
}

@media (min-width: 450px) {
    .btnPrimary {
        width: 30vw;
    }
}
</style>
