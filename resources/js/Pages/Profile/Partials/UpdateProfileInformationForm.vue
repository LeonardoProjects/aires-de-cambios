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
            </div>
        </div>

        <!-- Mensaje de éxito -->
        <div class="mt-4">
            <span v-if="form.recentlySuccessful"
                class="text-success">
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
            <button type="submit" :disabled="form.processing" class="btn btn-primary mt-3 btnPrimaryUpdateProfile"
                :class="{ 'opacity-25': form.processing }">
                Guardar
            </button>
        </div>
    </form>
</template>

<style>
@import "../../../../css/app.css";

.formActualizar {
    width: 20vw;
    margin: auto;
}

.btnPrimaryUpdatePassword {
    position: relative;
    font-size: 1.2rem;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    overflow: hidden;
    z-index: 1;
    width: 20vw;
}

.btnPrimaryUpdateProfile {
    position: relative;
    font-size: 1.2rem;
    padding: 10px 20px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    overflow: hidden;
    z-index: 1;
    width: 20vw;
}

.btnPrimaryUpdateProfile::before {
    content: "";
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    inset: 0 0 0 0;
    background: hsl(204, 26%, 7%);
    z-index: -1;
    transform: scaleX(0);
    transform-origin: bottom right;
    transition: transform 0.3s ease;
}

.btnPrimaryUpdateProfile:hover::before {
    transform: scaleX(1);
    transform-origin: bottom left;
}

.btnPrimaryUpdateProfile:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .formActualizar {
        width: 70vw;
    }

    .btnPrimaryUpdateProfile {
        width: 100%;
        font-size: 1rem;
        padding: 10px;
    }
}

@media((min-width:768px) and (max-width: 1645px)){
    .formActualizar {
        width: 40vw;
    }

    .btnPrimaryUpdateProfile {
        width: 100%;
        font-size: 1rem;
    }
}
</style>
