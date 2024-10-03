<template>
    <div v-if="datosCalculos.length != 0" class="d-flex justify-content-center flex-column w-75 text-center">
        <!-- Iterar sobre los grupos de resultados agrupados por fecha -->
        <div v-for="(resultadosPorFecha, date) in agrupadosPorFecha" :key="date" class="mb-4">
            <!-- Mostrar la fecha por encima de la tabla -->
            <h3 class="text-end">{{ date }}</h3>
            <div class="d-flex justify-content-center">
                <div class="text-center resultsTable">
                    <div v-for="(resultado, index) in resultadosPorFecha" :key="index" @click="toggleRow(index)"
                        role="button" :class="[resultado.expanded ? 'resultsHourExpanded' : 'resultsHourMinimized']">

                        <div v-if="!resultado.expanded" :class="['hourRow', {
                            claseLluviaMinimized: resultado.alert == 'Precaución por lluvias',
                            claseFrioMinimized: resultado.alert == 'Precaución por bajas temperaturas (menor a 10° C)',
                            claseVientoMinimized: resultado.alert == 'Precaución por vientos fuertes (mayores a 40 km/h)',
                            claseAgradableMinimized: resultado.alert == 'Aprovechar temperaturas agradables (mayor a 18° C)',
                            claseTormentaMinimized: resultado.alert == 'Precaución por tormentas fuertes'
                        }]">
                            <span class="ps-2">{{ resultado.hour }}</span>
                            <span>{{ resultado.cm }} cm</span>
                            <span>
                                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                    <path d="M0 0h24v24H0z" fill="none" />
                                    <circle cx="12" cy="12" r="10" stroke="black" stroke-width="2" fill="none" />
                                    <polygon points="8,10 16,10 12,14" fill="black" />
                                </svg>
                            </span>
                        </div>
                        <span :class="['text-center', 'resultExpanded', {
                            claseLluviaExpanded: resultado.alert == 'Precaución por lluvias',
                            claseFrioExpanded: resultado.alert == 'Precaución por bajas temperaturas (menor a 10° C)',
                            claseVientoExpanded: resultado.alert == 'Precaución por vientos fuertes (mayores a 40 km/h)',
                            claseAgradableExpanded: resultado.alert == 'Aprovechar temperaturas agradables (mayor a 18° C)',
                            claseTormentaExpanded: resultado.alert == 'Precaución por tormentas fuertes'
                        }]" v-else>
                            <div class="dUP">
                                <strong>{{ resultado.hour }}</strong>
                                <span class="m-0">Apertura<br><span class="fs-3">{{ resultado.cm }}cm</span></span>
                            </div>
                            <div class="dDOWN">
                                <div class="alertIcons">
                                    <!-- frio  -->
                                    <div class="iconFrio">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M22 11h-4.17l3.24-3.24-1.41-1.42L15 11h-2V9l4.66-4.66-1.42-1.41L13 6.17V2h-2v4.17L7.76 2.93 6.34 4.34 11 9v2H9L4.34 6.34 2.93 7.76 6.17 11H2v2h4.17l-3.24 3.24 1.41 1.42L9 13h2v2l-4.66 4.66 1.42 1.41L11 17.83V22h2v-4.17l3.24 3.24 1.42-1.41L13 15v-2h2l4.66 4.66 1.41-1.42L17.83 13H22z" />
                                        </svg>
                                    </div>

                                    <!-- viento -->
                                    <div class="iconViento">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path
                                                d="M17 16.99c-1.35 0-2.2.42-2.95.8-.65.33-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6-.75-.38-1.57-.8-2.95-.8s-2.2.42-2.95.8c-.65.33-1.17.6-2.05.6v1.95c1.35 0 2.2-.42 2.95-.8.65-.33 1.17-.6 2.05-.6s1.4.25 2.05.6c.75.38 1.57.8 2.95.8s2.2-.42 2.95-.8c.65-.33 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.58.8 2.95.8v-1.95c-.9 0-1.4-.25-2.05-.6-.75-.38-1.6-.8-2.95-.8zm0-4.45c-1.35 0-2.2.43-2.95.8-.65.32-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6-.75-.38-1.57-.8-2.95-.8s-2.2.43-2.95.8c-.65.32-1.17.6-2.05.6v1.95c1.35 0 2.2-.43 2.95-.8.65-.35 1.15-.6 2.05-.6s1.4.25 2.05.6c.75.38 1.57.8 2.95.8s2.2-.43 2.95-.8c.65-.35 1.15-.6 2.05-.6s1.4.25 2.05.6c.75.38 1.58.8 2.95.8v-1.95c-.9 0-1.4-.25-2.05-.6-.75-.38-1.6-.8-2.95-.8zm2.95-8.08c-.75-.38-1.58-.8-2.95-.8s-2.2.42-2.95.8c-.65.32-1.18.6-2.05.6-.9 0-1.4-.25-2.05-.6-.75-.37-1.57-.8-2.95-.8s-2.2.42-2.95.8c-.65.33-1.17.6-2.05.6v1.93c1.35 0 2.2-.43 2.95-.8.65-.33 1.17-.6 2.05-.6s1.4.25 2.05.6c.75.38 1.57.8 2.95.8s2.2-.43 2.95-.8c.65-.32 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.58.8 2.95.8V5.04c-.9 0-1.4-.25-2.05-.58zM17 8.09c-1.35 0-2.2.43-2.95.8-.65.35-1.15.6-2.05.6s-1.4-.25-2.05-.6c-.75-.38-1.57-.8-2.95-.8s-2.2.43-2.95.8c-.65.35-1.15.6-2.05.6v1.95c1.35 0 2.2-.43 2.95-.8.65-.32 1.18-.6 2.05-.6s1.4.25 2.05.6c.75.38 1.57.8 2.95.8s2.2-.43 2.95-.8c.65-.32 1.18-.6 2.05-.6.9 0 1.4.25 2.05.6.75.38 1.58.8 2.95.8V9.49c-.9 0-1.4-.25-2.05-.6-.75-.38-1.6-.8-2.95-.8z" />
                                        </svg>
                                    </div>

                                    <!-- tormenta -->
                                    <div class="iconTormenta">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0z" fill="none" />
                                            <path d="M7 2v11h3v9l7-12h-4l4-8z" />
                                        </svg>
                                    </div>

                                    <!-- agradable -->
                                    <div class="iconAgradable">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                            <path d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M6.76 4.84l-1.8-1.79-1.41 1.41 1.79 1.79 1.42-1.41zM4 10.5H1v2h3v-2zm9-9.95h-2V3.5h2V.55zm7.45 3.91l-1.41-1.41-1.79 1.79 1.41 1.41 1.79-1.79zm-3.21 13.7l1.79 1.8 1.41-1.41-1.8-1.79-1.4 1.4zM20 10.5v2h3v-2h-3zm-8-5c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6zm-1 16.95h2V19.5h-2v2.95zm-7.45-3.91l1.41 1.41 1.79-1.8-1.41-1.41-1.79 1.8z" />
                                        </svg>
                                    </div>

                                    <!-- lluvia -->
                                    <div class="iconLluvia">
                                        <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24"
                                            height="24" viewBox="0 0 24 24" width="24">
                                            <rect fill="none" height="24" width="24" />
                                            <path
                                                d="M12,2c-5.33,4.55-8,8.48-8,11.8c0,4.98,3.8,8.2,8,8.2s8-3.22,8-8.2C20,10.48,17.33,6.55,12,2z M7.83,14 c0.37,0,0.67,0.26,0.74,0.62c0.41,2.22,2.28,2.98,3.64,2.87c0.43-0.02,0.79,0.32,0.79,0.75c0,0.4-0.32,0.73-0.72,0.75 c-2.13,0.13-4.62-1.09-5.19-4.12C7.01,14.42,7.37,14,7.83,14z" />
                                        </svg>
                                    </div>
                                </div>
                                <p class="m-0">{{ resultado.alert }}</p>
                            </div>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="d-flex justify-content-center flex-column w-75 text-center">
        <p class="fs-5">¡Crea un ambiente para saber cómo ventilar!</p>
    </div>
