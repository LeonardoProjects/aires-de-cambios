<template>
    <div
        class="d-flex justify-content-center align-items-center flex-column min-vh-100"
    >
        <table border="1" align="center" cellpadding="10">
            <tr
                v-for="(resultado, index) in resultados"
                :key="index"
                @click="toggleRow(index)"
            >
                <template v-if="!resultado.expanded">
                    <td>{{ resultado.hour }}</td>
                    <td>{{ resultado.cm }} cm</td>
                    <td>{{ resultado.expanded ? "▲" : "▼" }}</td>
                </template>
                <td colspan="3" v-else>
                    <strong>{{ resultado.date }}</strong> <br />
                    <img
                        :src="resultado.icon"
                        alt="Alert Icon"
                        style="height: 20px; width: 20px"
                    />
                    {{ resultado.details }}, {{ resultado.alert }} <br />
                    Apertura: {{ resultado.cm }}
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
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


</style>
