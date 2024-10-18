<template>
    <button class="btn btn-outline-primary mx-1 rounded-5 p-0 px-2 botonTutorial"
        @click="openTutorialModal">
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
        <div class="modal-dialog modal-dialog-centered modalTutorial">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tutorialModalLabel">Aprende a Usar la Aplicación</h5>
                    <button type="button" class="btn-close" @click="closeTutorialModal" aria-label="Close"></button>
                </div>

                <!-- Cuerpo del modal para mostrar el GIF -->
                <div class="modal-body text-center">
                    <img :src="currentGif.src" alt="⚠" class="img-fluid" />
                    <p class="mt-3">{{ currentGif.description }}</p>
                </div>

                <!-- Footer con los botones de navegación -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="previousGif"
                        :disabled="currentGifIndex === 0">
                        Anterior
                    </button>
                    <button type="button" class="btn btn-primary" @click="nextGif"
                        :disabled="currentGifIndex === gifs.length - 1">
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
            showingTutorialModal: false,
            currentGifIndex: 0,
            gifs: [{
                src: null,
                description: 'CREAR AMBIENTE'
            },
            {
                src: '/gifs/btnCrearAmbiente.gif',
                description: 'Paso 1: Click en el botón "+" para crear un ambiente.'
            },
            {
                src: '/gifs/detallesAmbiente.gif',
                description: 'Paso 2: Ingresa los campos: Nombre, dimensiones en metros y tipo de habitacion para el ambiente.',
            }, {
                src: '/gifs/ubicacionAmbiente.gif',
                description: 'Paso 3: Ingresa los campos: Altura, vivo en y la ubicación del ambiente o clickea en el mapa como se mostrará en el siguiente ejemplo.'
            }, {
                src: '/gifs/selectMapa.gif',
                description: 'Navega por el mapa y clickea un punto determinado'
            }, {
                src: '/gifs/detallesAmbiente.gif',
                description: 'Paso 4: Ingresa los campos con las dimensiones y selecciona la calidad de la ventana luego hacer click en el botón "Guardar cambios".'
            }, {
                src: '/gifs/resultados.gif',
                description: 'Paso 5: Se podrán visualizar los resultados con fecha, hora, icono de alerta y apertura de ventana con la posibilidad de desplegar para mas detalles.'
            }, {
                src: null,
                description: 'EDITAR AMBIENTE'
            }, {
                src: '/gifs/editarAmbiente.gif',
                description: 'Paso 1: Click en el botón "Editar" para cambiar los datos de un ambiente.'
            }, {
                src: null,
                description: 'ALTERNAR CANTIDAD DE PERSONAS'
            }, {
                src: '/gifs/cantidadPersonas.gif',
                description: 'Paso 1: Cambiar valor del campo "cant.personas" para visualizar diferentes resultados.'
            }
            ],
        };
    },
    computed: {
        currentGif() {
            return this.gifs[this.currentGifIndex];
        }
    },
    methods: {
        openTutorialModal() {
            this.showingTutorialModal = true;
        },
        closeTutorialModal() {
            this.showingTutorialModal = false;
        },
        nextGif() {
            if (this.currentGifIndex < this.gifs.length - 1) {
                this.currentGifIndex++;
            }
        },
        previousGif() {
            if (this.currentGifIndex > 0) {
                this.currentGifIndex--;
            }
        }
    }
};
</script>

<style>
.modalTutorial {
    max-width: 600px;
}

.botonTutorial {
    max-height: 35px;
}
</style>