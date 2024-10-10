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
            <h1 class="fs-3">¿Qué es Aires de cambios?</h1>
            <div class="content fs-5 my-4">
                <span class="description">
                    Es una herramienta que utiliza tanto los datos
                    proporcionados por la API meteorológica de
                </span>
                <a href="https://www.weatherapi.com/">Weather API</a>
                <span class="description">
                    como los datos ingresados por el usuario para realizar
                    cálculos que determinan la apertura óptima de una ventana en
                    distintos horarios, con el fin de asegurar una ventilación
                    adecuada del ambiente.
                </span>
            </div>
        </div>
    </section>
    <section class="app-data">
        <div
            class="ambientesXPaises w-100 d-flex flex-column align-items-center"
        >
            <div class="chartDescription">
                <h4 class="text-center">Ambientes creados</h4>
                <p class="text-center">
                    Cantidad de ambientes creados en diferentes ubicaciones:
                </p>
            </div>
            <CountryMap />
        </div>
    </section>
    <section class="about-us my-5 d-flex flex-column align-items-center">
        <div class="aboutUsPresentation text-center w-50">
            <h1>Sobre nosotros</h1>
            <p>
                El proyecto surge de una versión de la herramienta previamente
                realizada por el Área Clima y Confort del Instituto de
                Tecnologías de la FADU - UdelaR, esta misma fue adaptada a una
                aplicación web por estudiantes de la carrera de Tecnólogo en
                Informática de Paysandú.
            </p>
        </div>
        <div class="aboutUsDescription d-flex justify-content-center my-5">
            <a
                target="_blank"
                href="https://www.fadu.edu.uy/"
                class="aboutFADU align-items-center d-flex"
            >
                <img src="../../images/fadu.png" class="logo-aboutUs" alt="" />
                <div>
                    <h2 class="fs-5">
                        FADU - UdelaR (Facultad de Arquitectura, Diseño y
                        Urbanismo)
                    </h2>
                    <p class="pe-4">
                        La Facultad de Arquitectura, Diseño y Urbanismo (FADU -
                        UdelaR) de la Universidad de la República es reconocida
                        por formar profesionales en arquitectura, diseño
                        industrial y comunicación visual. Con un enfoque
                        interdisciplinario, fomenta la creatividad, la
                        innovación y el desarrollo sustentable.
                    </p>
                </div>
            </a>
            <a
                target="_blank"
                href="https://utec.edu.uy/es/educacion/carrera/tecnologo-en-informatica/"
                class="aboutTIPY align-items-center d-flex"
            >
                <img
                    src="../../images/tipy.png"
                    class="logo-aboutUs utecLogo"
                    alt=""
                />
                <div>
                    <h2 class="fs-5">Tecnólogo en Informática</h2>
                    <p>
                        El Tecnólogo en Informática es una carrera co-gestionada
                        por UTEC, UdelaR y DGETP-UTU ofrecida en varios
                        puntos de Uruguay. Está orientada a la formación de
                        profesionales con competencias técnicas en áreas como
                        desarrollo de software, bases de datos y redes de
                        comunicación. El programa combina una sólida base
                        teórica con práctica aplicada, preparando a los
                        estudiantes para enfrentar los desafíos tecnológicos
                        actuales y contribuir al crecimiento del sector
                        informático en Uruguay.
                    </p>
                </div>
            </a>
        </div>
    </section>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import CountryMap from "@/Components/EnvironmentsPerCountryChart.vue";