</template>

<script>
import thunderIcon from "../../../images/icons/thunder.png";

export default {
    props: {
        idAmbiente: Number,
        cantPersonas: Number,
    },
    data() {
        return {
            datosCalculos: [],
            resultados: [],
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
    },
    methods: {
        toggleRow(index) {
            this.resultados.forEach((resultado, idx) => {
                if (idx !== index) resultado.expanded = false;
            });
            this.resultados[index].expanded = !this.resultados[index].expanded;
        },
        getImage(alert) {
            if (alert === "Precaución por lluvias") {
                return thunderIcon;
            }
            return "";
        },
        async cargarDatos() {
            try {
                if (this.idAmbiente != -1) {
                    let response = await axios.get(
                        this.route("resultados.index", {
                            idAmbiente: this.idAmbiente,
                            cantPersonas: this.cantPersonas
                        })
                    );
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
                for (let i = 0; i < 26; i++) {
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

            let currentDate = new Date(localTime);
            let tomorrowDate = new Date(localTime);
            tomorrowDate.setDate(currentDate.getDate() + 1);

            let formattedCurrentDate = currentDate.toISOString().slice(0, 10);
            let formattedTomorrowDate = tomorrowDate.toISOString().slice(0, 10);

            tenHoursData.forEach((resultado, index) => {
                let date =
                    index +
                        Number(this.roundDownHour(localTime).split(":")[0]) <
                        24
                        ? formattedCurrentDate
                        : formattedTomorrowDate;
                let dateFecha = new Date(date).toLocaleDateString('es-UY', {
                    day: "numeric",
                    month: "long",
                    year: "numeric",
                    timeZone: "America/Argentina/Buenos_Aires"
                });
                this.resultados.push({
                    hour: resultado.hora,
                    cm: resultado.apertura,
                    expanded: false,
                    date: dateFecha,
                    icon: "icono",
                    alert: resultado.alerta,
                });
            });
        },
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

.dDOWN {
    display: flex;
    width: 100%;
    justify-content: space-between;
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
    flex-shrink: 0;
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
    width: auto;
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
    background: linear-gradient(180deg, rgba(46,65,255,1) 0%, rgba(105,133,206,1) 57%);
}

.claseVientoExpanded {
    background: linear-gradient(180deg, rgba(205,126,7,1) 0%, rgba(226,168,80,1) 100%);
}

.claseTormentaExpanded {
    background: linear-gradient(180deg, rgba(100,67,178,1) 0%, rgba(161,152,243,1) 100%);
}

.claseAgradableExpanded {
    background: linear-gradient(180deg, rgba(63, 161, 61, 1) 0%, rgba(118, 218, 116, 1) 100%);
}
</style>