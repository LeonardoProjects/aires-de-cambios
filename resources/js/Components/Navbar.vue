<template>
    <nav class="bg-transparent border-bottom">
        <!-- Primary Navigation Menu -->
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div
                    class="d-flex justify-content-start align-items-center w-50"
                >
                    <!-- Logo -->
                    <div class="d-flex align-items-center">
                        <Link :href="route('dashboard')">
                            <!-- Aquí podrías agregar un logo -->
                            <div class="logo-container object-fit-cover">
                                <AuthenticationCardLogo />
                            </div>
                        </Link>
                    </div>

                    <!-- Navigation Links -->
                    <div class="d-none d-md-flex ms-4">
                        <NavLink
                            :href="route('herramienta')"
                            :active="route().current('herramienta')"
                        >
                            Herramienta
                        </NavLink>
                        <NavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                        >
                            Sobre nosotros
                        </NavLink>
                        <NavLink
                            :href="route('herramienta')"
                            :active="route().current('herramienta')"
                        >
                            Ayuda
                        </NavLink>
                    </div>
                </div>

                <div
                    v-if="$page.props.auth.user"
                    class="dropdown mx-3 d-md-flex align-items-center"
                >
                    <!-- Settings Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button
                                class="btn profile-button align-items-center"
                            >
                                {{ $page.props.auth.user.name }}
                                <i class="ms-2 bi bi-chevron-down"></i>
                            </button>
                        </template>

                        <template #content>
                            <div class="dropdown-content">
                                <DropdownLink :href="route('profile.show')">
                                    Perfil
                                </DropdownLink>
                                <form @submit.prevent="logout">
                                    <DropdownLink btnClass="logout" as="button">
                                        Cerrar sesión
                                    </DropdownLink>
                                </form>
                            </div>
                        </template>
                    </Dropdown>
                </div>

                <!-- Mostrar "Iniciar sesión" si el usuario no está autenticado -->
                <div v-else>
                    <Link
                        :href="route('login')"
                        class="btn btn-outline-primary mx-3"
                    >
                        Iniciar sesión
                    </Link>
                </div>

                <!-- Hamburger -->
                <div class="position-relative d-none hamburguerDiv">
                    <button
                        class="btn btn-outline-secondary mx-3 d-md-none hamburger"
                        @click="
                            showingNavigationDropdown =
                                !showingNavigationDropdown
                        "
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="20"
                            height="20"
                            fill="currentColor"
                            class="bi bi-list"
                            viewBox="0 0 16 16"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"
                            />
                        </svg>
                    </button>
                    <div
                        :class="{
                            'd-block': showingNavigationDropdown,
                            'd-none': !showingNavigationDropdown,
                        }"
                        class="d-md-none dropdown-content"
                    >
                        <div class="">
                        </div>
                        <div class="border-top" v-if="$page.props.auth.user">
                            <ResponsiveNavLink
                                :href="route('profile.show')"
                                :active="route().current('profile.show')"
                            >
                                {{ $page.props.auth.user.name }}
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('herramienta')"
                                :active="route().current('herramienta')"
                                btnClass="logout"
                            >
                                Herramienta
                            </ResponsiveNavLink>
                            <form @submit.prevent="logout">
                                <ResponsiveNavLink as="button-logout">
                                    Cerrar sesión
                                </ResponsiveNavLink>
                            </form>
                        </div>
                        <!-- Mostrar "Iniciar sesión" en el menú responsive si no está autenticado -->
                        <div class="border-top" v-else>
                            <ResponsiveNavLink :href="route('login')">
                                Iniciar sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
    </nav>
</template>

<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";

const showingNavigationDropdown = ref(false);
const logout = () => {
    router.post(route("logout"));
};
</script>

<style lang="css" scoped>
nav {
    display: flex;
    align-items: center;
    height: auto;
    min-height: 100px;
    max-height: 10vh;
    background-color: white;
    border-bottom: 1px solid #ddd;
    .dropdown {
        position: relative;
    }
}

.dropdown-content {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1000;
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.logo-container {
    min-width: 100px;
    min-height: 50px;
    max-width: 8vw;
    max-height: auto;
}

.profile-button {
    border: solid 1px #0099ff;
    color: #0099ff;
    position: relative;
}

.profile-button:hover {
    background-color: #0099ff;
    color: white;
}

.profile-button:focus {
    background-color: #0099ff;
    color: white;
}

button.hamburger {
    position: relative;
    z-index: 1050;
    color: #0099ff;
    border-color: #0099ff;
}

button.hamburger:hover {
    background-color: #0099ff;
    color: white;
}

@media screen and (max-width: 767px) {
    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-width: 120px;
        min-height: 70px;
        max-width: 20vw;
        max-height: 50vw;
    }

    .profile-button {
        display: none;
    }

    .hamburguerDiv{
        display: block !important;
    }
}
</style>
