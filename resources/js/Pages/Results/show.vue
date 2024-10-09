<template>
    <div v-if="datosCalculos.length != 0" class="d-flex justify-content-center flex-column text-center">
        <!-- Iterar sobre los grupos de resultados agrupados por fecha -->
        <div v-for="(resultadosPorFecha, date, fechaIndex) in agrupadosPorFecha" :key="date" class="mb-4">
            <!-- Mostrar la fecha por encima de la tabla -->
            <h3 class="text-center">{{ date }}</h3>
            <div class="d-flex justify-content-center">
                <div class="text-center resultsTable">
                    <div v-for="(resultado, index) in resultadosPorFecha" :key="getGlobalIndex(fechaIndex, index)"
                        @click="toggleRow(getGlobalIndex(fechaIndex, index), $event)" role="button"
                        :class="[resultado.expanded ? 'resultsHourExpanded' : 'resultsHourMinimized']">
                        <div v-if="!resultado.expanded" :class="['hourRow', {
                            claseLluviaMinimized: resultado.alertas[0] == 'Precaución por lluvias',
                            claseFrioMinimized: resultado.alertas[0] == 'Precaución por bajas temperaturas (menor a 10° C)',
                            claseVientoMinimized: resultado.alertas[0] == 'Precaución por vientos fuertes (mayores a 40 km/h)',
                            claseAgradableMinimized: resultado.alertas[0] == 'Aprovechar temperaturas agradables (mayor a 18° C)',
                            claseTormentaMinimized: resultado.alertas[0] == 'Precaución por tormentas fuertes',
                            claseNadaMinimized: resultado.alertas[0] == ''
                        }]">
                            <div class="d-flex w-33">
                                <div class="iconAlert">
                                    <AlertIcon :alertName="getAlertName(resultado.alertas[0])" />
                                </div>
                                <span class="ps-2">{{ resultado.hour }}</span>
                            </div>
                            <span class="w-33">{{ resultado.cm }} cm</span>
                            <span class="w-33 d-flex justify-content-end">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="10" stroke="black" stroke-width="2" fill="none" />
                                    <polygon points="8,10 16,10 12,14" fill="black" />
                                </svg>
                            </span>
                        </div>
                        <span :class="['text-center', 'resultExpanded', {
                            claseLluviaExpanded: resultado.alertas[0] == 'Precaución por lluvias',
                            claseFrioExpanded: resultado.alertas[0] == 'Precaución por bajas temperaturas (menor a 10° C)',
                            claseVientoExpanded: resultado.alertas[0] == 'Precaución por vientos fuertes (mayores a 40 km/h)',
                            claseAgradableExpanded: resultado.alertas[0] == 'Aprovechar temperaturas agradables (mayor a 18° C)',
                            claseTormentaExpanded: resultado.alertas[0] == 'Precaución por tormentas fuertes',
                            claseNadaExpanded: resultado.alertas[0] == ''
                        }]" :data-originalAlert="{
                            'lluvia': resultado.alertas[0] == 'Precaución por lluvias',
                            'frio': resultado.alertas[0] == 'Precaución por bajas temperaturas (menor a 10° C)',
                            'viento': resultado.alertas[0] == 'Precaución por vientos fuertes (mayores a 40 km/h)',
                            'agradable': resultado.alertas[0] == 'Aprovechar temperaturas agradables (mayor a 18° C)',
                            'tormenta': resultado.alertas[0] == 'Precaución por tormentas fuertes',
                            'nada': resultado.alertas[0] == ''
                        }" v-else>
                            <div class="dUP">
                                <strong>{{ resultado.hour }}</strong>
                                <span class="m-0">Apertura de ventana<br><span class="fs-3">{{ resultado.cm
                                        }}cm</span></span>
                            </div>
                            <div class="dDOWN">
                                <div class="alertIcons">
                                    <template
                                        v-if="!isMobileHerramienta || (isMobileHerramienta && resultado.alertas.length < 4)"
                                        v-for="(alerta, index) in resultado.alertas" :key="index">
                                        <div v-if="alerta != ''">
                                            <AlertIcon :alertName="getAlertName(alerta)"
                                                :class="{ 'alertSelected': index == 0 }"
                                                @updateAlertName="cambiarAlerta" />
                                        </div>
                                    </template>
                                    <template v-else>
                                        <div>
                                            <AlertIcon :alertName="getAlertName(resultado.alertas[0])"
                                                :class="alertSelected" @updateAlertName="cambiarAlerta" />
                                            <AlertIcon :alertName="getAlertName(resultado.alertas[1])"
                                                @updateAlertName="cambiarAlerta" />
                                        </div>
                                        <div>
                                            <AlertIcon :alertName="getAlertName(resultado.alertas[2])"
                                                @updateAlertName="cambiarAlerta" />
                                            <AlertIcon :alertName="getAlertName(resultado.alertas[3])"
                                                @updateAlertName="cambiarAlerta" />
                                        </div>
                                    </template>
                                </div>
                                <p class="m-0 align-self-center alertName">{{ resultado.alertas[0] }}</p>
                            </div>
                        </span>
                    </div>
                </div>
                <button v-if="fechaIndex == 0" class="btn btn-outline-primary mx-1 rounded-5 p-0 px-2 botonTutorial"
                    @click="openTutorialModal">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
                        class="bi bi-patch-question" viewBox="0 0 16 16">
                        <path
                            d="M8.05 9.6c.336 0 .504-.24.554-.627.04-.534.198-.815.847-1.26.673-.475 1.049-1.09 1.049-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.7 1.7 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745" />
                        <path
                            d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z" />
                        <path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div v-else class="d-flex justify-content-center flex-column w-75 text-center">
        <p class="fs-5">¡Crea un local para saber cómo ventilar!</p>
    </div>
    <div v-if="showingTutorialModal" class="modal fade show d-block" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modalTutorial">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tutorialModalLabel">Aprende a Usar la Aplicación</h5>
                    <button type="button" class="btn-close" @click="closeTutorialModal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal para mostrar el GIF -->
                <div class="modal-body text-center">
                    <img :src="currentGif.src" alt="⚠" class="img-fluid" />
                    <p class="mt-3">{{ currentGif.description }}</p>
                </div>

                <!-- Footer con los botones de navegación -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="previousGif"
                        :disabled="currentGifIndex === 0">
                        Anterior
                    </button>
                    <button type="button" class="btn btn-primary" @click="nextGif"
                        :disabled="currentGifIndex === gifs.length - 1">
                        Siguiente paso
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted, onBeforeMount } from "vue";
import AlertIcon from "@/Components/AlertIcon.vue";
import { DateTime } from 'luxon';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

