<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('user-password.update'), {
        errorBag: 'updatePassword',
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }

            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <form @submit.prevent="updatePassword" class="formActualizarContraseña text-center p-3">
        <!-- Título -->
        <div class="mb-4">
            <h3 class="">Actualiza la contraseña</h3>
        </div>

        <!-- Campo de contraseña actual -->
        <div class="form-group">
            <label for="current_password" class="labelCeleste mt-2">Contraseña actual</label>
            <TextInput
                id="current_password"
                ref="currentPasswordInput"
                v-model="form.current_password"
                type="password"
                class="form-control"
                autocomplete="current-password"
            />
            <InputError :message="form.errors.current_password" class="text-danger mt-2" />
        </div>

        <!-- Campo de nueva contraseña -->
        <div class="form-group">
            <label for="password" class="labelCeleste mt-4">Nueva contraseña</label>
            <TextInput
                id="password"
                ref="passwordInput"
                v-model="form.password"
                type="password"
                class="form-control"
                autocomplete="new-password"
            />
            <InputError :message="form.errors.password" class="text-danger mt-2" />
        </div>

        <!-- Confirmar nueva contraseña -->
        <div class="form-group">
            <label for="password_confirmation" class="labelCeleste mt-4">Confirmar contraseña</label>
            <TextInput
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                class="form-control"
                autocomplete="new-password"
            />
            <InputError :message="form.errors.password_confirmation" class="text-danger mt-2" />
        </div>

        <div class="mt-4">
            <span v-if="form.recentlySuccessful" class="text-success ms-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                    class="bi bi-check2-circle" viewBox="0 0 16 16">
                    <path
                        d="M2.5 8a5.5 5.5 0 0 1 8.25-4.764.5.5 0 0 0 .5-.866A6.5 6.5 0 1 0 14.5 8a.5.5 0 0 0-1 0 5.5 5.5 0 1 1-11 0" />
                    <path
                        d="M15.354 3.354a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0z" />
                </svg>
                Guardado</span>
        </div>

        <!-- Mensaje de acción y botón de envío -->
        <div class="mt-2">
            <button type="submit" :disabled="form.processing" class="btn btn-primary mt-2 btnPrimaryUpdatePassword mb-5"
                :class="{ 'opacity-25': form.processing }">
                Actualizar contraseña
            </button>
        </div>
    </form>
</template>

<style>
.formActualizarContraseña {
    min-width: 30vw;
    margin: 0 auto;
    width: 100%;
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
    width: 100%;
}

.btnPrimaryUpdatePassword::before {
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

.btnPrimaryUpdatePassword:hover::before {
    transform: scaleX(1);
    transform-origin: bottom left;
}

.btnPrimaryUpdatePassword:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

@media (max-width: 768px) {
    .formActualizarContraseña {
        width: 70vw;
    }

    .btnPrimaryUpdatePassword {
        min-width: 200px;
        max-width: 350px;
        font-size: 1rem;
        padding: 10px;
    }
}
</style>