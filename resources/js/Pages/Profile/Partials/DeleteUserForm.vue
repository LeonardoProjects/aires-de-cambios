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
    <div class="mb-4">
        <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
        <p class="text-sm text-gray-600">
            Permanently delete your account and all associated resources.
        </p>
    </div>

    <!-- Descripción -->
    <div class="max-w-xl text-sm text-gray-600 mb-4">
        Once your account is deleted, all of its resources and data will be permanently deleted. 
        Before deleting your account, please download any data or information that you wish to retain.
    </div>

    <!-- Botón de eliminación -->
    <div>
        <button @click="confirmUserDeletion" class="btn btn-danger">
            Delete Account
        </button>
    </div>

    <!-- Modal de confirmación de eliminación -->
    <DialogModal :show="confirmingUserDeletion" @close="closeModal">
        <template #title>
            Delete Account
        </template>

        <template #content>
            Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm.

            <div class="mt-4">
                <TextInput
                    ref="passwordInput"
                    v-model="form.password"
                    type="password"
                    class="mt-1 block w-3/4"
                    placeholder="Password"
                    autocomplete="current-password"
                    @keyup.enter="deleteUser"
                />
                <InputError :message="form.errors.password" class="mt-2" />
            </div>
        </template>

        <template #footer>
            <SecondaryButton @click="closeModal">
                Cancel
            </SecondaryButton>

            <DangerButton
                class="ms-3"
                :class="{ 'opacity-25': form.processing }"
                :disabled="form.processing"
                @click="deleteUser"
            >
                Delete Account
            </DangerButton>
        </template>
    </DialogModal>
</template>
