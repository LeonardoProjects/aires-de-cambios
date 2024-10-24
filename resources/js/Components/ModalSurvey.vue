<template>
    <div>
        <button
            type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#SurveyModal"
        >
            Realizar encuesta
        </button>

        <div
            class="modal fade"
            id="SurveyModal"
            tabindex="-1"
            aria-hidden="true"
        >
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">
                            Encuesta sobre la herramienta
                        </h1>
                        <button
                            type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"
                        ></button>
                    </div>
                    <div class="modal-body">
                        <form id="submitSurvey" @submit.prevent="submitSurvey">
                            <!-- Pregunta 1 -->
                            <div class="mb-3">
                                <label
                                    >¿Te ha parecido útil la herramienta?</label
                                >
                                <div>
                                    <input
                                        type="radio"
                                        id="utilSi"
                                        value="true"
                                        v-model="util"
                                    />
                                    <label for="utilSi">Sí</label>
                                </div>
                                <div>
                                    <input
                                        type="radio"
                                        id="utilNo"
                                        value="false"
                                        v-model="util"
                                    />
                                    <label for="utilNo">No</label>
                                </div>
                            </div>

                            <!-- Pregunta 2 -->
                            <div class="mb-3">
                                <label>¿La recomendarías a otros?</label>
                                <div>
                                    <input
                                        type="radio"
                                        id="recomendarSi"
                                        value="true"
                                        v-model="recomendar"
                                    />
                                    <label for="recomendarSi">Sí</label>
                                </div>
                                <div>
                                    <input
                                        type="radio"
                                        id="recomendarNo"
                                        value="false"
                                        v-model="recomendar"
                                    />
                                    <label for="recomendarNo">No</label>
                                </div>
                            </div>

                            <!-- Pregunta 3 -->
                            <div class="mb-3">
                                <label
                                    >¿Cómo calificarías la herramienta del 1 al
                                    5?</label
                                >
                                <select
                                    v-model="puntuacion"
                                    class="form-select"
                                >
                                    <option value="1">1 - Muy mala</option>
                                    <option value="2">2 - Mala</option>
                                    <option value="3">3 - Regular</option>
                                    <option value="4">4 - Buena</option>
                                    <option value="5">5 - Excelente</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal"
                            id="closeSurveyModalButton"
                        >
                            Cerrar
                        </button>
                        <button
                            type="submit"
                            form="submitSurvey"
                            class="btn btn-primary"
                        >
                            Guardar respuestas
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";

const page = usePage();
const userId = computed(() => page.props.auth.user.id);

export default {
    setup(props, { emit }) {
        const closeModal = () => {
            document.querySelector("#closeSurveyModalButton").click();
        };
        const util = ref(null);
        const recomendar = ref(null);
        const puntuacion = ref(null);

        const submitSurvey = () => {
            useForm({
                idUser: page.props.auth.user ? userId.value : "",
                util: util.value,
                recomendar: recomendar.value,
                puntuacion: puntuacion.value,
            }).post("/survey", {
                onSuccess: () => {
                    emit("surveyCompleted");
                    closeModal();
                },
            });
        };

        return {
            util,
            recomendar,
            puntuacion,
            submitSurvey,
        };
    },
};
</script>

<style scoped>
@media (max-width: 480px) {
    .modal-header h1 {
        font-size: 1rem;
    }

    .modal-body {
        font-size: 0.85rem;
    }

    .modal-footer button {
        font-size: 0.85rem;
    }
}
</style>
