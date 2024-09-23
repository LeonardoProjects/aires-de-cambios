<template>
    <div class="d-flex justify-content-center flex-column min-vh-100 w-75 text-center">
        <h1>Resultados</h1>
        <div class="d-flex justify-content-center">
            <table border="1" align="center" cellpadding="10" class="text-center">
                <tr v-for="(resultado, index) in resultados" :key="index" @click="toggleRow(index)" role="button">
                    <template v-if="!resultado.expanded">
                        <td>{{ resultado.hour }}</td>
                        <td>{{ resultado.cm }} cm</td>
                        <td class="text-end">{{ resultado.expanded ? "▲" : "▼" }}</td>
                    </template>
                    <td class="text-center" colspan="3" v-else>
                        <strong>{{ resultado.date }}</strong>
                        <br />
                        <img v-bind:src="getImage(resultado.alert)"
                            alt="" style="height: 20px; width: 20px" />
                        {{ resultado.alert }}
                        - Apertura: {{ resultado.cm }}cm
                        <br />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
import thunderIcon from '../../../images/icons/thunder.png';

export default {
    props: { idAmbiente: Number },
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
    },
    methods: {
        toggleRow(index) {
            this.resultados.forEach((resultado, idx) => {
                if (idx !== index) resultado.expanded = false;
            });
            this.resultados[index].expanded = !this.resultados[index].expanded;
        },
        getImage(alert) {
            // Verifica la alerta y devuelve la ruta de la imagen correspondiente
            if (alert === "Precaución por lluvias") {
                return thunderIcon; // Usa la imagen importada
            }
            return ''; // En caso de que no haya imagen, devuelve un string vacío o una imagen por defecto
        },
        async cargarDatos() {
            let response = await axios.get(
                this.route("resultados.index", { idAmbiente: this.idAmbiente })
            );
            this.datosCalculos = response.data;
            this.setDatos();
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
                this.resultados.push({
                    hour: resultado.hora,
                    cm: resultado.apertura,
                    expanded: false,
                    date: date,
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

table {
    width: 700px;
}
</style>
