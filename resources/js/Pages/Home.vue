<template>
    <button id="btnVolverArriba" ref="btnVolverArriba" @click="scrollArriba" :style="{ bottom: footerVisible ? (footerHeight + 30) + 'px' : '20px'}">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-up-short"
            viewBox="0 0 16 16">
            <path fill-rule="evenodd"
                d="M8 12a.5.5 0 0 0 .5-.5V5.707l2.146 2.147a.5.5 0 0 0 .708-.708l-3-3a.5.5 0 0 0-.708 0l-3 3a.5.5 0 1 0 .708.708L7.5 5.707V11.5a.5.5 0 0 0 .5.5" />
        </svg>
    </button>
    <section class="hero-section">
        <div class="filter-banner-div">
            <img src="../../images/heroSection.jpg" alt="Banner" class="banner-image" />
        </div>
        <div class="banner-text">
            <h1>Aires de cambios</h1>
            <p>
                Una herramienta que te ayudará a ventilar tu hogar de forma
                adecuada para mantener una buena calidad del aire.
            </p>

            <a v-if="!isMobile" href="/herramienta" class="try-button">Probar herramienta</a>
        </div>
        <a v-if="isMobile" href="/herramienta" class="try-button-mobile">Probar herramienta</a>
    </section>
    <div class="custom-shape-divider-top-1728974773">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path
                d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"
                class="shape-fill"></path>
        </svg>
    </div>
    <section id="webIntroduction">
        <div class="app-info mb-5 pt-5 d-flex justify-content-center">
            <div class="text-center w-50 introductionText">
                <h1 class="fs-3">¿Qué es Aires de cambios?</h1>
                <div class="content fs-5 my-4 descriptionText w-100">
                    <span class="description">
                        Es una herramienta que, a partir de un modelo simplificado, utiliza tanto los datos proporcionados
                        por la API meteorológica de
                    </span>
                    <a href="https://www.weatherapi.com/">Weather API</a>
                    <span class="description">
                        como los datos ingresados por el usuario para realizar
                        cálculos que determinan la apertura óptima de una ventana en distintos horarios, con el fin de
                        asegurar una ventilación adecuada del local.
                    </span>
                </div>
            </div>
        </div>
        <div class="app-data pb-5 d-md-block d-none">
            <div class="ambientesXPaises w-100 d-flex flex-column align-items-center">
                <div class="chartDescription">
                    <h4 class="text-center">Locales creados</h4>
                    <p class="text-center">
                        Cantidad de locales creados en diferentes ubicaciones:
                    </p>
                </div>
                <CountryMap />
            </div>
        </div>
    </section>
    <div class="custom-shape-divider-top-1728974654">
        <svg data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
            <path
                d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                opacity=".25" class="shape-fill"></path>
            <path
                d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                opacity=".5" class="shape-fill"></path>
            <path
                d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                class="shape-fill"></path>
        </svg>
    </div>
    <section id="sobreNosotros" class="about-us my-3 d-flex flex-column align-items-center">
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
            <a target="_blank" href="https://www.fadu.edu.uy/" class="aboutFADU align-items-center d-flex">
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
            <a target="_blank" href="https://utec.edu.uy/es/educacion/carrera/tecnologo-en-informatica/"
                class="aboutTIPY align-items-center d-flex">
                <img src="../../images/tipy.png" class="logo-aboutUs utecLogo" alt="" />
                <div>
                    <h2 class="fs-5">Tecnólogo en Informática</h2>
                    <p>
                        El Tecnólogo en Informática es una carrera co-gestionada
                        por UTEC, UdelaR y DGETP-UTU ofrecida en varios puntos
                        de Uruguay. Está orientada a la formación de
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
    <section id="contactUsSection" class="d-flex flex-column align-items-center">
        <h1 class="my-3">Contáctanos</h1>
        <span class="d-flex my-2"><svg class="me-1" xmlns="http://www.w3.org/2000/svg" height="24px"
                viewBox="0 -960 960 960" width="24px" fill="#000000">
                <path
                    d="M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h640q33 0 56.5 23.5T880-720v480q0 33-23.5 56.5T800-160H160Zm320-280L160-640v400h640v-400L480-440Zm0-80 320-200H160l320 200ZM160-640v-80 480-400Z" />
            </svg>
            <a target="_blank" href="mailto:decca@fadu.edu.uy"
                class="text-decoration-none d-flex">decca@fadu.edu.uy</a></span>
        <span class="d-flex my-2 ContactUSDirection">
            <svg class="me-1" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                fill="#000000">
                <path
                    d="M480-480q33 0 56.5-23.5T560-560q0-33-23.5-56.5T480-640q-33 0-56.5 23.5T400-560q0 33 23.5 56.5T480-480Zm0 294q122-112 181-203.5T720-552q0-109-69.5-178.5T480-800q-101 0-170.5 69.5T240-552q0 71 59 162.5T480-186Zm0 106Q319-217 239.5-334.5T160-552q0-150 96.5-239T480-880q127 0 223.5 89T800-552q0 100-79.5 217.5T480-80Zm0-480Z" />
            </svg>
            <p>
                Bv. Gral. Artigas 1031, 11200 Montevideo, Departamento de
                Montevideo
            </p>
        </span>
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
        let btnVolverArriba = ref(null);
        const footerVisible = ref(false);
        const footerHeight = ref(0);
        
        const checkDeviceSize = () => {
            isMobile.value = window.innerWidth < 768;
        };

        const scrollArriba = () => {
            window.scrollTo({ top: 0, behavior: "smooth" });
        };

        const mostrarBtnScroll = () => {
            if (
                document.body.scrollTop > 100 ||
                document.documentElement.scrollTop > 100
            ) {
                btnVolverArriba.value.style.display = "block";
            } else {
                btnVolverArriba.value.style.display = "none";
            }
        };

        const visibilidadFooter = () => {
            const footer = document.querySelector("footer");
            if (footer) {
                const footerRect = footer.getBoundingClientRect();
                footerVisible.value = footerRect.top < window.innerHeight && footerRect.bottom >= 0;
            }
        };

        onMounted(() => {
            const footer = document.querySelector("footer");
            checkDeviceSize();
            window.addEventListener("resize", checkDeviceSize);
            btnVolverArriba.value = document.getElementById("btnVolverArriba");

            window.addEventListener("scroll", mostrarBtnScroll);
            window.addEventListener("scroll", visibilidadFooter);

            if (footer) {
                footerHeight.value = footer.offsetHeight;
            }
        });

        onBeforeMount(() => {
            window.removeEventListener("resize", checkDeviceSize);
            window.addEventListener("scroll", mostrarBtnScroll);
        });

        return {
            isMobile,
            scrollArriba,
            footerVisible,
            footerHeight
        };
    },
    props: {
        ambientesPorPais: Array,
    }
};
</script>

