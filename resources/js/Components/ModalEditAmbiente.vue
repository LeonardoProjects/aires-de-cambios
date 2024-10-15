<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, ref, onMounted } from 'vue';
import { Tooltip } from 'bootstrap';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

const props = defineProps({
    ambiente: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['updateAmbientesEdit']); // Definir el evento que vas a emitir

async function submitEdit() {
    try {
        const response = await axios.post(route("ambiente.update"), form);
        // Si la solicitud es exitosa (status 200)
        if (response.status === 200) {
            closeModal(); // Cerrar el modal
            emitirAmbiente(response.data.data); // Emitir los ambientes actualizados
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

onMounted(() => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));
});

const form = useForm({
    idAmbiente: 0,
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
    idUser: page.props.auth.user ? userId.value : "",
    errors: {}
});

// Función para cargar los datos del ambiente en el formulario
function cargarAmbiente(ambiente) {
    crearMapaEdit(ambiente.ubicacion.latitud, ambiente.ubicacion.longitud);
    form.reset();
    form.idAmbiente = ambiente.id || 0;
    form.nombreAmbiente = ambiente.nombre || "";
    form.anchoAmbiente = Number(ambiente.local.ancho) || 0;
    form.largoAmbiente = Number(ambiente.local.largo) || 0;
    form.altoAmbiente = Number(ambiente.local.alto) || 0;
    form.tipoHabitacion = ambiente.local.tipoHabitacion || "";
    form.latitud = ambiente.ubicacion.latitud || "";
    form.longitud = ambiente.ubicacion.longitud || "";
    form.alturaSelect = ambiente.ubicacion.altura || "";
    form.densidadSelect = ambiente.ubicacion.densidad || "";
    form.largoVentana = Number(ambiente.ventana.largo) || 0;
    form.altoVentana = Number(ambiente.ventana.alto) || 0;
    form.calidadVentana = ambiente.ventana.calidad || "";
}

function emitirAmbiente($ambiente) {
    emit('updateAmbientesEdit', $ambiente);

}

function clearInputs() {
    // Limpiar marcadores y volver a la posicion original del mapa
    if (marcadorEdit.value) {
        mapEdit.removeLayer(marcadorEdit.value);
        marcadorEdit.value = null;
    }

    if (mapEdit) {
        mapEdit.remove();
    }

    form.reset();
}

function closeModal() {
    document.querySelector('#closeModalEditButton').click();
}

// Funcionalidad para el mapa Leaflet
const marcadorEdit = ref(null);
let mapEdit;

function crearMapaEdit($latitud, $longitud) {
    mapEdit = L.map('mapEdit').setView([Number($latitud), Number($longitud)], 18);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        fullscreenControl: true,
        maxZoom: 19,
        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(mapEdit);


    let pantallaCompleta = new L.Control.Fullscreen({
        title: {
            'false': 'View Fullscreen',
            'true': 'Exit Fullscreen'
        }
    });
    mapEdit.addControl(pantallaCompleta);

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
            mapEdit.fitBounds(poly.getBounds());

            if (marcadorEdit.value) {
                mapEdit.removeLayer(marcadorEdit.value);
            }

            // Añadir nuevo marcador en la ubicación ingresada por el geocoder
            marcadorEdit.value = L.marker(e.geocode.center).addTo(mapEdit).bindPopup(e.geocode.name).openPopup();

            // Actualizar latitud y longitud en el formulario
            form.latitud = e.geocode.center.lat;
            form.longitud = e.geocode.center.lng;
        }).addTo(mapEdit);
    mapEdit.invalidateSize();

    mapEdit.on('enterFullscreen', () => {
        mapEdit.invalidateSize(); // Ajustar el tamaño del mapa cuando entra en fullscreen
    });

    mapEdit.on('exitFullscreen', () => {
        mapEdit.invalidateSize(); // Ajustar el tamaño del mapa cuando sale de fullscreen
    });

    // Evento de clic en el mapa para que el usuario pueda seleccionar una ubicación
    mapEdit.on('click', function (e) {
        const { lat, lng } = e.latlng;

        // Borrar el marcador anterior si existe
        if (marcadorEdit.value) {
            mapEdit.removeLayer(marcadorEdit.value);
        }

        // Añadir nuevo marcador en la ubicación seleccionada por el usuario
        marcadorEdit.value = L.marker([lat, lng]).addTo(mapEdit).bindPopup('Ubicación modificada').openPopup();

        form.latitud = lat;
        form.longitud = lng;
    });
    setTimeout(function () {
        window.dispatchEvent(new Event('resize'));
    }, 1000);

    marcadorEdit.value = L.marker([$latitud, $longitud]).addTo(mapEdit);
}
</script>

