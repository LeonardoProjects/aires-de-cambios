<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

const props = defineProps({
    editFunction: {
        Boolean,
        default: false
    }
});

const emit = defineEmits(['updateAmbientes']); // Definir el evento que vas a emitir

const form = useForm({
    anchoAmbiente: 0,
    nombreAmbiente: "",
    largoAmbiente: 0,
    altoAmbiente: 0,
    tipoHabitacion: "",
    alturaSelect: "",
    longitud: "",
    latitud: "",
    densidadSelect: "",
    largoVentana: 0,
    altoVentana: 0,
    tipoVentana: "Corrediza",
    calidadVentana: "",
    idUser: userId.value,
    errors: {}
});

async function submit() {
    try {
        const response = await axios.post(route("ambiente.store"), form);
        // Si la solicitud es exitosa (status 200)
        if (response.status === 200) {
            closeModal(); // Cerrar el modal
            emitirAmbientes(response.data.data); // Emitir los ambientes actualizados
        }
    } catch (error) {
        // Si hay un error de validación o cualquier otro error
        if (error.response && error.response.status === 422) {
            // Los errores de validación están en error.response.data.errors
            form.errors = error.response.data.errors; // Asignar los errores al objeto 'form'
        } else {
            console.error("Error inesperado: ", error);
        }
    }
}

// Funcionalidad para el mapa Leaflet
const marcador = ref(null);
let map;

function crearMapa() {
    map = L.map('map').setView([-32.98369774322006, -55.93512229688155], 6);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        fullscreenControl: true,
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);


    let pantallaCompleta = new L.Control.Fullscreen({
        title: {
            'false': 'View Fullscreen',
            'true': 'Exit Fullscreen'
        }
    });
    map.addControl(pantallaCompleta);

    // Añadir el Geocoder al mapa
    L.Control.geocoder({
        defaultMarkGeocode: false
    })
        .on('markgeocode', function (e) {
            let bbox = e.geocode.bbox;
            let poly = L.polygon([
                [bbox.getSouthEast().lat, bbox.getSouthEast().lng],
                [bbox.getNorthEast().lat, bbox.getNorthEast().lng],
                [bbox.getNorthWest().lat, bbox.getNorthWest().lng],
                [bbox.getSouthWest().lat, bbox.getSouthWest().lng]
            ]);
            map.fitBounds(poly.getBounds());

            if (marcador.value) {
                map.removeLayer(marcador.value);
            }

            // Añadir nuevo marcador en la ubicación ingresada por el geocoder
            marcador.value = L.marker(e.geocode.center).addTo(map).bindPopup(e.geocode.name).openPopup();

            // Actualizar latitud y longitud en el formulario
            form.latitud = e.geocode.center.lat;
            form.longitud = e.geocode.center.lng;
        }).addTo(map);
    map.invalidateSize();

    map.on('enterFullscreen', () => {
        map.invalidateSize(); // Ajustar el tamaño del mapa cuando entra en fullscreen
    });

    map.on('exitFullscreen', () => {
        map.invalidateSize(); // Ajustar el tamaño del mapa cuando sale de fullscreen
    });

    // Evento de clic en el mapa para que el usuario pueda seleccionar una ubicación
    map.on('click', function (e) {
        const { lat, lng } = e.latlng;

        // Borrar el marcador anterior si existe
        if (marcador.value) {
            map.removeLayer(marcador.value);
        }

        // Añadir nuevo marcador en la ubicación seleccionada por el usuario
        marcador.value = L.marker([lat, lng]).addTo(map).bindPopup(`Coordenadas: ${lat}, ${lng}`).openPopup();

        form.latitud = lat;
        form.longitud = lng;
    });
    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 1000);
}

function emitirAmbientes($ambientes) {
    emit('updateAmbientes', $ambientes);
}

function clearInputs() {
    // Limpiar marcadores y volver a la posicion original del mapa
    if (marcador.value) {
        console.log(marcador.value);
        map.removeLayer(marcador.value);
        marcador.value = null;
    }

    if (map) {
        map.remove();
    }

    form.reset();
}

function closeModal() {
    document.querySelector('#closeModalButton').click();
}
</script>

