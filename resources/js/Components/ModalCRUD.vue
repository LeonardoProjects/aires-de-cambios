<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed, ref, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';
import { Tooltip } from 'bootstrap';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

const emit = defineEmits(['updateAmbientes']); // Definir el evento que vas a emitir

const formAdd = useForm({
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
        const response = await axios.post(route("ambiente.store"), formAdd);
        // Si la solicitud es exitosa (status 200)
        if (response.status === 200) {
            closeModal(); // Cerrar el modal
            emitirAmbiente(response.data.data); // Emitir los ambientes actualizados
        }
    } catch (error) {
        // Si hay un error de validación o cualquier otro error
        if (error.response && error.response.status === 422) {
            // Los errores de validación están en error.response.data.errors
            formAdd.errors = error.response.data.errors; // Asignar los errores al objeto 'formAdd'
        } else {
            console.error("Error inesperado: ", error);
        }
    }
}

onMounted(() => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));
});

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
            formAdd.latitud = e.geocode.center.lat;
            formAdd.longitud = e.geocode.center.lng;
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
        marcador.value = L.marker([lat, lng]).addTo(map).bindPopup('Ubicación seleccionada').openPopup();

        formAdd.latitud = lat;
        formAdd.longitud = lng;
    });
    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 1000);
}


function emitirAmbiente($ambiente) {
    emit('updateAmbientes', $ambiente);
}

function clearInputs() {
    // Limpiar marcadores y volver a la posicion original del mapa
    if (marcador.value) {
        map.removeLayer(marcador.value);
        marcador.value = null;
    }

    if (map) {
        map.remove();
    }

    formAdd.reset();
}

function closeModal() {
    document.querySelector('#closeModalButton').click();
}
</script>