export default {
    props: {
        idAmbiente: Number,
        cantPersonas: Number,
    },
    setup() {
        const isMobileHerramienta = ref(false);
        const checkDeviceSize = () => {
            isMobileHerramienta.value = window.innerWidth < 768;
        };

        onMounted(() => {
            checkDeviceSize();
            window.addEventListener("resize", checkDeviceSize);
        });

        onBeforeMount(() => {
            window.removeEventListener("resize", checkDeviceSize);
        });

        return {
            isMobileHerramienta,
        };
    },
    components: {
        AlertIcon
    },
    data() {
        return {
            datosCalculos: [],
            resultados: [],
            showingTutorialModal: false,
            currentGifIndex: 0,
            gifs: [{
                src: null,
                description: 'CREAR AMBIENTE'
            },
            {
                src: '/gifs/btnCrearAmbiente.gif',
                description: 'Paso 1: Click en el botón "+" para crear un ambiente.'
            },
            {
                src: '/gifs/detallesAmbiente.gif',
                description: 'Paso 2: Ingresa los campos: Nombre, dimensiones en metros y tipo de habitacion para el ambiente.',
            }, {
                src: '/gifs/ubicacionAmbiente.gif',
                description: 'Paso 3: Ingresa los campos: Altura, vivo en y la ubicación del ambiente o clickea en el mapa como se mostrará en el siguiente ejemplo.'
            }, {
                src: '/gifs/selectMapa.gif',
                description: 'Navega por el mapa y clickea un punto determinado'
            }, {
                src: '/gifs/detallesAmbiente.gif',
                description: 'Paso 4: Ingresa los campos con las dimensiones y selecciona la calidad de la ventana luego hacer click en el botón "Guardar cambios".'
            }, {
                src: '/gifs/resultados.gif',
                description: 'Paso 5: Se podrán visualizar los resultados con fecha, hora, icono de alerta y apertura de ventana con la posibilidad de desplegar para mas detalles.'
            }, {
                src: null,
                description: 'EDITAR AMBIENTE'
            }, {
                src: '/gifs/editarAmbiente.gif',
                description: 'Paso 1: Click en el botón "Editar" para cambiar los datos de un ambiente.'
            }, {
                src: null,
                description: 'ALTERNAR CANTIDAD DE PERSONAS'
            }, {
                src: '/gifs/cantidadPersonas.gif',
                description: 'Paso 1: Cambiar valor del campo "cant.personas" para visualizar diferentes resultados.'
            }
            ],
        };
    },
    watch: {
        idAmbiente: function (newVal, oldVal) {
            this.cargarDatos();
        },
        cantPersonas: function (newVal, oldVal) {
            this.cargarDatos();
        },
    },
    computed: {
        // Agrupar los resultados por fecha
        agrupadosPorFecha() {
            return this.resultados.reduce((acc, resultado) => {
                if (!acc[resultado.date]) {
                    acc[resultado.date] = [];
                }
                acc[resultado.date].push(resultado);
                return acc;
            }, {});
        },
        currentGif() {
            return this.gifs[this.currentGifIndex];
        }
    },
    methods: {
        getAlertName(alertName) {
            switch (alertName) {
                case 'Precaución por vientos fuertes (mayores a 40 km/h)':
                    return 'vientosIcon';
                case 'Precaución por bajas temperaturas (menor a 10° C)':
                    return 'frioIcon';
                case 'Precaución por tormentas fuertes':
                    return 'tormentasIcon';
                case 'Precaución por lluvias':
                    return 'lluviaIcon';
                case 'Aprovechar temperaturas agradables (mayor a 18° C)':
                    return 'agradableIcon';
                default:
                    return '';
            }
        },
        getAlertClass(alertName, divResults) {
            let pAlert = divResults.querySelector('.alertName');
            switch (alertName) {
                case 'vientosIcon':
                    pAlert.textContent = 'Precaución por vientos fuertes (mayores a 40 km/h)'
                    return 'claseVientoExpanded';
                case 'frioIcon':
                    pAlert.textContent = 'Precaución por bajas temperaturas (menor a 10° C)'
                    return 'claseFrioExpanded';
                case 'tormentasIcon':
                    pAlert.textContent = 'Precaución por tormentas'
                    return 'claseTormentaExpanded';
                case 'lluviaIcon':
                    pAlert.textContent = 'Precaución por lluvias'
                    return 'claseLluviaExpanded';
                case 'agradableIcon':
                    pAlert.textContent = 'Aprovechar temperaturas agradables (mayor a 18° C)'
                    return 'claseAgradableExpanded';
                default:
                    return '';
            }
        },
        getGlobalIndex(fechaIndex, index) {
            // Obtener el índice global sumando la cantidad de resultados anteriores.
            let count = 0;
            for (let i = 0; i < fechaIndex; i++) {
                count += this.agrupadosPorFecha[Object.keys(this.agrupadosPorFecha)[i]].length;
            }
            return count + index;
        },
        toggleRow(index, event) {
            if (event.target.tagName !== 'svg' && event.target.tagName !== 'path') {
                this.resultados.forEach((resultado, idx) => {
                    if (idx !== index) resultado.expanded = false;
                });
                this.resultados[index].expanded = !this.resultados[index].expanded;
            }
        },
        async cargarDatos() {
            try {
                if (this.idAmbiente == -2) {
                    let ambienteNotLogged = JSON.parse(localStorage.getItem('ambienteNotLogged'));
                    let response = await axios({
                        method: 'POST',
                        url: this.route("resultados.index"),
                        data: {
                            idAmbiente: this.idAmbiente,
                            cantPersonas: this.cantPersonas,
                            ambiente: ambienteNotLogged
                        }
                    });
                    this.datosCalculos = response.data;
                    this.setDatos();
                }
                else if (this.idAmbiente != -1) {
                    let response = await axios({
                        method: 'POST',
                        url: this.route("resultados.index"),
                        data: {
                            idAmbiente: this.idAmbiente,
                            cantPersonas: this.cantPersonas,
                            idUsuario: userId.value
                        }
                    });
                    this.datosCalculos = response.data;
                    this.setDatos();
                }
            } catch (error) {
                if (error.response) {
                    console.error(
                        "Error en la respuesta del servidor:",
                        error.response.status
                    );
                } else if (error.request) {
                    console.error(
                        "No se recibió respuesta del servidor:",
                        error.request
                    );
                } else {
                    console.error(
                        "Error en la configuración de la solicitud:",
                        error.message
                    );
                }
            }
        },
        roundDownHour(localTime) {
            let date = new Date(localTime);
            date.setMinutes(0, 0, 0);
            return date.toTimeString().slice(0, 5);
        },
        getTenHoursOfData(localTime, firstDayData, secondDayData) {
            let roundedLocalTime = this.roundDownHour(localTime);
            let combinedData = [...firstDayData, ...secondDayData];
            let result = [];

            let startIndex = combinedData.findIndex(
                (item) => item.hora === roundedLocalTime
            );

            if (startIndex !== -1) {
                for (let i = 0; i < 15; i++) {
                    result.push(combinedData[startIndex + i]);
                }
            }

            return result;
        },
        setDatos() {
            this.resultados = [];
            let localTime = this.datosCalculos["localTime"];
            let firstDayData = this.datosCalculos["firstDayData"];
            let secondDayData = this.datosCalculos["secondDayData"];
            let tenHoursData = this.getTenHoursOfData(
                localTime,
                firstDayData,
                secondDayData
            );
            let isoLocalTime = localTime.replace(" ", "T"); //Fixea localTime que llega de API para que sea ISO
            let currentDate = DateTime.fromISO(isoLocalTime, { zone: this.datosCalculos["timezone"] });
            let tomorrowDate = currentDate.plus({ days: 1 });

            let formattedCurrentDate = currentDate.toFormat('yyyy-MM-dd');
            let formattedTomorrowDate = tomorrowDate.toFormat('yyyy-MM-dd');

            tenHoursData.forEach((resultado, index) => {
                // Calcula la fecha correcta basado en la hora actual y la comparación
                let date = index + Number(this.roundDownHour(localTime).split(":")[0]) < 24
                    ? formattedCurrentDate
                    : formattedTomorrowDate;

                // Utiliza Luxon para crear un objeto DateTime con la zona horaria adecuada
                let dateWithTime = DateTime.fromISO(`${date}T00:00:00`, {
                    zone: this.datosCalculos["timezone"],
                });
                // Formatea la fecha en el formato deseado (día, mes, año)
                let dateFecha = dateWithTime.setLocale("es").toLocaleString(DateTime.DATE_FULL);

                this.resultados.push({
                    hour: resultado.hora,
                    cm: resultado.apertura,
                    expanded: false,
                    date: dateFecha,
                    icon: "icono",
                    alertas: resultado.alertas,
                });
            });
        },
        cambiarAlerta($alertName) {
            let results = document.querySelector('.resultExpanded');
            results.className = '';
            results.classList.add('text-center');
            results.classList.add('resultExpanded');
            results.classList.add(this.getAlertClass($alertName, results));
            // Clases para fade de color

            let alertDeselect = results.querySelector('.alertSelected');
            alertDeselect.className = '';

            let alertIcon = results.querySelector(`#${$alertName}`);
            alertIcon.parentElement.classList.add('alertSelected');
        },
        openTutorialModal() {
            this.showingTutorialModal = true;
        },
        closeTutorialModal() {
            this.showingTutorialModal = false;
        },
        nextGif() {
            if (this.currentGifIndex < this.gifs.length - 1) {
                this.currentGifIndex++;
            }
        },
        previousGif() {
            if (this.currentGifIndex > 0) {
                this.currentGifIndex--;
            }
        }
    },
    mounted() {
        this.cargarDatos();
    },
};
</script>

