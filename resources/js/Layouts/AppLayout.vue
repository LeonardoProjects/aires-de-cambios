<script setup>
import { Head } from '@inertiajs/vue3';
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
            <main class="container-fluid py-4">
                <slot />
            </main>
        </div>
    </div>
</template>
