<template>
    <div v-if="datosCalculos.length != 0" class="d-flex justify-content-center flex-column w-75 text-center">
        <!-- Iterar sobre los grupos de resultados agrupados por fecha -->
        <div v-for="(resultadosPorFecha, date) in agrupadosPorFecha" :key="date" class="mb-4">
            <!-- Mostrar la fecha por encima de la tabla -->
            <h3 class="text-center">{{ date }}</h3>
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
                            <strong>{{ resultado.hour }}</strong>
                            <br />
                            <img v-if="resultado.alert" v-bind:src="getImage(resultado.alert)" alt=""
                                style="height: 20px; width: 20px" />
                            Precaución por vientos fuertes (mayores a 40 km/h) Apertura: {{ resultado.cm }}cm
                            <br />
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
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}

.resultsTable {
    width: 700px;
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

    display: flex;
    justify-content: center;
    align-items: center;
}

.resultsHourExpanded {
    margin-bottom: 20px;
    background-color: #f1f1f1;
    -webkit-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    -moz-box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    box-shadow: 0px 3.87px 3.87px 0px rgba(0, 0, 0, 0.25);
    border-radius: 7px;
    min-height: 50px;
    max-height: 100px;
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
    display: inline-block;
    min-height: 30px;
    max-height: 100%;
    padding: 3%;
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
.claseFrioExpanded:hover {}

.claseLluviaExpanded:hover {}

.claseVientoExpanded:hover {}

.claseTormentaExpanded:hover {}

.claseAgradableExpanded:hover {}
</style>