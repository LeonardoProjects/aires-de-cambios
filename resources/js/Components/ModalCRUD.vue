<script setup>
import { router, useForm } from "@inertiajs/vue3";

const props = defineProps({
    titulo: String,
});

const form = useForm({
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
});

function submit() {
    router.post("/ambiente/store", form);
}

function clearInputs() {
    form.reset();
}
</script>

<template>
    <button
        type="button"
        class="btn btn-primary"
        data-bs-toggle="modal"
        data-bs-target="#staticBackdrop"
    >
        Crear ambiente
    </button>

    <!-- Modal -->
    <div
        class="modal fade"
        id="staticBackdrop"
        data-bs-backdrop="static"
        data-bs-keyboard="false"
        tabindex="-1"
        aria-labelledby="staticBackdropLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">
                        {{ props.titulo }}
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body text-center">
                    <form @submit.prevent="submit" id="crudFORM">
                        <div class="mb-5">
                            <h3>Detalles del ambiente</h3>
                            <hr />
                            <div class="mt-1 mb-4 mx-4 text-center">
                                <label for="nombreAmbiente" class="form-label"
                                        >Nombre de ambiente</label
                                    >
                                    <input
                                        id="nombreAmbiente"
                                        type="text"
                                        class="form-control w-0"
                                        v-model="form.nombreAmbiente"
                                    />
                            </div>
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-25 text-center">
                                    <label
                                        for="anchoAmbiente"
                                        class="form-label"
                                        >Ancho (m)</label
                                    >
                                    <input
                                        id="anchoAmbiente"
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0,1"
                                        class="form-control"
                                        v-model="form.anchoAmbiente"
                                    />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label
                                        for="largoAmbiente"
                                        class="form-label"
                                        >Largo (m)</label
                                    >
                                    <input
                                        id="largoAmbiente"
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0,1"
                                        value="0"
                                        class="form-control w-0"
                                        v-model="form.largoAmbiente"
                                    />
                                </div>
                                <div class="mx-4 w-25 text-center">
                                    <label for="altoAmbiente" class="form-label"
                                        >Alto (m)</label
                                    >
                                    <input
                                        id="altoAmbiente"
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0,1"
                                        value="0"
                                        class="form-control w-0"
                                        v-model="form.altoAmbiente"
                                    />
                                </div>
                            </div>
                            <div class="mx-5 mt-4 w-75 text-center">
                                <label for="tipoHabitacion" class="form-label"
                                    >Tipo de habitación</label
                                >
                                <select
                                    name="tipoHabitacionSelect"
                                    id="tipoHabitacion"
                                    class="form-select"
                                    v-model="form.tipoHabitacion"
                                >
                                    <option value="Dormitorio">
                                        Dormitorio
                                    </option>
                                    <option value="EstarComedor">
                                        Estar o comedor
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <h3>Ubicación</h3>
                            <hr />
                            <div class="d-flex h-50">
                                <div class="me-3 w-50 text-center">
                                    <label
                                        for="alturaSelect"
                                        class="form-label"
                                    >
                                        Altura</label
                                    >
                                    <select
                                        name="alturaSelect"
                                        id="altura"
                                        v-model="form.alturaSelect"
                                        class="form-select"
                                    >
                                        <option value="PlantaBaja">
                                            Planta baja
                                        </option>
                                        <option value="PrimerOsegundoPiso">
                                            1° o 2° piso
                                        </option>
                                        <option value="TercerAQuintoPiso">
                                            3° a 5° piso
                                        </option>
                                        <option value="SextoPisoOMas">
                                            6° piso o más
                                        </option>
                                    </select>
                                </div>

                                <div class="ms-3 w-50 text-center">
                                    <label
                                        for="densidadSelect"
                                        class="form-label"
                                    >
                                        Vivo en</label
                                    >
                                    <select
                                        name="densidadSelect"
                                        id="densidad"
                                        class="form-select"
                                        v-model="form.densidadSelect"
                                    >
                                        <option value="FrenteAlMar">
                                            Frente al mar
                                        </option>
                                        <option value="ElCampo">
                                            El campo
                                        </option>
                                        <option value="UnBarrioPocoDenso">
                                            Un barrio poco denso
                                        </option>
                                        <option value="UnBarrioMuyDenso">
                                            Un barrio muy denso
                                        </option>
                                        <option
                                            value="ElCentroConEdificiosAltos"
                                        >
                                            El centro con edificios altos
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <input
                                type="text"
                                id="buscador"
                                class="form-control my-3"
                                placeholder="Ingresa la ubicación del ambiente"
                            />
                            <div id="map"></div>
                        </div>

                        <div class="mb-0">
                            <h3>Detalles de ventana</h3>
                            <hr />
                            <div class="d-flex h-50 flex-row">
                                <div class="mx-4 w-50 text-center">
                                    <label
                                        for="largoVentana"
                                        class="form-label"
                                    >
                                        Largo (m)</label
                                    >
                                    <input
                                        id="largoVentana"
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0,1"
                                        class="form-control"
                                        v-model="form.largoVentana"
                                    />
                                </div>
                                <div class="mx-4 w-50 text-center">
                                    <label for="altoVentana" class="form-label">
                                        Alto (m)</label
                                    >
                                    <input
                                        id="altoVentana"
                                        type="number"
                                        min="0"
                                        max="10"
                                        step="0,1"
                                        class="form-control"
                                        v-model="form.altoVentana"
                                    />
                                </div>
                            </div>
                            <div class="d-flex mt-4 flex-row">
                                <div class="mx-3 w-50 text-center">
                                    <label for="tipoVentana" class="form-label">
                                        Tipo de ventana</label
                                    >
                                    <select
                                        name="tipoVentanaSelect"
                                        id="tipoVentana"
                                        class="form-select"
                                        v-model="form.tipoVentana"
                                    >
                                        <option value="Corrediza">
                                            Corrediza
                                        </option>
                                    </select>
                                </div>
                                <div class="mx-3 w-50 text-center">
                                    <label
                                        for="calidadVentana"
                                        class="form-label"
                                    >
                                        Calidad de ventana</label
                                    >
                                    <select
                                        name="calidadSelect"
                                        id="calidadVentana"
                                        class="form-select"
                                        v-model="form.calidadVentana"
                                    >
                                        <option value="Normal">Normal</option>
                                        <option value="Mejorada">
                                            Mejorada
                                        </option>
                                        <option value="Reforzada">
                                            Reforzada
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        @click="clearInputs"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        form="crudFORM"
                    >
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
</style>
