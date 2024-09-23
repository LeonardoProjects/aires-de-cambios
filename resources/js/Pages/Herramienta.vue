<script>
import ModalCRUD from '@/Components/ModalCRUD.vue';
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const userId = computed(() => page.props.auth.user);

export default {
    data() {
        return {
            ambientes: []
        }
    },
    components: {
        ModalCRUD
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
            console.log('Cargando resultados en Show.vue ' + $idAmbiente);
        }
    },
    mounted() {
        this.obtenerTemas();
    }
}

</script>

<template>
    <div class="d-flex justify-content-center">
        <label for="selectAmbientes" class="form-label"></label>
        <select name="selectAmbientes" id="selectAmbientes" class="form-select" 
        @change="cargarResultados($event.target.value)">
        <option v-for="(ambiente) in ambientes" :key="ambiente.id" 
        :value="ambiente.id">{{ ambiente.nombre }}</option>
        <option v-if="ambientes && ambientes.length === 0" :value="-1">No hay ambientes creados</option>
        </select>
        <ModalCRUD titulo="Crear ambiente" @updateAmbientes="actualizarAmbientes"/>

    </div>
</template>
<style>
@import "../../css/Herramienta.css"
</style>