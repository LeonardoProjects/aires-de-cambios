<script>
import ModalCRUD from "@/Components/ModalCRUD.vue";
import ModalEditAmbiente from "@/Components/ModalEditAmbiente.vue";
import DeleteAmbienteConfirm from "@/Components/DeleteAmbienteConfirm.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Show from "./Results/show.vue";

const page = usePage();
const userId = computed(() => page.props.auth.user);

export default {
    layout: AppLayout,
    data() {
        return {
            ambientes: [],
            idAmbiente: -1,
            cantPersonas: 1,
            ambienteCreado: false
        };
    },
    components: {
        ModalCRUD,
        Show,
        ModalEditAmbiente,
        DeleteAmbienteConfirm
    },
    methods: {
        async obtenerAmbientes() {
            if (page.props.auth.user) {
                const response = await axios.get(
                    route("ambiente.getAll", { id: userId.value })
                );
                this.ambientes = response.data.data;
            }
        },
        actualizarAmbientesPostAdd($data) {
            const ambienteAdd = Object.values($data)[0];
            this.ambientes.push(ambienteAdd);
            this.idAmbiente = ambienteAdd.id;
            localStorage.setItem(`loggedAmbiente${ambienteAdd.idUsuario}`, ambienteAdd.id.toString());
        },
        actualizarAmbientesPostEdit($data) {
            // Extraer el ambiente desde el objeto anidado
            const ambiente = Object.values($data)[0]; // Toma el primer valor, que es el objeto con la clave numérica
            // Ahora puedes acceder al id del ambiente
            const index = this.ambientes.findIndex(item => item.id === ambiente.id);
            if (index !== -1) {
                this.ambientes.splice(index, 1, ambiente); // Reemplaza el ambiente con el nuevo
            }
            this.$refs.resultados.cargarDatos();
            localStorage.setItem(`loggedAmbiente${ambiente.idUsuario}`, ambiente.id.toString());
        },
        addAmbienteLocalStorage() {
            this.idAmbiente = -2;
            this.$refs.resultados.cargarDatos();
            this.ambienteCreado = true;
        },
        cargarResultados($idAmbiente) {
            this.idAmbiente = Number($idAmbiente);
            localStorage.setItem(`loggedAmbiente${userId.value.id}`, $idAmbiente.toString());
        },
        obtenerAmbienteXid($idAmbiente) {
            let $ambiente = null;
            for (const amb of this.ambientes) {
                if (amb.id === Number($idAmbiente)) {
                    $ambiente = amb;
                    break;
                }
            };
            return $ambiente;
        },
        deleteAmbiente($data) {
            const index = this.ambientes.findIndex(item => item.id === $data);
            if (index !== -1) {
                this.ambientes.splice(index, 1);
            }
            if (this.ambientes.length > 0) {
                this.idAmbiente = this.ambientes[0].id;
                this.$forceUpdate();
                this.cargarResultados(this.ambientes[0].id);
            } else {
                this.idAmbiente = -1;
                this.$refs.resultados.datosCalculos = [];
            }
        }
    },
    mounted() {
        this.obtenerAmbientes().then(() => {
            if (this.ambientes.length > 0) {
                const loggedAmbiente = localStorage.getItem(`loggedAmbiente${userId.value.id}`);

                if (loggedAmbiente) {
                    const ambienteId = Number(loggedAmbiente); // Convierte el string a número
                    this.idAmbiente = ambienteId;
                    this.cargarResultados(ambienteId); // Usa el ID numérico
                } else {
                    const primerAmbienteId = this.ambientes[0].id;
                    localStorage.setItem(`loggedAmbiente${userId.value.id}`, primerAmbienteId.toString());
                    this.idAmbiente = primerAmbienteId;
                    this.cargarResultados(primerAmbienteId);
                }
            } else if (!page.props.auth.user) {
                const ambienteNotLogged = localStorage.getItem('ambienteNotLogged');
                if (ambienteNotLogged) {
                    this.idAmbiente = -2;
                    this.$refs.resultados.cargarDatos();
                    this.ambienteCreado = true;
                }
            }
        });
    }
};
</script>

<template>
    <div class="d-flex justify-content-center">
        <div class="d-flex justify-content-start align-items-center flex-column min-vh-100 divPrincipal">
            <div v-if="$page.props.auth.user" class="d-flex flex-row position-relative divSelect">
                <ModalCRUD @updateAmbientes="actualizarAmbientesPostAdd" />
                <select name="selectAmbientes" id="selectAmbientes" class="form-select w-50"
                    @change="cargarResultados($event.target.value)" v-model="idAmbiente">
                    <option v-for="ambiente in ambientes" :key="ambiente.id" :value="ambiente.id">
                        {{ ambiente.nombre }}
                    </option>
                    <option v-if="ambientes && ambientes.length === 0" :value="-1">
                        No hay locales creados
                    </option>
                </select>
                <ModalEditAmbiente v-if="idAmbiente != -1" @updateAmbientesEdit="actualizarAmbientesPostEdit"
                    :ambiente="obtenerAmbienteXid(idAmbiente)" />
                <DeleteAmbienteConfirm :idAmbiente="idAmbiente" @deleteAmbiente="deleteAmbiente" />
                <div v-if="idAmbiente != -1" class="d-flex flex-column position-absolute divCantPersonas text-center">
                    <label for="cantPersonas" class="form-label">Cant. personas</label>
                    <input type="number" id="cantPersonas" class="form-control" min="1" v-model="cantPersonas" />
                </div>
            </div>
            <div v-else class="d-flex justify-content-center position-relative divSelect">
                <ModalCRUD v-if="!ambienteCreado" :notLogged="true" @updateLocalStorage="addAmbienteLocalStorage" />
                <ModalEditAmbiente v-if="ambienteCreado" @updateAmbientesEdit="actualizarAmbientesPostEdit"
                    :ambiente="obtenerAmbienteXid(idAmbiente)" />
                <p v-if="ambienteCreado">Si quieres más locales, ¡Inicia sesión!</p>
            </div>
            <Show ref="resultados" :idAmbiente="idAmbiente" :cantPersonas="cantPersonas" />
        </div>
    </div>
</template>

<style>
svg {
    vertical-align: middle;
}

.divPrincipal {
    width: 650px;
    padding: 50px 0 0 0;
}

.divSelect {
    width: 100%;
    margin-bottom: 30px;

    p {
        margin: 0;
        display: flex;
        align-items: center;
    }
}

.divCantPersonas {
    right: 0;
    bottom: 0;
    width: 20%;
}

@media screen and (max-width: 768px) {
    .divSelect {
        width: 65% !important;
        padding-top: 10px;

        select {
            width: 40% !important;
            text-overflow: ellipsis;
        }
    }

    .divCantPersonas {
        width: 30% !important;
    }

    label {
        font-size: 0.9em !important;
    }
}
</style>
