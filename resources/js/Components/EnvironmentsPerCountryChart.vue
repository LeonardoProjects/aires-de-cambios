<template>
    <div>
        <div id="map2" style="height: 500px"></div>
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

        const crearMapa = () => {
            if (map2.value) {
                return;
            }

            map2.value = L.map("map2", { preferCanvas: true }).setView([20, 0], 2); // Coordenadas centradas en el mundo

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                maxZoom: 18,
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map2.value);

            axios.get("/country-data/ne_10m_admin_0_countries_arg.json") // Cambia esta ruta a la ruta donde guardaste tu GeoJSON
                .then((response) => {
                    L.geoJSON(response.data).addTo(map2.value);
                })
                .catch((error) => {
                    console.error("Error al cargar el archivo GeoJSON:", error);
                });
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
