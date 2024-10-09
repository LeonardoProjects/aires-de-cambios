<template>
    <div>
        <div id="map2" style="height: 500px; width: 1000px;"></div>
        <button @click="crearMapa">Crear Mapa</button>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    name: "EnvironmentsPerCountryChart",
    setup() {
        const map2 = ref(null);
        const maxAmbientes = ref(10);

        const crearMapa = () => {
            if (map2.value) {
                return; 
            }
            map2.value = L.map("map2", { preferCanvas: true }).setView([20, 0], 2);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 18,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map2.value);

            axios.get('/countries-data')
                .then(response => {
                    const ambientesPorPais = response.data;

                    axios.get('/country-data/ne_10m_admin_0_countries_arg.json')
                        .then(geoJsonResponse => {
                            L.geoJSON(geoJsonResponse.data, {
                                style: feature => {
                                    const pais = feature.properties.ADMIN;
                                    const ambiente = ambientesPorPais.find(a => a.pais === pais);

                                    let fillColor = 'lightgray';
                                    if (ambiente) {
                                        const total = ambiente.total;
                                        const intensity = Math.min(total / maxAmbientes.value, 1);
                                        fillColor = `rgba(0, 0, 255, ${intensity})`; 
                                    }

                                    return {
                                        color: 'black',
                                        fillColor: fillColor,
                                        fillOpacity: 0.7,
                                        weight: 1,
                                    };
                                }
                            }).addTo(map2.value);
                        })
                        .catch(error => console.error('Error al cargar el GeoJSON:', error));
                })
                .catch(error => console.error('Error al obtener datos de países:', error));
        };

        return {
            crearMapa,
        };
    },
};
</script>

<style scoped>
#map2 {
    height: 500px;
    width: 1000px;
}
</style>
