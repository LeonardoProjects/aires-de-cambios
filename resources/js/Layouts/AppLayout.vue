<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';

defineProps({
    title: String,
});

</script>

<template>
    <div>

        <Head :title="title" />

        <div class="min-vh-100 bg-light">
            <Navbar />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white shadow-sm">
                <div class="container-fluid py-4">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main class="container-fluid p-0">
                <slot />
            </main>
            <footer>
                <Footer />
            </footer>
        </div>
    </div>
</template>