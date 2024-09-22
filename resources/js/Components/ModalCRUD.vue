<script setup>
import { router, useForm } from "@inertiajs/vue3";
import { reactive } from "vue"


const form = useForm({
    anchoAmbiente: 0,
    nombreAmbiente: "",
    largoAmbiente: 0,
    altoAmbiente: 0,
    tipoHabitacion: "",
    alturaSelect: "",
    densidadSelect: "",
    largoVentana: 0,
    altoVentana: 0,
    tipoVentana: "Corrediza",
    calidadVentana: "",
});

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
                        {{ title }}
                    </h1>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                    ></button>
                </div>
                <div class="modal-body text-center">
                    <form @submit="form.post('/ambiente/create')">
                        <div class="mb-5">
                            <h3>Detalles del ambiente</h3>
                            <hr />
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
                                        value="0"
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
                                <label for="nombreAmbiente" class="form-label"
                                    >Nombre</label
                                >
                                <input
                                    id="nombreAmbiente"
                                    type="text"
                                    class="form-control w-0"
                                    v-model="form.nombreAmbiente"
                                />
                            </div>
                        </div>

                        <div class="mb-3">
                            <h3>Ubicación</h3>
                            <hr />
                            <div class="d-flex h-50">
                                <div class="f w-50 text-center">
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

                                <div class="f w-50 text-center">
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

                            <input type="text" id="buscador" />
                            <div id="map"></div>
                        </div>

                        <div class="mb-3">
                            <h3>Detalles de ventana</h3>
                            <hr />
                            <label for="largoVentana">
                                Largo (m)
                                <input
                                    id="largoVentana"
                                    type="number"
                                    min="0"
                                    max="10"
                                    step="0,1"
                                    value="0"
                                    v-model="form.largoVentana"
                                />
                            </label>
                            <label for="altoVentana">
                                Alto (m)
                                <input
                                    id="altoVentana"
                                    type="number"
                                    min="0"
                                    max="10"
                                    step="0,1"
                                    value="0"
                                    v-model="form.altoVentana"
                                />
                            </label>
                            <label for="tipoVentana">
                                Tipo de ventana
                                <select
                                    name="tipoVentanaSelect"
                                    id="tipoVentana"
                                    v-model="form.tipoVentana"
                                >
                                    <option value="Corrediza">Corrediza</option>
                                </select>
                            </label>
                            <label for="calidadVentana">
                                Calidad de ventana
                                <select
                                    name="calidadSelect"
                                    id="calidadVentana"
                                    v-model="form.calidadVentana"
                                >
                                    <option value="Normal">Normal</option>
                                    <option value="Mejorada">Mejorada</option>
                                    <option value="Reforzada">Reforzada</option>
                                </select>
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        lass="btn btn-primary">
                        Guardar cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
#map {
    min-height: 150px;
    max-height: 200px;
    min-width: 50%;
    max-width: 80%;
    background-color: gray;
}
</style>
