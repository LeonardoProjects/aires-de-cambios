<template>
    <div v-if="loaderVisibleShow" class="loader"></div>
    <div v-else class="d-flex">
        <div v-if="datosCalculos.length != 0" class="d-flex justify-content-center flex-column text-center">
            <!-- Iterar sobre los grupos de resultados agrupados por fecha -->
            <div v-for="(resultadosPorFecha, date, fechaIndex) in agrupadosPorFecha" :key="date" class="mb-4">
                <!-- Mostrar la fecha por encima de la tabla -->
                <h3 class="text-center fechaTabla">{{ date }}</h3>
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
                            }]" v-else>
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
                </div>
            </div>
        </div>
        <div v-else class="d-flex justify-content-center flex-column w-100 text-center">
            <p class="fs-5">¡Crea un local para saber cómo ventilar!</p>
        </div>
    </div>
</template>

<script>
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";
import AlertIcon from "@/Components/AlertIcon.vue";
import { DateTime } from 'luxon';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

export default {
    props: {
        idAmbiente: Number,
        cantPersonas: Number,
    },
    components: {
        AlertIcon
    },
    data() {
        return {
            datosCalculos: [],
            resultados: [],
            loaderVisibleShow: false
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
            const target = event.target;

            // Si se hace clic en el SVG con la clase `custom-svg`, no ejecutar el toggle
            if (target.closest('.custom-svg')) {
                return; // Salir de la función si se hace clic en ese SVG específico
            }

            // Lógica para expandir/cerrar las filas si no se hizo clic en el SVG específico
            this.resultados.forEach((resultado, idx) => {
                if (idx !== index) resultado.expanded = false;
            });

            this.resultados[index].expanded = !this.resultados[index].expanded;
        },
        async cargarDatos() {
            try {
                this.loaderVisibleShow = true;
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
                this.loaderVisibleShow = false;
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
            this.loaderVisibleShow = false;
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
        }
    },
    mounted() {
        this.loaderVisibleShow = true;
        this.cargarDatos();
        this.loaderVisibleShow = false;
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

.fechaTabla{
    margin-bottom: 15px;
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