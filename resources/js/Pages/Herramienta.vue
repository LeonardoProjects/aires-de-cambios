<script>
import ModalCRUD from '@/Components/ModalCRUD.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import Show from "./Results/show.vue";

const page = usePage();
const userId = computed(() => page.props.auth.user);

export default {
    data() {
        return {
            ambientes: [],
            idAmbiente: 1
        }
    },
    components: {
        ModalCRUD,
        Show
    },
    methods: {
        async obtenerTemas() {
            const response = await axios.get(route('ambiente.getAll', { id: userId.value }));
            this.ambientes = response.data.data;
        },
        actualizarAmbientes($data) {
            this.ambientes = $data;
        },  
        cargarResultados($idAmbiente) {
            this.idAmbiente = Number($idAmbiente);
            console.log(this.idAmbiente);
            console.log('Cargando resultados en Show.vue ' + $idAmbiente);
        },
        enviarEvento(){
            this.idAmbiente = 1;
        }
    },
    mounted() {
        this.obtenerTemas();
    }
}

</script>

<template>
    <div class="d-flex justify-content-center align-items-center flex-column min-vh-100">
        <label for="selectAmbientes" class="form-label"></label>
        <select name="selectAmbientes" id="selectAmbientes" class="form-select" 
        @change="cargarResultados($event.target.value)">
        <option v-for="(ambiente) in ambientes" :key="ambiente.id" 
        :value="ambiente.id">{{ ambiente.nombre }}</option>
        <option v-if="ambientes && ambientes.length === 0" :value="-1">No hay ambientes creados</option>
        </select>
        <ModalCRUD titulo="Crear ambiente" @updateAmbientes="actualizarAmbientes"/>
        <Show :idAmbiente="idAmbiente" />
    </div>
</template>

<style>

</style>
