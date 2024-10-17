<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    active: Boolean,
    href: String,
    as: String,
    btnClass: String,
});

const classes = computed(() => {
    return props.active
        ? 'btnResponsiveNavLinkActive'
        : 'btnResponsiveNavLinkInactive';
});

</script>

<template>
    <div>
        <button v-if="as == 'button'" :class="classes" class="w-full text-start">
            <slot />
        </button>
        <button v-if="as == 'button-logout'" class="btn logout">
            <slot />
        </button>

        <Link v-else :href="href" :class="classes">
            <slot />
        </Link>
    </div>
</template>

<style>
.btnResponsiveNavLinkActive {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #001677;
    background-color: #bde2fc;
    text-decoration: none;
    text-align: start;
    transition: background-color 0.15s ease-in-out;
    overflow-x: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.btnResponsiveNavLinkActive:hover {
    background-color: #0099ff;
    color: white;
}

.btnResponsiveNavLinkInactive {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #0099ff;
    text-decoration: none;
    text-align: start;
    transition: background-color 0.15s ease-in-out;
    overflow-x: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}  

.btnResponsiveNavLinkInactive:hover {
    background-color: #0099ff;
    color: white;
    border-radius: var(--bs-border-radius);
}

.logout {
    color: red !important;
}

.logout:hover {
    color: white !important;
}
</style>
