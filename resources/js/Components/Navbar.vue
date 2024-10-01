<template>
    <nav class="bg-transparent border-bottom">
        <!-- Primary Navigation Menu -->
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center py-3">
                <div
                    class="d-flex justify-content-start align-items-center w-50"
                >
                    <!-- Logo -->
                    <div class="d-flex align-items-center w-25">
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

                <div class="dropdown d-none d-md-flex align-items-center">
                    <!-- Settings Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <button class="btn btn-outline-secondary">
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
                                    <DropdownLink as="button">
                                        Cerrar sesión
                                    </DropdownLink>
                                </form>
                            </div>
                        </template>
                    </Dropdown>
                </div>

                <!-- Hamburger -->
                <button
                    class="btn btn-outline-secondary d-md-none"
                    @click="
                        showingNavigationDropdown = !showingNavigationDropdown
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
            </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
            :class="{
                'd-block': showingNavigationDropdown,
                'd-none': !showingNavigationDropdown,
            }"
            class="d-md-none"
        >
            <div class="py-2">
                <ResponsiveNavLink
                    :href="route('dashboard')"
                    :active="route().current('dashboard')"
                >
                    Menú principal
                </ResponsiveNavLink>
            </div>
            <div class="py-2 border-top">
                <ResponsiveNavLink
                    :href="route('profile.show')"
                    :active="route().current('profile.show')"
                >
                    Mi perfil
                </ResponsiveNavLink>
                <form @submit.prevent="logout">
                    <ResponsiveNavLink as="button">
                        Cerrar sesión
                    </ResponsiveNavLink>
                </form>
            </div>
        </div>
    </nav>
</template>

<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import { Link } from "@inertiajs/vue3";
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
    height: auto; /* Ajusta la altura según el contenido */
    min-height: 100px; /* Establece una altura mínima */
    max-height: 10vh; /* Ajusta la altura máxima a algo más razonable */
    background-color: transparent;
    border-bottom: 1px solid #ddd; /* Para añadir la línea de fondo */

    /* Ajuste para el dropdown */
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
    min-width: 75px;
    min-height: 50px;
    max-width: 10vw;
    max-height: auto; /* Cambia el max-height por 'auto' */
}

@media screen and (max-width: 768px) {
    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-width: 120px;
        min-height: 70px;
        max-width: 50vw;
        max-height: 50vw;
    }
}

</style>
