<script>
import ModalCRUD from '@/Components/ModalCRUD.vue';

export default {
    components: {
        ModalCRUD
    },
    methods: {
        // A esta función le falta que, si existe otra row visible, se cierre.
        mostrar_ocultarRow(index) {
            const row = document.querySelector('tr[data-index="' + index + '"].hiddenRow');
            if (row && row.classList.contains('d-none')) {
                // Si la fila está oculta, la mostramos
                row.classList.remove('d-none');
            } else if (row) {
                // Si la fila está visible, la ocultamos
                row.classList.add('d-none');
            } else {
                console.log("No se encontró la fila con el índice proporcionado.");
            }
        }
    }
}
</script>

<template>
    <div class="d-flex justify-content-center align-items-center flex-column min-vh-100">

        <ModalCRUD />

        <table border=1 id="table_detail" align=center cellpadding=10>

            <tr>
                <th>Name</th>
                <th>Age</th>
                <th>Salary</th>
                <th>Job</th>
            </tr>

            <tr data-index="1" @click="mostrar_ocultarRow(1);">
                <td>Person-1</td>
                <td>24</td>
                <td>60000</td>
                <td>Computer Programmer</td>
            </tr>
            <tr data-index="1" class="hiddenRow d-none">
                <td colspan=4>
                    Person-1 is 24 years old and
                    he is a computer programmer
                    he earns 60000 per month
                </td>
            </tr>

            <tr data-index="2" @click="mostrar_ocultarRow(2);">
                <td>Person-2</td>
                <td>25</td>
                <td>100000</td>
                <td>Web Designer</td>
            </tr>
            <tr data-index="2" class="hiddenRow d-none">
                <td colspan=4>
                    Person-2 is 25 years old and
                    she is a web designer she earns
                    100000 per month
                </td>
            </tr>

            <tr data-index="3" @click="mostrar_ocultarRow(3);">
                <td>Person-3</td>
                <td>35</td>
                <td>90000</td>
                <td>Cyber Security Expert</td>
            </tr>
            <tr data-index="3" class="hiddenRow d-none">
                <td colspan=4>
                    Person is 35 years old and he
                    is a cyber security expert he
                    earns 90000 per month
                </td>
            </tr>

            <tr data-index="4" @click="mostrar_ocultarRow(4);">
                <td>Person-4</td>
                <td>52</td>
                <td>200000</td>
                <td>Content Writer</td>
            </tr>
            <tr data-index="4" class="hiddenRow d-none">
                <td colspan=4>
                    Person-4 is 52 years old and he
                    is a content writer he earns
                    200000 per month
                </td>
            </tr>

            <tr data-index="5" @click="mostrar_ocultarRow(5);">
                <td>Person-5</td>
                <td>38</td>
                <td>400000</td>
                <td>Chief Executive</td>
            </tr>
            <tr data-index="5" class="hiddenRow d-none">
                <td colspan=4>
                    Person-5 is 38 years old and he
                    is chief executive he earns
                    400000 per month
                </td>
            </tr>
        </table>
    </div>
</template>
<style>
@import "../../css/Herramienta.css"
</style>