<style scoped>
.dUP {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
}

.w-33 {
    width: 33%;
}

.alertIcons {
    div {
        fill: #3D5A73;
    }
}

.alertSelected {
    fill: black !important;
}

.botonTutorial {
    max-height: 35px;
}

.dDOWN {
    display: flex;
    width: 100%;
    justify-content: space-between;
}

.iconAlert {
    width: 30px;
}

.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.resultsTable {
    width: 600px;
    height: auto;
}

.resultsHourMinimized {
    margin-bottom: 20px;
    background-color: #f1f1f1;
    -webkit-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    border-radius: 7px;
    min-height: 40px;
    width: 100%;

    display: flex;
    justify-content: center;
    align-items: center;
}

.resultsHourExpanded {
    width: 100%;
    margin-bottom: 20px;
    background-color: #f1f1f1;
    -webkit-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    border-radius: 7px;
    min-height: 50px;
    max-height: 120px;
}

.hourRow {
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 5px;

}

.resultsHourMinimized:hover {
    background-color: #e2e2e2;
}

.resultsHourExpanded:hover {
    background-color: #d9d9d9;
}

.resultExpanded {
    border-radius: 7px;
    display: flex;
    flex-direction: column;
    align-items: space-around;
    min-height: 30px;
    max-height: 100%;
    padding: 1.5%;
}

