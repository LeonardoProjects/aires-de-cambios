<template>
    <div class="d-flex justify-content-center align-items-center flex-column min-vh-100">
        <table border="1" align="center" cellpadding="10">
            <tr v-for="(item, index) in items" :key="index" @click="toggleRow(index)">
                <template v-if="!item.expanded">
                    <td>{{ item.hour }}</td>
                    <td>{{ item.cm }} cm</td>
                    <td>{{ item.expanded ? '▲' : '▼' }}</td>
                </template>
                <td colspan="3" v-else>
                    <strong>{{ item.date }}</strong> <br>
                    <img :src="item.icon" alt="Alert Icon" style="height: 20px; width: 20px;"> {{ item.details }} <br>
                    Apertura: {{ item.aperture }}
                </td>
            </tr>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            items: [
                { 
                    hour: '22:00', 
                    cm: 2, 
                    expanded: false, 
                    details: 'Precaución por baja temperatura (menor a 10°C)',
                    date: '4, Agosto - 21:00',
                    icon: 'alert-icon.png',
                    aperture: '3 cm'
                },
                { 
                    hour: '22:00', 
                    cm: 2, 
                    expanded: false, 
                    details: 'Precaución por baja temperatura (menor a 10°C)',
                    date: '4, Agosto - 21:00',
                    icon: 'alert-icon.png',
                    aperture: '3 cm'
                },
                { 
                    hour: '22:00', 
                    cm: 2, 
                    expanded: false, 
                    details: 'Precaución por baja temperatura (menor a 10°C)',
                    date: '4, Agosto - 21:00',
                    icon: 'alert-icon.png',
                    aperture: '3 cm'
                },
            ]
        }
    },
    methods: {
        toggleRow(index) {
            this.items.forEach((item, idx) => {
                if (idx !== index) item.expanded = false;
            });
            this.items[index].expanded = !this.items[index].expanded;
        }
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: opacity 0.5s ease;
}
.fade-enter, .fade-leave-to {
    opacity: 0;
}
</style>