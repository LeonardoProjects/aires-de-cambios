<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    setTimeout(() => passwordInput.value.focus(), 250);
};

const deleteUser = () => {
    form.delete(route('current-user.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <!-- Título de la sección -->
    <div class="mb-4 text-center">
        <h5>¿Deseas eliminar tu cuenta?</h5>
        <!-- Botón de eliminación -->
        <button @click="confirmUserDeletion" class="btn btn-danger btnDanger">
            Eliminar cuenta
        </button>
    </div>

  <!-- Modal de confirmación de eliminación -->
<div
    v-if="confirmingUserDeletion"
    class="modal fade show d-block"
    data-bs-backdrop="static"
    data-bs-keyboard="false"
    tabindex="-1"
    role="dialog"
    aria-labelledby="deleteUserModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modalEliminarCuenta">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteUserModalLabel">Eliminar cuenta</h5>
                <button type="button" class="btn-close" @click="closeModal" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body text-center">
                <p>
                    ¿Estás seguro de que quieres eliminar tu cuenta? Una vez que se elimine su cuenta,
                    todos sus recursos y datos se eliminarán permanentemente.
                    Por favor ingrese su contraseña para confirmar.
                </p>

                <!-- Campo de contraseña -->
                <div class="form-group">
                    <TextInput
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="form-control"
                        id="passwordInput"
                        placeholder="Contraseña"
                        autocomplete="current-password"
                        @keyup.enter="deleteUser"
                    />
                    <InputError :message="form.errors.password" class="mt-2" />
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" @click="closeModal">Cancelar</button>
                <button
                    type="button"
                    class="btn btn-danger btnDanger"
                    @click="deleteUser"
                    :disabled="form.processing"
                >
                    Borrar cuenta
                </button>
            </div>
        </div>
    </div>
</div>

</template>

<style>
.modalEliminarCuenta {
  position: relative;
  z-index: 1050;
}

.modalEliminarCuenta::before {
  content: '';
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* Fondo oscuro semitransparente */
  backdrop-filter: blur(10px); /* Aplicar efecto blur */
  z-index: -1;
}

.modal-dialog-centered {
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
}

.btnDanger {
  background-color: #dc3545;
  border-color: #dc3545;
}

.btnDanger:disabled {
  opacity: 0.65;
}

</style>
