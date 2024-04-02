<script setup>
import { onMounted, ref } from 'vue';
import InputLabel from "@/Components/InputLabel.vue";

const model = defineModel({
    type: String,
    required: true,
});

const props = defineProps({
    items: {
        type: Object,
        default: {},
    },
    label: {
        type: String,
        default: '',
    },
});

const input = ref(null);

</script>

<template>
    <InputLabel :value="label" />

    <select class="border border-inherit rounded-md w-full shadow" v-model="model">
        <option class="mt-2 p-2" v-for="item in items" :value="item.value">{{ item.label }}</option>
    </select>
</template>
