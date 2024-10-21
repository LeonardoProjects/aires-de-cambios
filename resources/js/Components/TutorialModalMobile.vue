<template>
    <button class="btn btn-outline-primary mx-1 rounded-5 p-2 px-3 botonTutorial" @click="openTutorialModal"
        :style="{ bottom: footerVisible ? (footerHeight + 30) + 'px' : '20px' }">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-patch-question"
            viewBox="0 0 16 16">
            <path
                d="M8.05 9.6c.336 0 .504-.24.554-.627.04-.534.198-.815.847-1.26.673-.475 1.049-1.09 1.049-1.986 0-1.325-.92-2.227-2.262-2.227-1.02 0-1.792.492-2.1 1.29A1.7 1.7 0 0 0 6 5.48c0 .393.203.64.545.64.272 0 .455-.147.564-.51.158-.592.525-.915 1.074-.915.61 0 1.03.446 1.03 1.084 0 .563-.208.885-.822 1.325-.619.433-.926.914-.926 1.64v.111c0 .428.208.745.585.745" />
            <path
                d="m10.273 2.513-.921-.944.715-.698.622.637.89-.011a2.89 2.89 0 0 1 2.924 2.924l-.01.89.636.622a2.89 2.89 0 0 1 0 4.134l-.637.622.011.89a2.89 2.89 0 0 1-2.924 2.924l-.89-.01-.622.636a2.89 2.89 0 0 1-4.134 0l-.622-.637-.89.011a2.89 2.89 0 0 1-2.924-2.924l.01-.89-.636-.622a2.89 2.89 0 0 1 0-4.134l.637-.622-.011-.89a2.89 2.89 0 0 1 2.924-2.924l.89.01.622-.636a2.89 2.89 0 0 1 4.134 0l-.715.698a1.89 1.89 0 0 0-2.704 0l-.92.944-1.32-.016a1.89 1.89 0 0 0-1.911 1.912l.016 1.318-.944.921a1.89 1.89 0 0 0 0 2.704l.944.92-.016 1.32a1.89 1.89 0 0 0 1.912 1.911l1.318-.016.921.944a1.89 1.89 0 0 0 2.704 0l.92-.944 1.32.016a1.89 1.89 0 0 0 1.911-1.912l-.016-1.318.944-.921a1.89 1.89 0 0 0 0-2.704l-.944-.92.016-1.32a1.89 1.89 0 0 0-1.912-1.911z" />
            <path d="M7.001 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0" />
        </svg>
    </button>

    <div v-if="showingTutorialModal" class="modal fade show d-block" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="tutorialModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modalTutorialMobile">
            <div class="modal-content contenidoModalMobile">
                <div class="modal-header">
                    <h5 class="modal-title" id="tutorialModalLabel">Selecciona el tutorial MOBILE</h5>
                    <button type="button" class="btn-close" @click="closeTutorialModal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal -->
                <div class="modal-body">
                    <div class="d-flex justify-content-end">
                        <!-- Selector de categoría -->
                        <select v-model="categoriaSeleccionada" class="form-select mb-3 w-25 selectTutorial">
                            <option value="crearLocal">Crear local</option>
                            <option value="editarLocal">Editar local</option>
                            <option value="cantidadPersonas">Modificar cantidad de personas en el local</option>
                            <option value="eliminarLocal">Eliminar local</option>
                        </select>
                    </div>
                    <!-- Imagen y descripción -->
                    <div class="text-center">
                        <img :src="imagenCategoria?.src" alt="⚠" class="img-fluid" />
                        <p class="mt-3">{{ imagenCategoria?.description }}</p>
                    </div>

                    <div class="progress mt-3">
                        <div class="progress-bar"
                            :style="{ width: ((currentImageIndex + 1) / filtrarImagenesPorCategoria.length) * 100 + '%' }">
                            Paso {{ currentImageIndex + 1 }} de {{ filtrarImagenesPorCategoria.length }}
                        </div>
                    </div>
                </div>

                <!-- Footer con los botones de navegación -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="previousImage"
                        :disabled="currentImageIndex === 0">
                        Anterior
                    </button>
                    <button type="button" class="btn btn-primary" @click="nextImage"
                        :disabled="currentImageIndex === filtrarImagenesPorCategoria.length - 1">
                        Siguiente paso
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            footerVisible: false,
            footerHeight: 0,
            showingTutorialModal: false,
            currentImageIndex: 0,
            categoriaSeleccionada: 'crearLocal',
            images: [
                {
                    src: '/imagesTutorial/Mobile/btnCrearLocal.png',
                    description: 'Paso 1: Presione el botón "+" para crear un local.',
                    categoria: 'crearLocal'
                },
                {
                    src: '/imagesTutorial/Mobile/detallesLocal.png',
                    description: 'Paso 2: Complete los campos: Nombre, dimensiones en metros y tipo de habitación para el local.',
                    categoria: 'crearLocal'
                }, {
                    src: '/imagesTutorial/Mobile/ubicacionLocal.png',
                    description: 'Paso 3: Complete los campos: Altura, vivo en luego presione el botón "Lupa" para buscar la ubicación del local o utilize el mapa como se mostrará en el siguiente ejemplo.',
                    categoria: 'crearLocal'
                }, {
                    src: '/imagesTutorial/Mobile/ubicacionClickeada.png',
                    description: 'Navega por el mapa y presione un punto determinado',
                    categoria: 'crearLocal'
                }, {
                    src: '/imagesTutorial/Mobile/ventanaLocal.png',
                    description: 'Paso 4: Complete los campos con las dimensiones y selecciona la calidad de la ventana, por último presione el botón "Guardar cambios".',
                    categoria: 'crearLocal'
                }, {
                    src: '/imagesTutorial/Mobile/resultados.png',
                    description: 'Paso 5: Se podrán visualizar los resultados a lo largo de 15 horas mostrando icono de alerta climatica relevante y apertura de ventana en centímetros. Haciendo presionando cada hora se desplegará informacion detallada pudiendo intercambiar de alerta climática en caso que hayan varias.',
                    categoria: 'crearLocal'
                }, {
                    src: '/imagesTutorial/Mobile/btnEditarLocal.png',
                    description: 'Paso 1: Presionar el botón "Editar" para modificar los datos de un local.',
                    categoria: 'editarLocal'
                }, {
                    src: '/imagesTutorial/Mobile/modalEditarLocal.png',
                    description: 'Paso 2: Modifique los campos y presione el botón "Guardar cambios".',
                    categoria: 'editarLocal'
                },{
                    src: '/imagesTutorial/Mobile/resultadosEditar.png',
                    description: 'Paso 3: Se podrán visualizar los nuevos resultados.',
                    categoria: 'editarLocal'
                }, {
                    src: '/imagesTutorial/Mobile/cantidadPersonas.png',
                    description: 'Paso 1: Cambiar valor del campo "Cant. personas" para visualizar diferentes resultados o presione el botón "-" para disminuir en uno y "+" para aumentar en uno.',
                    categoria: 'cantidadPersonas',
                }, {
                    src: '/imagesTutorial/Mobile/btnEliminarLocal.png',
                    description: 'Paso 1: Presione el botón "Borrar" para eliminar un local.',
                    categoria: 'eliminarLocal'
                }, {
                    src: '/imagesTutorial/Mobile/modalBorrarLocal.png',
                    description: 'Paso 2: Presione el botón "Borrar local" para confirmar la eliminación.',
                    categoria: 'eliminarLocal'
                }
            ],
        };
    },
    computed: {
        filtrarImagenesPorCategoria() {
            return this.images.filter(img => img.categoria === this.categoriaSeleccionada);
        },
        imagenCategoria() {
            return this.filtrarImagenesPorCategoria[this.currentImageIndex];
        },
        currentImageProgress() {
            if (this.filtrarImagenesPorCategoria.length === 0) return '0%';
            return ((this.currentImageIndex + 1) / this.filtrarImagenesPorCategoria.length) * 100 + '%';
        }

    },
    watch: {
        categoriaSeleccionada: function () {
            this.currentImageIndex = 0;
        }
    },
    mounted() {
        window.addEventListener("scroll", this.visibilidadFooter);
        this.footerHeight = document.querySelector('footer').offsetHeight;
    },
    methods: {
        openTutorialModal() {
            this.showingTutorialModal = true;
        },
        closeTutorialModal() {
            this.showingTutorialModal = false;
        },
        nextImage() {
            if (this.currentImageIndex < this.filtrarImagenesPorCategoria.length - 1) {
                this.currentImageIndex++;
            }
        },
        previousImage() {
            if (this.currentImageIndex > 0) {
                this.currentImageIndex--;
            }
        },
        visibilidadFooter() {
            const footer = document.querySelector('footer');
            // Tomo el tamaño del footer y su posicion relativa respecto al viewport
            const footerRect = footer.getBoundingClientRect();

            // Detecta si el footer está entrando en el viewport
            this.footerVisible = footerRect.top < window.innerHeight && footerRect.bottom >= 0;
        }
    }
};
</script>

<style>
.modalTutorialMobile{
    margin-top: 30px;
}
.contenidoModalMobile{
    height: 100%;
}
</style>