<template>
    <button type="button" @click="crearMapa" class="btn btn-outline-primary mx-1 rounded-5 p-0 px-2"
        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <svg v-if="!editFunction" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-plus-circle" viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor"
            class="bi bi-pencil-square" viewBox="0 0 16 16">
            <path
                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
            <path fill-rule="evenodd"
                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
        </svg>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <span v-if="editFunction">Editar ambiente</span>
                        <span v-else>Crear ambiente</span>
                    </h1>
                    <button type="button" class="btn-close" @click="clearInputs" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <form @submit.prevent="submit" id="crudFORM">
                        <div class="mb-5">
                            <h3>Detalles del ambiente</h3>
                            <hr />
                            <div class="mt-1 mb-4 mx-4 text-center">
                                <label for="nombreAmbiente" class="form-label">Nombre de ambiente</label>
                                <input id="nombreAmbiente" type="text" class="form-control w-0"
                                    v-model="form.nombreAmbiente" @input="form.clearErrors('nombreAmbiente')" />
                                <div v-if="form.errors.nombreAmbiente" class="error">{{ form.errors.nombreAmbiente[0] }}
                                </div>
                            </div>
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-25 text-center">
                                    <label for="anchoAmbiente" class="form-label">Ancho (m)</label>
                                    <input id="anchoAmbiente" type="number" min="0" max="20" step="0,1"
                                        class="form-control" v-model="form.anchoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="largoAmbiente" class="form-label">Largo (m)</label>
                                    <input id="largoAmbiente" type="number" min="0" max="20" step="0,1" value="0"
                                        class="form-control w-0" v-model="form.largoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="altoAmbiente" class="form-label">Alto (m)</label>
                                    <input id="altoAmbiente" type="number" min="0" max="20" step="0,1" value="0"
                                        class="form-control w-0" v-model="form.altoAmbiente" />
                                </div>
                            </div>
                            <div class="mx-5 mt-4 w-75 text-center">
                                <label for="tipoHabitacion" class="form-label">Tipo de habitación</label>
                                <select name="tipoHabitacionSelect" id="tipoHabitacion" class="form-select"
                                    v-model="form.tipoHabitacion" @change="form.clearErrors('tipoHabitacion')">
                                    <option value="Dormitorio">
                                        Dormitorio
                                    </option>
                                    <option value="Estar o comedor">
                                        Estar o comedor
                                    </option>
                                </select>
                                <div v-if="form.errors.tipoHabitacion" class="error">{{ form.errors.tipoHabitacion[0] }}
                                </div>
                            </div>
                        </div>

                        <div class="mb-5 padreMapa">
                            <h3>Ubicación</h3>
                            <hr />
                            <div class="d-flex h-50">
                                <div class="me-3 w-50 text-center">
                                    <label for="alturaSelect" class="form-label">
                                        Altura</label>
                                    <select name="alturaSelect" id="altura" v-model="form.alturaSelect"
                                        class="form-select" @change="form.clearErrors('alturaSelect')">
                                        <option value="Planta baja">
                                            Planta baja
                                        </option>
                                        <option value="1° o 2° piso">
                                            1° o 2° piso
                                        </option>
                                        <option value="3° a 5° piso">
                                            3° a 5° piso
                                        </option>
                                        <option value="6° piso o más">
                                            6° piso o más
                                        </option>
                                    </select>
                                    <div v-if="form.errors.alturaSelect" class="error">{{ form.errors.alturaSelect[0] }}
                                    </div>
                                </div>

                                <div class="ms-3 w-50 text-center">
                                    <label for="densidadSelect" class="form-label">
                                        Vivo en</label>
                                    <select name="densidadSelect" id="densidad" class="form-select"
                                        v-model="form.densidadSelect" @change="form.clearErrors('densidadSelect')">
                                        <option value="Frente al mar">
                                            Frente al mar
                                        </option>
                                        <option value="El campo">
                                            El campo
                                        </option>
                                        <option value="Un barrio poco denso">
                                            Un barrio poco denso
                                        </option>
                                        <option value="Un barrio muy denso">
                                            Un barrio muy denso
                                        </option>
                                        <option value="El centro con edificios altos">
                                            El centro con edificios altos
                                        </option>
                                    </select>
                                    <div v-if="form.errors.densidadSelect" class="error">{{
                                        form.errors.densidadSelect[0] }}</div>
                                </div>
                            </div>
                            <div id="map" class="mt-4"></div>
                        </div>

                        <div class="mb-0">
                            <h3>Detalles de ventana</h3>
                            <hr />
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-50 text-center">
                                    <label for="largoVentana" class="form-label">
                                        Largo (m)</label>
                                    <input id="largoVentana" type="number" min="0" max="20" step="0.1"
                                        class="form-control" v-model="form.largoVentana" />
                                </div>
                                <div class="mx-4 w-50 text-center">
                                    <label for="altoVentana" class="form-label">
                                        Alto (m)</label>
                                    <input id="altoVentana" type="number" min="0" max="20" step="0.1"
                                        class="form-control" v-model="form.altoVentana" />
                                </div>
                            </div>
                            <div class="d-flex mt-4 flex-row">
                                <div class="mx-3 w-50 text-center">
                                    <label for="tipoVentana" class="form-label">
                                        Tipo de ventana</label>
                                    <select name="tipoVentanaSelect" id="tipoVentana" class="form-select"
                                        v-model="form.tipoVentana" @change="form.clearErrors('tipoVentana')">
                                        <option value="Corrediza">
                                            Corrediza
                                        </option>
                                    </select>
                                    <div v-if="form.errors.tipoVentana" class="error">{{ form.errors.tipoVentana[0] }}
                                    </div>
                                </div>
                                <div class="mx-3 w-50 text-center">
                                    <label for="calidadVentana" class="form-label">
                                        Calidad de ventana</label>
                                    <select name="calidadSelect" id="calidadVentana" class="form-select"
                                        v-model="form.calidadVentana" @change="form.clearErrors('calidadVentana')">
                                        <option value="Normal">Normal</option>
                                        <option value="Mejorada">
                                            Mejorada
                                        </option>
                                        <option value="Reforzada">
                                            Reforzada
                                        </option>
                                    </select>
                                    <div v-if="form.errors.calidadVentana" class="error">{{
                                        form.errors.calidadVentana[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalButton" class="btn btn-secondary" @click="clearInputs"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" form="crudFORM" :disabled="form.processing">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#map {
    width: 100%;
    height: 400px;
    margin: 0 auto;
}

.error {
    color: red;
    font-size: 0.9em;
}

label,
input,
option,
select {
    font-size: max(0.8vw, 0.9rem) !important;
}


/*Resolución para tablets (pantallas entre 768px y 1024px)*/
@media (min-width: 768px) and (min-width: 1024px) {
    #map {
        width: 90%;
        height: 350px;
    }
}
</style>