.alertIcons {
    display: flex;
    flex-flow: row wrap;
    align-content: center;
    width: auto;
    z-index: 999;
}

.claseFrioMinimized:hover {
    filter: invert(74%) sepia(61%) saturate(2366%) hue-rotate(169deg) brightness(84%) contrast(77%);
}

.claseLluviaMinimized:hover {
    filter: invert(14%) sepia(74%) saturate(2316%) hue-rotate(210deg) brightness(80%) contrast(116%);
}

.claseVientoMinimized:hover {
    filter: invert(71%) sepia(25%) saturate(5136%) hue-rotate(353deg) brightness(96%) contrast(99%);
}

.claseTormentaMinimized:hover {
    filter: invert(33%) sepia(96%) saturate(7476%) hue-rotate(270deg) brightness(79%) contrast(129%);
}

.claseAgradableMinimized:hover {
    filter: invert(23%) sepia(98%) saturate(2033%) hue-rotate(137deg) brightness(90%) contrast(104%);
}

/* separacion minimized expanded */
.claseFrioExpanded {
    background: linear-gradient(180deg, rgba(79, 158, 195, 1) 0%, rgba(162, 208, 229, 1) 100%);
}

.claseLluviaExpanded {
    background: linear-gradient(180deg, rgb(92, 119, 254) 0%, rgb(195, 205, 255) 100%);
}

.claseVientoExpanded {
    background: linear-gradient(180deg, rgba(205, 126, 7, 1) 0%, rgba(226, 168, 80, 1) 100%);
}

.claseTormentaExpanded {
    background: linear-gradient(180deg, rgb(139, 94, 228) 0%, rgb(181, 170, 244) 100%);
}

.claseAgradableExpanded {
    background: linear-gradient(180deg, rgb(101, 214, 72) 0%, rgb(165, 212, 164) 100%);
}

@media screen and (max-width: 767px) {
    .resultsTable {
        width: max(330px, 90vw);
    }

    .dDOWN {
        .alertIcons {
            flex-shrink: 0;
            width: max(18vw, 60px);
        }

        p {
            font-size: min(4vw, 1rem);
            text-align: end;
        }
    }
}
</style>