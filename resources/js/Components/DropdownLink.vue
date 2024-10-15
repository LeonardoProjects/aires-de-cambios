<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    href: String,
    as: String,
    btnClass: String,
});
</script>

<template>
    <div>
        <button
            v-if="as == 'button'"
            type="submit"
            :class="['btn', 'btnDropdownLink', btnClass === 'logout' ? 'logout' : '']"
        >
            <slot />
        </button>

        <a
            v-else-if="as == 'a'"
            :href="href"
            class="anchorDropdownLink"
        >
            <slot />
        </a>

        <Link
            v-else
            :href="href"
            class="linkDropdownLink"
        >
            <slot />
        </Link>
    </div>
</template>

<style>
.btnDropdownLink,
.linkDropdownLink,
.anchorDropdownLink {
    display: block;
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    line-height: 1.25rem;
    color: #0099ff;
    text-decoration: none;
    text-align: start;
}

.btnDropdownLink:hover,
.linkDropdownLink:hover,
.anchorDropdownLink:hover,
.btnDropdownLink:focus,
.linkDropdownLink:focus,
.anchorDropdownLink:focus {
    background-color: #0099ff;
    outline: none;
    color: white;
    border-radius: var(--bs-border-radius);
}

.btnDropdownLink {
    width: 100%;
    text-align: start;
    transition: none;
}

.logout {
  color: red;
  font-size: 0.875rem;
  border-radius: 0;
}

.logout:hover {
  color: white;
  background-color: red;
}

.logout:focus {
  color: white;
  background-color: red;
}

</style>
