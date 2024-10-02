<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: String,
    active: Boolean,
});

const classes = computed(() => {
    return props.active ? 'navlink-active' : 'navlink-inactive'
});
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<style>
/* Estilos cuando active es true (enlace activo) */
.navlink-active {
    margin-left: 10px;
    margin-right: 10px;
    display: inline-flex;
    align-items: center;
    padding-left: 0.25rem;
    padding-right: 0.25rem;
    padding-top: 0.25rem;
    border-bottom: 2px solid #0099ff;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.25rem;
    color: #145580;
    text-decoration: none;
    transition: border-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

.navlink-active:hover {
    outline: none;
    border-bottom-color: #00aeff;
    color: #00aeff;
}

/* Estilos cuando active es false (enlace inactivo) */
.navlink-inactive {
    margin-right: 10px;
    margin-left: 10px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    padding-left: 0.25rem;  /* px-1 */
    padding-right: 0.25rem; /* px-1 */
    padding-top: 0.25rem;   /* pt-1 */
    border-bottom: 2px solid transparent; /* border-transparent */
    font-size: 0.875rem;    /* text-sm */
    font-weight: 500;       /* font-medium */
    line-height: 1.25rem;   /* leading-5 */
    color: #000000;         /* text-gray-500 */
    transition: border-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

.navlink-inactive:hover {
    color: #00aeff;         /* hover:text-gray-700 */
    border-bottom-color: #00aeff; /* hover:border-gray-300 */
}

.navlink-inactive:focus {
    outline: none;          /* focus:outline-none */
    color: #00aeff;         /* focus:text-gray-700 */
    border-bottom-color: #00aeff; /* focus:border-gray-300 */
}

</style>
