<script>
import ModalCRUD from "@/Components/ModalCRUD.vue";
import ModalEditAmbiente from "@/Components/ModalEditAmbiente.vue";
import DeleteAmbienteConfirm from "@/Components/DeleteAmbienteConfirm.vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import Show from "./Results/show.vue";
import EventBus from './../EventBus';

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
        actualizarAmbientesLocalStorage(nuevoAmbiente) {
            console.log("entra al actualizarAmbientes del local storage:");
            console.log("nuevoAmbiente:", nuevoAmbiente);
            this.ambientes.push(nuevoAmbiente);
            this.idAmbiente = nuevoAmbiente.data.id;
            localStorage.setItem(`loggedAmbiente${nuevoAmbiente.idUsuario}`, nuevoAmbiente.id.toString());
        },
        actualizarAmbientesPostAdd($data) {
            const ambienteAdd = Object.values($data)[0];
            this.ambientes.push(ambienteAdd);
            this.idAmbiente = ambienteAdd.id;
            localStorage.setItem(`loggedAmbiente${ambienteAdd.idUsuario}`, ambienteAdd.id.toString());
        },
        actualizarAmbientesPostEdit($data) {
            const ambiente = Object.values($data)[0];
            const index = this.ambientes.findIndex(item => item.id === ambiente.id);
            if (index !== -1) {
                this.ambientes.splice(index, 1, ambiente);
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
        },
        aumentarCantPersonas() {
            this.cantPersonas++;
            this.$refs.resultados.cargarDatos();
        },
        disminuirCantPersonas() {
            if (this.cantPersonas > 1) {
                this.cantPersonas--;
                this.$refs.resultados.cargarDatos();
            }
        }
    },
    mounted() {
        EventBus.$on('ambienteCreadoConLocalStorage', this.actualizarAmbientesLocalStorage);
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
            <h3 class="d-md-none d-block">Ajustes de local</h3>
            <div v-if="$page.props.auth.user" class="divAjustes">
                <div v-if="idAmbiente != -1" class="d-md-none d-flex flex-column flex-wrap divCa text-center">
                    <label for="cantPersonas" class="form-label">Cant. personas</label>
                    <div class="d-flex justify-content-center mb-2">
                        <button class="btn btn-danger rounded-5 p-1 mx-2" @click="disminuirCantPersonas">
                            <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24"
                                viewBox="0 0 24 24" width="24">
                                <g>
                                    <rect fill="none" fill-rule="evenodd" height="24" width="24" />
                                    <rect fill="white" fill-rule="evenodd" height="2" width="16" x="4" y="11" />
                                </g>
                            </svg>
                        </button>
                        <input type="text" id="cantPersonas" class="form-control text-center" min="1"
                            v-model="cantPersonas" />
                        <button class="btn btn-success rounded-5 p-1 mx-2" @click="aumentarCantPersonas">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                                <path d="M0 0h24v24H0z" fill="none" />
                                <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z" fill="white" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="d-flex flex-row position-relative divSelect w-100">
                    <ModalCRUD @updateAmbientes="actualizarAmbientesPostAdd" />
                    <select name="selectAmbientes" id="selectAmbientes" class="form-select"
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
                    <div v-if="idAmbiente != -1"
                        class="d-none d-md-flex flex-column position-absolute divCantPersonas text-center">
                        <label for="cantPersonas" class="form-label">Cant. personas</label>
                        <input type="number" id="cantPersonas" class="form-control" min="1" v-model="cantPersonas" />
                    </div>
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
    margin-bottom: 30px;

    #selectAmbientes {
        width: 50%;
    }

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

.divAjustes {
    width: 100%;
}

@media screen and (max-width: 767px) {
    .divSelect {
        padding-top: 10px;
        justify-content: center;
    }
    
    #selectAmbiente {
        width: 40% !important;
        text-overflow: ellipsis;
    }
    .divCantPersonas {
        width: 30% !important;
    }

    #cantPersonas {
        width: 15% !important;
    }

    label {
        font-size: 0.9em !important;
    }

    .divAjustes {
        width: 400px;
    }
}</style>
