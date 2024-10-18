<template>
    <div class="footer-container">
        <a
            :href="'https://www.fadu.edu.uy/'"
            target="_blank"
            class="logo-footer d-flex justify-content-center align-items-center"
        >
            <img
                src="../../images/fadu.png"
                alt="logoProyecto"
                class="h-100 w-100"
            />
        </a>
        <a
            :href="'https://utec.edu.uy/es/'"
            target="_blank"
            class="logo-footer d-flex align-items-center justify-content-center"
        >
            <img
                src="../../images/utec.png"
                alt="logoProyecto"
                class="h-100 w-50"
            />
        </a>
        <a
            target="_blank"
            class="anchorTitle d-flex justify-content-center"
            href="https://udelar.edu.uy/portal/"
            ><img
                class="logo-footer"
                src="../../images/udelar.png"
                alt="UdelaR"
        /></a>
        <a
            target="_blank"
            class="anchorTitle d-flex justify-content-center"
            href="https://www.utu.edu.uy/"
            ><img class="logo-footer" src="../../images/utu.png" alt="UTU"
        /></a>
    </div>
</template>

<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<style lang="css" scoped>
.footer-container {
    position: relative;
    bottom: 0;
    left: 0;
    width: 100%;
    height: auto;
    min-height: 100px;
    max-height: 10vh;
    padding: 0 20px 0 20px;
    display: flex;
    justify-content: space-around;
    align-items: center;
    background-color: rgb(255, 255, 255);
    color: black;
    border-top: 1px solid #ddd;
    font-size: clamp(1rem, 2vw, 1.5rem);
    flex-wrap: wrap;
}

.logo-footer {
    min-width: 180px;
    min-height: 50px;
    max-width: 10vw;
    max-height: 10vh;
    object-fit: contain;
}
</style>
