<script>
import ModalCRUD from "@/Components/ModalCRUD.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Show from "./Results/show.vue";
import AppLayout from "@/Layouts/AppLayout.vue";

const page = usePage();
const userId = computed(() => page.props.auth.user);

export default {
    layout: AppLayout,
    data() {
        return {
            ambientes: [],
            idAmbiente: -1,
            cantPersonas: 1,
        };
    },
    components: {
        ModalCRUD,
        Show,
    },
    methods: {
        async obtenerAmbientes() {
            const response = await axios.get(
                route("ambiente.getAll", { id: userId.value })
            );
            this.ambientes = response.data.data;
        },
        actualizarAmbientes($data) {
            this.ambientes.push($data);
            this.idAmbiente = $data.id;
        },
        cargarResultados($idAmbiente) {
            this.idAmbiente = Number($idAmbiente);
        },
    },
    mounted() {
        // Llama a la función asincrónica y espera a que termine
        this.obtenerAmbientes().then(() => {
            // Verifica si hay ambientes después de que se hayan cargado
            if (this.ambientes.length > 0) {
                this.idAmbiente = this.ambientes[0];
                this.cargarResultados(this.ambientes[0].id); // Asegúrate de usar this.cargarResultados
            }
        });
    },
};
</script>

<template>
    <div class="d-flex justify-content-center">
        <div
            class="d-flex justify-content-start align-items-center flex-column min-vh-100 divPrincipal"
        >
            <div class="d-flex flex-row position-relative divSelect">
                <ModalCRUD @updateAmbientes="actualizarAmbientes" />
                <select
                    name="selectAmbientes"
                    id="selectAmbientes"
                    class="form-select w-50"
                    @change="cargarResultados($event.target.value)"
                    v-model="idAmbiente"
                >
                    <option
                        v-for="ambiente in ambientes"
                        :key="ambiente.id"
                        :value="ambiente.id"
                    >
                        {{ ambiente.nombre }}
                    </option>
                    <option
                        v-if="ambientes && ambientes.length === 0"
                        :value="-1"
                    >
                        No hay ambientes creados
                    </option>
                </select>
                <ModalCRUD v-if="idAmbiente != -1" editFunction="true" />
                <div
                    v-if="idAmbiente != -1"
                    class="d-flex flex-column position-absolute divCantPersonas text-center"
                >
                    <label for="cantPersonas" class="form-label"
                        >Cant. personas</label
                    >
                    <input
                        type="number"
                        id="cantPersonas"
                        class="form-control"
                        min="1"
                        v-model="cantPersonas"
                    />
                </div>
            </div>
            <Show :idAmbiente="idAmbiente" :cantPersonas="cantPersonas" />
        </div>
    </div>
</template>

<style>
.divPrincipal {
    width: 650px;
    padding: 50px 0 50px 0;
}

.divSelect {
    width: 100%;
    margin-bottom: 30px;
}

.divCantPersonas {
    right: 0;
    bottom: 0;
    width: 20%;
}
</style>
