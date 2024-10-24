<template lang="">
    <button type="button" class="btn btn-outline-danger mx-1 rounded-5 px-2" data-bs-toggle="modal"
        data-bs-target="#confirmDeleteAmbiente">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash"
            viewBox="0 0 17 17">
            <path
                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
            <path
                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
        </svg>
    </button>
    <!-- Modal de confirmación de eliminación -->
    <div class="modal fade" id="confirmDeleteAmbiente" data-bs-backdrop="static" data-bs-keyboard="true"
        tabindex="-1" aria-labelledby="confirmDeleteAmbienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content position-relative">
                <div v-if="loaderVisibleDelete" class="modal-overlay">
                    <div class="loader loaderDelete"></div>
                </div>
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteAmbienteLabel">Eliminar local</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <p>¿Estás seguro de eliminar el local?</p>
                    <p>Se eliminarán todos sus datos</p>
                </div>
                <div class="modal-footer">
                    <button type="button" id="deleteAmbientecloseModalButton" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" @click="deleteAmbiente">
                        Borrar local
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        idAmbiente: Number
    },
    data() {
        return {
            loaderVisibleDelete: false
        }
    },
    emits: ['deleteAmbiente'],
    methods: {
        closeModal() {
            document.querySelector('#deleteAmbientecloseModalButton').click();
        },
        async deleteAmbiente() {
            this.loaderVisibleDelete = true;
            let response = await axios({
                method: 'POST',
                url: this.route("ambiente.delete"),
                data: {
                    idAmbiente: this.idAmbiente
                }
            });
            if (response.status == 200) {
                this.$emit('deleteAmbiente', response.data.data);
                this.closeModal();
            }
            this.loaderVisibleDelete = false;
        }
    }
}
</script>
<style>
.loaderDelete {
    background: #dc3545;
}
</style>