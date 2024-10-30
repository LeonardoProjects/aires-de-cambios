<template>
    <div>
        <div id="map2"></div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";
import countries from "i18n-iso-countries";
import countriesESP from "i18n-iso-countries";
import es from "i18n-iso-countries/langs/es.json";
import en from "i18n-iso-countries/langs/en.json";

export default {
    name: "EnvironmentsPerCountryChart",
    setup() {
        const map2 = ref(null);
        const maxAmbientes = ref(0);

        countries.registerLocale(en);
        countries.registerLocale(es);

        const translateCountryName = (countryName) => {
            const countryISO = countries.getAlpha2Code(countryName, "en");
            console.log(`ISO for ${countryName}: ${countryISO}`);
            if (countryISO) {
                return countriesESP.getName(countryISO, "es");
            }
            return countryName;
        };

        const roundToNearestPowerOfTen = (num) => {
            if (num < 10) return 10;
            return Math.pow(10, Math.floor(Math.log10(num)));
        };

        const getColor = (total, max) => {
            if (max === 0) return "lightgray";
            const intensity = total / max;

            const startColor = [255, 255, 178];
            const endColor = [255, 69, 0];

            const r = Math.floor(
                startColor[0] + intensity * (endColor[0] - startColor[0])
            );
            const g = Math.floor(
                startColor[1] + intensity * (endColor[1] - startColor[1])
            );
            const b = Math.floor(
                startColor[2] + intensity * (endColor[2] - startColor[2])
            );

            return `rgba(${r}, ${g}, ${b}, 0.7)`;
        };

        const crearMapa = () => {
            if (map2.value) {
                return;
            }

            map2.value = L.map("map2", {
                minZoom: 2,
                maxZoom: 4,
                worldCopyJump: false,
                maxBoundsViscosity: 1,
                scrollWheelZoom: false,
            }).setView([20, 0], 2);

            const southWest = L.latLng(-85, -180);
            const northEast = L.latLng(85, 180);
            const bounds = L.latLngBounds(southWest, northEast);
            map2.value.setMaxBounds(bounds);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map2.value);

            axios.get("/countries-data").then((response) => {
                const ambientesPorPais = response.data;
                const maxAmbientesCalculado = Math.max(
                    ...ambientesPorPais.map((a) => a.total)
                );
                maxAmbientes.value = roundToNearestPowerOfTen(
                    maxAmbientesCalculado
                );
                var geojson;
                var info = L.control();

                axios
                    .get("/country-data/ne_10m_admin_0_countries_arg.json")
                    .then((geoJsonResponse) => {
                        info.onAdd = function () {
                            this._div = L.DomUtil.create("div");
                            this.update();
                            this._div.style.background =
                                "rgba(255, 255, 255, 0.6)";
                            this._div.style.padding = "10px";
                            this._div.style.borderRadius = "7px";
                            return this._div;
                        };

                        info.update = function (props) {
                            const nombreEnEspanol = props
                                ? translateCountryName(props.ADMIN)
                                : null;
                            this._div.innerHTML =
                                "<h4>Información del país</h4>" +
                                (props
                                    ? '<p class="m-0 fs-6 fw-bold">' +
                                      nombreEnEspanol +
                                      "</p><p class='m-0 fs-6'>Ambientes: " +
                                      (ambientesPorPais.find(
                                          (a) => a.pais === props.ADMIN
                                      )?.total || 0) +
                                      "</p>"
                                    : "Posicione el mouse sobre un país ");
                        };

                        info.addTo(map2.value);

                        let selectedLayer = null;
                        geojson = L.geoJSON(geoJsonResponse.data, {
                            style: (feature) => {
                                const pais = feature.properties.ADMIN;
                                const ambiente = ambientesPorPais.find(
                                    (a) => a.pais === pais
                                );

                                let fillColor = "lightgray";
                                if (ambiente) {
                                    const total = ambiente.total;
                                    fillColor = getColor(
                                        total,
                                        maxAmbientes.value
                                    );
                                }

                                return {
                                    color: "black",
                                    fillColor: fillColor,
                                    fillOpacity: 0.7,
                                    weight: 1,
                                };
                            },
                            onEachFeature: (feature, layer) => {
                                const isMobile =
                                    "ontouchstart" in window ||
                                    navigator.maxTouchPoints > 0;
                                const eventType = isMobile
                                    ? "click"
                                    : "mouseover";

                                layer.on({
                                    [eventType]: (e) => {
                                        highlightFeature(e);
                                        info.update(
                                            e.target.feature.properties
                                        );
                                    },
                                    mouseout: (e) => {
                                        resetHighlight(e);
                                        info.update();
                                    },
                                    click: (e) => {
                                        if (isMobile) {
                                            if (selectedLayer) {
                                                geojson.resetStyle(
                                                    selectedLayer
                                                );
                                            }
                                            selectedLayer = e.target;
                                            highlightFeature(e);
                                            info.update(
                                                e.target.feature.properties
                                            );
                                        }
                                    },
                                });
                            },
                        }).addTo(map2.value);

                        function highlightFeature(e) {
                            var layer = e.target;

                            layer.setStyle({
                                weight: 2,
                                color: "#fff",
                                dashArray: "",
                                fillOpacity: 0.8,
                            });

                            layer.bringToFront();
                        }

                        function resetHighlight(e) {
                            geojson.resetStyle(e.target);
                        }

                        map2.value.on("click", function () {
                            map2.value.scrollWheelZoom.enable();
                        });

                        map2.value.on("blur", function () {
                            map2.value.scrollWheelZoom.disable();
                        });

                        var legend = L.control({ position: "bottomright" });

                        legend.onAdd = function () {
                            const div = L.DomUtil.create("div", "info legend");
                            const gradient =
                                "linear-gradient(to right, rgba(255, 237, 160, 0.7), rgba(255, 69, 0, 0.7))";
                            div.innerHTML += `<div style="width: 200px; height: 20px; background: ${gradient}; border: 1px solid;"></div>`;
                            div.innerHTML += `<div style="display: flex; justify-content: space-between;">
                        <span class="fw-bold">0</span><span class="fw-bold">${maxAmbientes.value}</span>
                      </div>`;

                            return div;
                        };

                        legend.addTo(map2.value);
                    })
                    .catch((error) =>
                        console.error("Error al cargar el GeoJSON:", error)
                    );
            });
        };

        return {
            crearMapa,
        };
    },
    mounted() {
        this.crearMapa();
    },
};
</script>

<style scoped>
#map2 {
    height: 500px;
    width: 1000px;
    z-index: 1;
}

.info.legend {
    background-color: white;
    padding: 6px;
    font-size: 12px;
    line-height: 18px;
    color: #333;
    text-align: center;
}

.info.legend div {
    margin: 4px 0;
}
</style>