<template>
    <button type="button" @click="crearMapa" class="btn btn-outline-primary mx-1 rounded-5 p-0 px-2"
        data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-plus-circle"
            viewBox="0 0 16 16">
            <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
            <path
                d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4" />
        </svg>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        <span>Crear ambiente</span>
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
                                    v-model="formAdd.nombreAmbiente" />
                                <div v-if="formAdd.errors.nombreAmbiente" class="error">{{
                                    formAdd.errors.nombreAmbiente[0]
                                    }}
                                </div>
                            </div>
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-25 text-center">
                                    <label for="anchoAmbiente" class="form-label">Ancho (m)</label>
                                    <input id="anchoAmbiente" type="number" min="0.1" max="20" step="0.1"
                                        class="form-control" v-model="formAdd.anchoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="largoAmbiente" class="form-label">Largo (m)</label>
                                    <input id="largoAmbiente" type="number" min="0.1" max="20" step="0.1" value="0"
                                        class="form-control w-0" v-model="formAdd.largoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="altoAmbiente" class="form-label">Alto (m)</label>
                                    <input id="altoAmbiente" type="number" min="0.1" max="20" step="0.1" value="0"
                                        class="form-control w-0" v-model="formAdd.altoAmbiente" />
                                </div>
                            </div>
                            <div class="mx-5 mt-4 w-75 text-center">
                                <label for="tipoHabitacion" class="form-label">Tipo de habitación</label>
                                <select name="tipoHabitacionSelect" id="tipoHabitacion" class="form-select"
                                    v-model="formAdd.tipoHabitacion" @change="formAdd.clearErrors('tipoHabitacion')">
                                    <option value="Dormitorio">
                                        Dormitorio
                                    </option>
                                    <option value="Estar o comedor">
                                        Estar o comedor
                                    </option>
                                </select>
                                <div v-if="formAdd.errors.tipoHabitacion" class="error">{{
                                    formAdd.errors.tipoHabitacion[0]
                                    }}
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
                                    <button type="button" class="btn btnTooltip" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="La altura sobre el suelo afecta la velocidad del viento.">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                            </svg>
                                    </button>
                                    <select name="alturaSelect" id="altura" v-model="formAdd.alturaSelect"
                                        class="form-select" @change="formAdd.clearErrors('alturaSelect')">
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
                                    <div v-if="formAdd.errors.alturaSelect" class="error">{{
                                        formAdd.errors.alturaSelect[0]
                                        }}
                                    </div>
                                </div>

                                <div class="ms-3 w-50 text-center">
                                    <label for="densidadSelect" class="form-label">
                                        Vivo en</label>
                                    <button type="button" class="btn btnTooltip" data-bs-toggle="tooltip"
                                        data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                        data-bs-title="Las características del terreno y lugar afectan la velocidad del viento por la rugosidad.">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                            </svg>
                                    </button>
                                    <select name="densidadSelect" id="densidad" class="form-select"
                                        v-model="formAdd.densidadSelect"
                                        @change="formAdd.clearErrors('densidadSelect')">
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
                                    <div v-if="formAdd.errors.densidadSelect" class="error">{{
                                        formAdd.errors.densidadSelect[0] }}
                                    </div>
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
                                    <input id="largoVentana" type="number" min="0.1" max="20" step="0.1"
                                        class="form-control" v-model="formAdd.largoVentana" />
                                </div>
                                <div class="mx-4 w-50 text-center">
                                    <label for="altoVentana" class="form-label">
                                        Alto (m)</label>
                                    <input id="altoVentana" type="number" min="0.1" max="20" step="0.1"
                                        class="form-control" v-model="formAdd.altoVentana" />
                                </div>
                            </div>
                            <div class="d-flex mt-4 flex-row">
                                <div class="mx-3 w-50 text-center">
                                    <label for="tipoVentana" class="form-label">
                                        Tipo de ventana</label>
                                    <select name="tipoVentanaSelect" id="tipoVentana" class="form-select"
                                        v-model="formAdd.tipoVentana" @change="formAdd.clearErrors('tipoVentana')">
                                        <option value="Corrediza">
                                            Corrediza
                                        </option>
                                    </select>
                                    <div v-if="formAdd.errors.tipoVentana" class="error">{{
                                        formAdd.errors.tipoVentana[0] }}
                                    </div>
                                </div>
                                <div class="mx-3 w-50 text-center">
                                    <div class="d-flex align-items-baseline">
                                        <label for="calidadVentana" class="form-label">
                                            Calidad de ventana</label>
                                        <button type="button" class="btn btnTooltip" data-bs-toggle="tooltip"
                                            data-bs-placement="top" data-bs-custom-class="custom-tooltip"
                                            data-bs-title="La calidad de las aberturas afecta en las infiltraciones de aire.">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                fill="currentColor" class="bi bi-info-circle" viewBox="0 0 16 16">
                                                <path
                                                    d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16" />
                                                <path
                                                    d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0" />
                                            </svg>
                                        </button>
                                    </div>
                                    <select name="calidadSelect" id="calidadVentana" class="form-select"
                                        v-model="formAdd.calidadVentana"
                                        @change="formAdd.clearErrors('calidadVentana')">
                                        <option value="Normal">Normal</option>
                                        <option value="Mejorada">
                                            Mejorada
                                        </option>
                                        <option value="Reforzada">
                                            Reforzada
                                        </option>
                                    </select>
                                    <div v-if="formAdd.errors.calidadVentana" class="error">{{
                                        formAdd.errors.calidadVentana[0] }}
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
                    <button type="submit" class="btn btn-primary" form="crudFORM" :disabled="formAdd.processing">
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
    font-size: max(0.5vw, 0.9rem) !important;
}

.btnTooltip {
    background: transparent;
    border: none;
    border-radius: 100px;
    padding: 0;
    margin-left: 3px;
}

.btnTooltip svg {
    vertical-align: middle; /* Alinea el ícono svg dentro del botón */
}


/*Resolución para tablets (pantallas entre 768px y 1024px)*/
@media (min-width: 768px) and (min-width: 1024px) {
    #map {
        width: 90%;
        height: 350px;
    }
}

svg {
    vertical-align: text-bottom;
}
</style>
