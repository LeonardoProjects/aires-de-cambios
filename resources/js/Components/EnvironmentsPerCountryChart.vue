<template>
    <div>
        <div id="map2" style="height: 500px; width: 1000px"></div>
    </div>
</template>

<script>
import { ref } from "vue";
import axios from "axios";

export default {
    name: "EnvironmentsPerCountryChart",
    setup() {
        const map2 = ref(null);
        const maxAmbientes = ref(0);
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
                scrollWheelZoom: false
            }).setView([20, 0], 2);

            const southWest = L.latLng(-85, -180);
            const northEast = L.latLng(85, 180);
            const bounds = L.latLngBounds(southWest, northEast);
            map2.value.setMaxBounds(bounds);

            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution:
                    '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }).addTo(map2.value);
            axios
                .get("/countries-data")
                .then((response) => {
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
                                this._div = L.DomUtil.create('div'); // create a div with a class "info"
                                this.update();
                                this._div.style.background = 'rgba(255, 255, 255, 0.6)';
                                this._div.style.padding = '10px';
                                this._div.style.borderRadius = '7px';
                                return this._div;
                            };

                            // method that we will use to update the control based on feature properties passed
                            info.update = function (props) {
                                this._div.innerHTML = '<h4>Información del país</h4>' +
                                    (props ?
                                        '<p class="m-0 fs-6 fw-bold">' + props.ADMIN + '</p><p class="m-0 fs-6">Ambientes: ' +
                                        (ambientesPorPais.find(a => a.pais === props.ADMIN)?.total || 0) + '</p>'
                                        : 'Posicione el mouse sobre un país </p>');
                            };

                            info.addTo(map2.value);

                            geojson = L.geoJSON(geoJsonResponse.data, {
                                style: (feature) => {
                                    const pais = feature.properties.ADMIN;
                                    const ambiente = ambientesPorPais.find((a) => a.pais === pais);

                                    let fillColor = "lightgray";
                                    if (ambiente) {
                                        const total = ambiente.total;
                                        fillColor = getColor(total, maxAmbientes.value);
                                    }

                                    return {
                                        color: "black",
                                        fillColor: fillColor,
                                        fillOpacity: 0.7,
                                        weight: 1,
                                    };
                                },
                                onEachFeature: (feature, layer) => {
                                    // Evento 'mouseover' para resaltar la capa y actualizar el control info
                                    layer.on({
                                        mouseover: (e) => {
                                            highlightFeature(e);
                                            info.update(e.target.feature.properties);
                                        },
                                        mouseout: (e) => {
                                            resetHighlight(e);
                                            info.update();
                                        }
                                    });
                                }
                            }).addTo(map2.value);

                            // Función para resaltar el país al hacer hover
                            function highlightFeature(e) {
                                var layer = e.target;

                                layer.setStyle({
                                    weight: 2,
                                    color: '#fff',
                                    dashArray: '',
                                    fillOpacity: 0.8
                                });

                                layer.bringToFront();
                            }

                            // Función para quitar el resaltado al salir del hover
                            function resetHighlight(e) {
                                geojson.resetStyle(e.target); // Asegúrate de que `geojson` esté definido correctamente
                            }

                            map2.value.on('click', function () {
                                map2.value.scrollWheelZoom.enable();
                            });


                            map2.value.on('blur', function () {
                                map2.value.scrollWheelZoom.disable();
                            });
                            // Añadir leyenda
                            var legend = L.control({ position: "bottomright" });

                            legend.onAdd = function () {
                                const div = L.DomUtil.create(
                                    "div",
                                    "info legend"
                                );
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
                })
                .catch((error) =>
                    console.error("Error al obtener datos de países:", error)
                );
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
