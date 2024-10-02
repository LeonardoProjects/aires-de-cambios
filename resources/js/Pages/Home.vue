<template>
    <section class="hero-section">
        <div class="filter-banner-div">
            <img
                src="../../images/heroSection.jpg"
                alt="Banner"
                class="banner-image"
            />
        </div>
        <div class="banner-text">
            <h1>Aires de cambios</h1>
            <p>
                Una herramienta que te ayudará a ventilar tu hogar de forma
                adecuada para mantener una buena calidad del aire.
            </p>

            <a v-if="!isMobile" href="/herramienta" class="try-button"
                >Probar herramienta</a
            >
        </div>
        <a v-if="isMobile" href="/herramienta" class="try-button-mobile"
            >Probar herramienta</a
        >
    </section>
    <section class="app-info my-5 d-flex justify-content-center">
        <div class="text-center w-50">
            <div class="content">
                <h4>¿Qué es Aires de cambios?</h4>
                <span class="description">
                    Es una herramienta que utiliza datos proporcionados por la
                    API meteorológica de
                </span>
                <a href="https://www.weatherapi.com/">Weather API</a>
                <span class="description">
                    y los ingresados por el usuario, como las dimensiones del
                    ambiente y la ventana, la ubicación, y demás. A partir de
                    estos datos, realiza cálculos para determinar la apertura de
                    una ventana en diferentes horarios, basándose en una
                    estimación de la calidad del aire.
                </span>
            </div>
        </div>
    </section>
    <section class="app-data">
        <h4>usuarios activos</h4>
        <div>grafica 1</div>
        <p>usuarios registrados</p>
        <div>grafica 2</div>
    </section>
    <section class="about-us">
        <p>
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Deleniti
            rem minus dignissimos libero sint atque, delectus odio eaque iusto
            sed quasi ea amet laborum illum. Amet eos neque eum magni.
        </p>
    </section>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";

import { onBeforeMount, ref, onMounted } from "vue";
export default {
    layout: AppLayout,
    setup() {
        const isMobile = ref(false);
        const checkDeviceSize = () => {
            isMobile.value = window.innerWidth < 768;
        };

        onMounted(() => {
            checkDeviceSize();
            window.addEventListener("resize", checkDeviceSize);
        });

        onBeforeMount(() => {
            window.removeEventListener("resize", checkDeviceSize);
        });

        return {
            isMobile,
        };
    },
};
</script>

<style lang="css" scoped>
.hero-section {
    height: 90vh;
    width: 100%;
}

.filter-banner-div {
    background: rgb(38, 38, 38);
    background: linear-gradient(
        180deg,
        rgba(38, 38, 38, 0) 0%,
        rgba(61, 61, 61, 0) 20%,
        rgba(0, 130, 217, 1) 85%,
        rgba(0, 117, 195, 1) 93%,
        rgba(3, 68, 111, 0.9962185557816877) 100%
    );
    height: 100%;
}

.banner-image {
    opacity: 70%;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.banner-text {
    position: absolute;
    overflow-wrap: break-word;
    width: max(35vw, 400px);
    top: 70%;
    left: 2%;
    color: white;
}

.try-button {
    border: solid 1px #ffffff;
    color: #ffffff;
    background-color: transparent;
    border-radius: 5px;
    padding: 10px;
    text-decoration: none;
    cursor: pointer;
}

.try-button:hover {
    background-color: #ffffff;
    color: #000000;
}

@media screen and (max-width: 768px) {
    .hero-section {
        position: relative;
        width: 100vw;
    }

    .filter-banner-div {
        padding-top: 200px;
    }

    .banner-text {
        color: black;
        position: absolute;
        top: 15%;
        left: 50%;
        translate: -50% -50%;
        text-align: center;
    }

    .try-button-mobile {
        border: solid 1px #ffffff;
        border-radius: 5px;
        padding: 10px;
        text-decoration: none;
        cursor: pointer;
        position: absolute;
        left: 50%;
        top: 90%;
        translate: -50% -50%;
        background-color: transparent;
        color: white;
    }

    .try-button-mobile:hover {
        background-color: #ffffff;
        color: #000000;
    }
}
</style>
