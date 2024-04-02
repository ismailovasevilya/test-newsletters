<template>
    <div>
        <Datepicker v-model="date" :enable-time-picker="enableTimePicker" />
    </div>
</template>

<script setup>
import { ref } from 'vue';
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

const model = defineModel({
    type: Date,
    required: false,
});

const props = defineProps({
    enableTimePicker: {
        type: Boolean,
        default: false,
        required: false
    },
});

const date = ref(model);

</script>