<style lang="css" scoped>
#webIntroduction {
    background: linear-gradient(180deg,
            rgba(255, 255, 255, 1) 0%,
            rgba(170, 211, 223, 1) 40%,
            rgba(170, 211, 223, 1) 83%);
}

.custom-shape-divider-top-1728974773 {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
    transform: rotate(180deg);
}

.custom-shape-divider-top-1728974773 svg {
    position: relative;
    display: block;
    width: calc(138% + 1.3px);
    height: 40px;
}

.custom-shape-divider-top-1728974773 .shape-fill {
    fill: #112431;
}

.custom-shape-divider-top-1728974654 {
    position: relative;
    top: 0;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
}

.custom-shape-divider-top-1728974654 svg {
    position: relative;
    display: block;
    width: calc(138% + 1.3px);
    height: 100px;
}

.custom-shape-divider-top-1728974654 .shape-fill {
    fill: #aad3df;
}

.hero-section {
    height: 90vh;
    width: 100%;
}

.filter-banner-div {
    background: rgb(38, 38, 38);
    background: linear-gradient(180deg,
            rgba(38, 38, 38, 0) 0%,
            rgba(61, 61, 61, 0) 20%,
            rgba(0, 130, 217, 1) 85%,
            rgba(0, 117, 195, 1) 93%,
            rgba(3, 68, 111, 0.9962185557816877) 100%);
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

#btnVolverArriba {
    position: fixed;
    bottom: 30px;
    right: 30px;
    z-index: 100;
    padding: 10px 20px;
    font-size: 18px;
    background-color: #0099ff;
    color: white;
    border: none;
    border-radius: 50px;
    display: none;
    cursor: pointer;
    transition: bottom 0.3s ease-in-out;
}

#btnVolverArriba:hover {
    background-color: transparent;
    color: black;
    border: solid 1px black;
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
    background: linear-gradient(180deg,
            rgba(229, 128, 28, 0.3) 0%,
            rgba(229, 128, 28, 0.4) 47%,
            rgba(229, 128, 28, 7) 100%);
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
    background: linear-gradient(180deg,
            rgba(0, 169, 224, 0.3) 0%,
            rgba(0, 169, 224, 0.4) 47%,
            rgba(0, 169, 224, 0.7) 100%);
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

@media screen and (max-width: 767px) {

    #sobreNosotros {
        margin-bottom: 0px !important;
    }

    .custom-shape-divider-top-1728974654 {
        width: 100vw !important;
    }

    .leaflet-control-attribution {
        a {
            font-size: 1rem !important;
        }
    }
    .ContactUSDirection {
        width: 70vw;

        svg {
            width: 60px;
        }
    }

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
        width: 90vw !important;
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
        margin-bottom: 0px !important;
        width: 100vw !important;
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

    .introductionText {
        width: 75% !important;
    }

    .aboutUsPresentation {
        width: 75% !important;
    }

    .app-info {
        margin-bottom: 0 !important;
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
