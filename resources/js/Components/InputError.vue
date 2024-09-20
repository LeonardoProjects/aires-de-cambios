<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="alert alert-danger">
            {{ message }}
        </p>
    </div>
</template>
