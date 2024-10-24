<template>
    <div @click="handleClick" :class="class">
        <svg v-if="alertName == 'vientosIcon'" id="vientosIcon" xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24"
            width="24">
            <g>
                <path d="M0,0h24v24H0V0z" fill="none" />
            </g>
            <g>
                <g>
                    <path
                        d="M14.5,17c0,1.65-1.35,3-3,3s-3-1.35-3-3h2c0,0.55,0.45,1,1,1s1-0.45,1-1s-0.45-1-1-1H2v-2h9.5 C13.15,14,14.5,15.35,14.5,17z M19,6.5C19,4.57,17.43,3,15.5,3S12,4.57,12,6.5h2C14,5.67,14.67,5,15.5,5S17,5.67,17,6.5 S16.33,8,15.5,8H2v2h13.5C17.43,10,19,8.43,19,6.5z M18.5,11H2v2h16.5c0.83,0,1.5,0.67,1.5,1.5S19.33,16,18.5,16v2 c1.93,0,3.5-1.57,3.5-3.5S20.43,11,18.5,11z" />
                </g>
            </g>
        </svg>
        <svg v-else-if="alertName == 'frioIcon'" id="frioIcon" xmlns="http://www.w3.org/2000/svg" height="24"
            viewBox="0 0 24 24" width="24">
            <path d="M0 0h24v24H0z" fill="none" />
            <path
                d="M22 11h-4.17l3.24-3.24-1.41-1.42L15 11h-2V9l4.66-4.66-1.42-1.41L13 6.17V2h-2v4.17L7.76 2.93 6.34 4.34 11 9v2H9L4.34 6.34 2.93 7.76 6.17 11H2v2h4.17l-3.24 3.24 1.41 1.42L9 13h2v2l-4.66 4.66 1.42 1.41L11 17.83V22h2v-4.17l3.24 3.24 1.42-1.41L13 15v-2h2l4.66 4.66 1.41-1.42L17.83 13H22z" />
        </svg>
        <svg v-else-if="alertName == 'tormentasIcon'" id="tormentasIcon" xmlns="http://www.w3.org/2000/svg" height="24"
            viewBox="0 0 24 24" width="24">
            <path d="M0 0h24v24H0z" fill="none" />
            <path d="M7 2v11h3v9l7-12h-4l4-8z" />
        </svg>
        <svg v-else-if="alertName == 'lluviaIcon'" id="lluviaIcon" xmlns="http://www.w3.org/2000/svg"
            enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24">
            <rect fill="none" height="24" width="24" />
            <path
                d="M12,2c-5.33,4.55-8,8.48-8,11.8c0,4.98,3.8,8.2,8,8.2s8-3.22,8-8.2C20,10.48,17.33,6.55,12,2z M7.83,14 c0.37,0,0.67,0.26,0.74,0.62c0.41,2.22,2.28,2.98,3.64,2.87c0.43-0.02,0.79,0.32,0.79,0.75c0,0.4-0.32,0.73-0.72,0.75 c-2.13,0.13-4.62-1.09-5.19-4.12C7.01,14.42,7.37,14,7.83,14z" />
        </svg>
        <svg v-else-if="alertName == 'agradableIcon'" id="agradableIcon" xmlns="http://www.w3.org/2000/svg"
            enable-background="new 0 0 24 24" height="24" viewBox="0 0 24 24" width="24">
            <g>
                <path d="M0,0h24v24H0V0z" fill="none" />
            </g>
            <g>
                <path
                    d="M15,13V5c0-1.66-1.34-3-3-3S9,3.34,9,5v8c-1.21,0.91-2,2.37-2,4c0,2.76,2.24,5,5,5s5-2.24,5-5C17,15.37,16.21,13.91,15,13z M11,11V5c0-0.55,0.45-1,1-1s1,0.45,1,1v1h-1v1h1v1v1h-1v1h1v1H11z" />
            </g>
        </svg>
    </div>
</template>
<script>
export default {
    props: {
        alertName: String,
        class:
        {
            type: String,
            default: ''
        }
    },
    emits: [
        'updateAlertName'
    ],
    methods: {
        handleClick() {
            // Emit an event to parent component with the alert name
            this.$emit('updateAlertName', this.alertName);
        }
    }
}
</script>