import { onBeforeMount, ref, onMounted } from "vue";
export default {
    layout: AppLayout,
    components: {
        CountryMap,
    },
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
    props: {
        ambientesPorPais: Array,
    },
    mounted() {},
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

.studentsLink {
    text-decoration: none;
    color: #0022e0;
}

.studentsLink:hover {
    color: #0068e0;
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

.logo-aboutUs {
    margin: 20px;
    min-width: 100px;
    min-height: 100%;
    max-width: 10vw;
    max-height: 10vh;
    object-fit: contain;
}

.anchorTitle {
    text-decoration: none;
    color: black;
    width: 20%;
    display: flex;
}

.utecLogo {
    padding-bottom: 20px;
}

.aboutLogos {
    padding: 40px;
    width: 84%;
    position: relative;
    z-index: 1;
    transition: background 0.5s ease-in-out;
    border-radius: 10px;
    text-decoration: none;
    color: black;
}

.aboutFADU {
    padding: 40px;
    margin-right: 3vw;
    max-width: 40%;
    border: solid 1px #e5801c;
    background: white;
    position: relative;
    z-index: 1;
    transition: background 0.5s ease-in-out;
    border-radius: 10px;
    text-decoration: none;
    color: black;
    cursor: pointer;
}

.aboutFADU::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        180deg,
        rgba(229, 128, 28, 0.3) 0%,
        rgba(229, 128, 28, 0.4) 47%,
        rgba(229, 128, 28, 7) 100%
    );
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    z-index: -1;
}

.aboutFADU:hover::before {
    opacity: 1;
    animation: gradientSlide 1s ease-in-out forwards;
}

@keyframes gradientSlide {
    0% {
        background-position: 0% 100%;
    }
    100% {
        background-position: 0% 0%;
    }
}

.aboutTIPY {
    padding: 40px;
    max-width: 40%;
    border: solid 1px #00a9e0;
    background: white;
    position: relative;
    z-index: 1;
    transition: background 0.5s ease-in-out;
    border-radius: 10px;
    text-decoration: none;
    color: black;
    cursor: pointer;
}

.aboutTIPY::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        180deg,
        rgba(0, 169, 224, 0.3) 0%,
        rgba(0, 169, 224, 0.4) 47%,
        rgba(0, 169, 224, 0.7) 100%
    );
    opacity: 0;
    transition: opacity 0.5s ease-in-out;
    z-index: -1;
}

.aboutTIPY:hover::before {
    opacity: 1;
    animation: gradientSlideUTEC 1s ease-in-out forwards;
}

@keyframes gradientSlideUTEC {
    0% {
        background-position: 0% 100%;
        /* Inicia desde la parte inferior */
    }

    100% {
        background-position: 0% 0%;
        /* El gradiente se desplaza hacia arriba */
    }
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
        top: 93%;
        translate: -50% -50%;
        background-color: transparent;
        color: white;
    }

    .try-button-mobile:hover {
        background-color: #ffffff;
        color: #000000;
    }

    .aboutUsDescription {
        display: flex;
        flex-direction: column;
    }

    .aboutFADU {
        padding: 40px;
        max-width: 877px;
        margin: 10vw;
        border: solid 1px #e5801c;
        background: white;
        position: relative;
        z-index: 1;
        transition: background 0.5s ease-in-out;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    .logo-aboutUs {
        margin: 20px;
        min-width: 200px;
        min-height: 100px;
        max-width: 10vw;
        max-height: 10vh;
        object-fit: contain;
    }

    .aboutTIPY {
        padding: 40px;
        border: solid 1px #00a9e0;
        margin: 10vw;
        max-width: 877px;
        background: white;
        position: relative;
        z-index: 1;
        transition: background 0.5s ease-in-out;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }
}

@media screen and (max-width: 1440px) {
    .aboutUsDescription {
        display: flex;
        flex-direction: column;
    }

    .aboutFADU {
        padding: 40px;
        max-width: 877px;
        margin: 10vw;
        border: solid 1px #e5801c;
        background: white;
        position: relative;
        z-index: 1;
        transition: background 0.5s ease-in-out;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }
    .aboutTIPY {
        padding: 40px;
        border: solid 1px #00a9e0;
        margin: 10vw;
        max-width: 877px;
        background: white;
        position: relative;
        z-index: 1;
        transition: background 0.5s ease-in-out;
        border-radius: 10px;
        text-decoration: none;
        color: black;
        cursor: pointer;
        display: flex;
        flex-direction: column;
    }
}
</style>
