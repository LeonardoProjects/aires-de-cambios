<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue';
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});

</script>

<template>
    <AppLayout title="Profile">
        <div class="w-100 d-flex justify-content-center">
            <div class="divComponentes flex-column">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation" class="d-flex justify-content-center">
                    <UpdateProfileInformationForm :user="$page.props.auth.user" class="p-3" />

                    <SectionBorder />
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword" class="d-flex justify-content-center">
                    <UpdatePasswordForm />

                    <SectionBorder />
                </div>

                <!-- <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                    <TwoFactorAuthenticationForm
                        :requires-confirmation="confirmsTwoFactorAuthentication"
                        class="mt-10 sm:mt-0"
                    />

                    <SectionBorder />
                </div> -->

                <LogoutOtherBrowserSessionsForm :sessions="sessions" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <SectionBorder />

                    <DeleteUserForm />
                </template>
            </div>
        </div>
    </AppLayout>
</template>
<style>

@media (max-width: 767px) {
    .divComponentes{
        width: 100%;
    }
}
</style>
