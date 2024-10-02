<script setup>
import { useForm, usePage } from "@inertiajs/vue3";
import { computed, onMounted, watch, nextTick, ref } from 'vue';

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

const props = defineProps({
    editFunction: {
        type: Boolean,
        default: false
    },
    ambiente: {
        type: Object,
        default: null
    }
});

const emit = defineEmits(['updateAmbientes']); // Definir el evento que vas a emitir

const form  = useForm({
    anchoAmbiente: 0,
    nombreAmbiente: "",
    largoAmbiente: 0,
    altoAmbiente: 0,
    tipoHabitacion: "",
    alturaSelect: "",
    longitud: -58.076054,
    latitud: -32.319339,
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

// Este método se ejecuta al montar el componente si es edición
onMounted(() => {
    if (props.editFunction && props.ambiente) {
        cargarAmbiente(props.ambiente); 
    }
});

// Crear un watch para actualizar el formulario cuando cambia el ambiente
watch(() => props.ambiente, (newAmbiente) => {
    if (props.editFunction && newAmbiente) {
        cargarAmbiente(props.ambiente); 
    }
});

// Función para cargar los datos del ambiente en el formulario
function cargarAmbiente(ambiente) {
    form.reset();
    form.nombreAmbiente = ref([ambiente.nombre]) || "";
    form.anchoAmbiente = Number(ambiente.local.ancho) || 0;
    form.largoAmbiente = Number(ambiente.local.largo) || 0;
    form.altoAmbiente = Number(ambiente.local.alto) || 0;
    form.tipoHabitacion = ambiente.local.tipoHabitacion || "";
    form.alturaSelect = ambiente.ubicacion.altura || "";
    form.densidadSelect = ambiente.ubicacion.densidad || "";
    form.largoVentana = Number(ambiente.ventana.largo) || 0;
    form.altoVentana = Number(ambiente.ventana.alto) || 0;
    form.calidadVentana = ambiente.ventana.calidad || "";
    console.log(form);
}

function emitirAmbiente($ambiente) {
    emit('updateAmbientes', $ambiente);
}

function clearInputs() {
    form.reset();
}

function closeModal() {
    document.querySelector('#closeModalButton').click();
}
</script>

<template>
    <button type="button" class="btn btn-outline-primary mx-1 rounded-5 p-0 px-2" data-bs-toggle="modal"
        data-bs-target="#staticBackdrop" @click="editFunction ? cargarAmbiente(ambiente) : null">
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
                        <span v-if="props.editFunction">Editar ambiente</span>
                        <span v-else>Ambiente</span>
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
                                    <input id="anchoAmbiente" type="number" min="0" max="20" step="0,1" class="form-control"
                                        v-model="form.anchoAmbiente" />
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

                        <div class="mb-5">
                            <h3>Ubicación</h3>
                            <hr />
                            <div class="d-flex h-50">
                                <div class="me-3 w-50 text-center">
                                    <label for="alturaSelect" class="form-label">
                                        Altura</label>
                                    <select name="alturaSelect" id="altura" v-model="form.alturaSelect" class="form-select"
                                        @change="form.clearErrors('alturaSelect')">
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
                                    <div v-if="form.errors.densidadSelect" class="error">{{ form.errors.densidadSelect[0] }}
                                    </div>
                                </div>
                            </div>

                            <input type="text" id="buscador" class="form-control my-3"
                                placeholder="Ingresa la ubicación del ambiente" />
                            <div id="map"></div>
                        </div>

                        <div class="mb-0">
                            <h3>Detalles de ventana</h3>
                            <hr />
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-50 text-center">
                                    <label for="largoVentana" class="form-label">
                                        Largo (m)</label>
                                    <input id="largoVentana" type="number" min="0" max="20" step="0.1" class="form-control"
                                        v-model="form.largoVentana" />
                                </div>
                                <div class="mx-4 w-50 text-center">
                                    <label for="altoVentana" class="form-label">
                                        Alto (m)</label>
                                    <input id="altoVentana" type="number" min="0" max="20" step="0.1" class="form-control"
                                        v-model="form.altoVentana" />
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
                                    <div v-if="form.errors.tipoVentana" class="error">{{ form.errors.tipoVentana[0] }}</div>
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
                                    <div v-if="form.errors.calidadVentana" class="error">{{ form.errors.calidadVentana[0] }}
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
    min-height: 200px;
    max-height: 300px;
    min-width: 50%;
    max-width: 80%;
    background-color: gray;
    margin: 0 50px 0 50px;
}

#buscador {
    width: 75%;
    margin: 0 57px 0 57px;
}

.error {
    color: red;
    font-size: 0.9em;
}
</style>
