<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';

defineProps({
    title: String,
});

const showingNavigationDropdown = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>

        <Head :title="title" />

        <div class="min-vh-100 bg-light">
            <nav class="bg-white border-bottom">
                <!-- Primary Navigation Menu -->
                <div class="container-fluid px-4">
                    <div class="d-flex justify-content-between align-items-center py-3">
                        <div class="d-flex">
                            <!-- Logo -->
                            <div class="d-flex align-items-center">
                                <Link :href="route('dashboard')">
                                <!-- Aquí podrías agregar un logo -->
                                <div class="w-50 h-50">
                                    <AuthenticationCardLogo />
                                </div>
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="d-none d-md-flex ms-4">
                                <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Menú principal
                                </NavLink>
                            </div>
                        </div>

                        <div class="d-none d-md-flex align-items-center">
                            <!-- Teams Dropdown -->
                            <Dropdown v-if="$page.props.jetstream.hasTeamFeatures" align="right" width="60">
                                <template #trigger>
                                    <button class="btn btn-outline-secondary">
                                        {{ $page.props.auth.user.current_team.name }}
                                        <i class="ms-2 bi bi-chevron-down"></i>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="dropdown-menu">
                                        <div class="dropdown-item-text">Manage Team</div>
                                        <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">
                                            Team Settings
                                        </DropdownLink>
                                        <DropdownLink v-if="$page.props.jetstream.canCreateTeams"
                                            :href="route('teams.create')">
                                            Create New Team
                                        </DropdownLink>
                                        <div class="dropdown-divider"></div>
                                        <div class="dropdown-item-text">Switch Teams</div>
                                        <template v-for="team in $page.props.auth.user.all_teams" :key="team.id">
                                            <form @submit.prevent="switchToTeam(team)">
                                                <DropdownLink as="button">
                                                    <div class="d-flex align-items-center">
                                                        <span v-if="team.id == $page.props.auth.user.current_team_id"
                                                            class="badge bg-success me-2">✔</span>
                                                        {{ team.name }}
                                                    </div>
                                                </DropdownLink>
                                            </form>
                                        </template>
                                    </div>
                                </template>
                            </Dropdown>

                            <!-- Settings Dropdown -->
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="btn btn-outline-secondary">
                                        {{ $page.props.auth.user.name }}
                                        <i class="ms-2 bi bi-chevron-down"></i>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.show')">
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink v-if="$page.props.jetstream.hasApiFeatures"
                                        :href="route('api-tokens.index')">
                                        API Tokens
                                    </DropdownLink>
                                    <form @submit.prevent="logout">
                                        <DropdownLink as="button">
                                            Log Out
                                        </DropdownLink>
                                    </form>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Hamburger -->
                        <button class="btn btn-outline-secondary d-md-none"
                            @click="showingNavigationDropdown = !showingNavigationDropdown">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                                class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{ 'd-block': showingNavigationDropdown, 'd-none': !showingNavigationDropdown }"
                    class="d-md-none">
                    <div class="py-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Menú principal
                        </ResponsiveNavLink>
                    </div>
                    <div class="py-2 border-top">
                        <ResponsiveNavLink :href="route('profile.show')" :active="route().current('profile.show')">
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

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow-sm">
                <div class="container-fluid py-4">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="container-fluid py-4">
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <!-- Card 1 -->
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Card title 1</h5>
                                <p class="card-text">Some example text to build on the card title and make up the bulk
                                    of the card's
                                    content.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Card 2 -->
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Card title 2</h5>
                                <p class="card-text">Some example text to build on the card title and make up the bulk
                                    of the card's
                                    content.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Card 3 -->
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Card title 3</h5>
                                <p class="card-text">Some example text to build on the card title and make up the bulk
                                    of the card's
                                    content.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