<template>
    <button type="button" class="btn btn-outline-primary mx-1 rounded-5 px-2" data-bs-toggle="modal"
        data-bs-target="#modalEdit" @click="cargarAmbiente(ambiente)">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-square"
            viewBox="0 0 16 16">
            <path
                d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
            <path fill-rule="evenodd"
                d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
        </svg>
    </button>

    <!-- Modal -->
    <div class="modal fade" id="modalEdit" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="modalEditLabel">
                        Editar local
                    </h1>
                    <button type="button" class="btn-close" @click="clearInputs" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <form @submit.prevent="submitEdit" id="editFORM">
                        <div class="mb-5">
                            <h3>Detalles del local</h3>
                            <hr />
                            <div class="mt-1 mb-4 mx-4 text-center">
                                <label for="nombreAmbiente" class="form-label">Nombre de local</label>
                                <input id="nombreAmbiente" type="text" class="form-control w-0"
                                    v-model="form.nombreAmbiente" />
                                <div v-if="form.errors.nombreAmbiente" class="error">{{ form.errors.nombreAmbiente[0] }}
                                </div>
                            </div>
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-25 text-center">
                                    <label for="anchoAmbiente" class="form-label">Ancho (m)</label>
                                    <input id="anchoAmbiente" type="number" min="0.1" max="20" step="0.1"
                                        class="form-control" v-model="form.anchoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="largoAmbiente" class="form-label">Largo (m)</label>
                                    <input id="largoAmbiente" type="number" min="0.1" max="20" step="0.1" value="0"
                                        class="form-control w-0" v-model="form.largoAmbiente" />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="altoAmbiente" class="form-label">Alto (m)</label>
                                    <input id="altoAmbiente" type="number" min="0.1" max="20" step="0.1" value="0"
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

                        <div class="mb-5">
                            <h3>Ubicación</h3>
                            <hr />
                            <div class="d-flex h-50">
                                <div class="me-3 w-50 text-center">
                                    <label for="alturaSelect" class="form-label">
                                        Altura</label>
                                    <div class="d-flex">
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
                                    </div>
                                    <div v-if="form.errors.alturaSelect" class="error">{{ form.errors.alturaSelect[0] }}
                                    </div>
                                </div>

                                <div class="ms-3 w-50 text-center">
                                    <label for="densidadSelect" class="form-label">
                                        Vivo en</label>
                                    <div class="d-flex">
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
                                    </div>
                                    <div v-if="form.errors.densidadSelect" class="error">{{
                                        form.errors.densidadSelect[0] }}
                                    </div>
                                </div>
                            </div>
                            <div id="mapEdit" class="mt-4"></div>
                        </div>

                        <div class="mb-0">
                            <h3>Detalles de ventana</h3>
                            <hr />
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-50 text-center">
                                    <label for="largoVentana" class="form-label">
                                        Largo (m)</label>
                                    <input id="largoVentana" type="number" min="0.1" max="20" step="0.1"
                                        class="form-control" v-model="form.largoVentana" />
                                </div>
                                <div class="mx-4 w-50 text-center">
                                    <label for="altoVentana" class="form-label">
                                        Alto (m)</label>
                                    <input id="altoVentana" type="number" min="0.1" max="20" step="0.1"
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
                                    <div class="d-flex">
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
                                    <div v-if="form.errors.calidadVentana" class="error">{{
                                        form.errors.calidadVentana[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="closeModalEditButton" class="btn btn-secondary" @click="clearInputs"
                        data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary" form="editFORM" :disabled="form.processing">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#mapEdit {
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

select {
    text-overflow: ellipsis;
}

/*Resolución para tablets (pantallas entre 768px y 1024px)*/
@media (min-width: 768px) and (min-width: 1024px) {
    #mapEdit {
        width: 90%;
        height: 350px;
    }
}
</style>
