<script setup>
import {computed, onMounted, watch, ref} from 'vue';
import {useForm, router} from "@inertiajs/vue3";


const options = [10, 20, 35, 50]

const props = defineProps({
    data: Object,
})

const parameters = useForm({
    perPage: '20'
});

watch(() => parameters.perPage, (newValue) => {
    router.reload({
        data: {
            per_page: newValue
        }
    })
});

onMounted(() => {
    const url = window.location.search.substring(1);
    const params = new URLSearchParams(url);
    if (params.has('per_page')) {
        parameters.perPage = params.get('per_page')
    }
});

const prevPageUrl = computed(() => {
    return props.data.prev_page_url + `&per_page=${parameters.perPage}`
});

const nextPageUrl = computed(() => {
    return props.data.next_page_url + `&per_page=${parameters.perPage}`
});


const links = computed(() => {
    const originalLinks = props.data.links;
    const modifiedLinks = originalLinks.slice(1, -1).map(link => ({ ...link }));

    modifiedLinks.forEach(link => {
        link.url = link.url + `&per_page=${parameters.perPage}`;
    });

    return modifiedLinks;
});
const linkGeneralStyles = computed(() => {
    return [
        "transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer", // Default
        "focus:ring-4 focus:ring-primary focus:ring-opacity-20", // On focus
        "focus-visible:outline-none", // On focus visible
        "dark:focus:ring-slate-700 dark:focus:ring-opacity-50", // Dark mode
        "[&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90", // On hover and not disabled
        "[&:not(button)]:text-center", // Not a button element
        "disabled:opacity-70 disabled:cursor-not-allowed", // Disabled
        "min-w-0 sm:min-w-[40px] shadow-none font-normal flex items-center justify-center border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3"
    ];
});

const selectClasses = computed(() => {
    return [
        "disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50",
        "[&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50",
        "transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8",
        "focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40",
        "dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50",
    ];
});
</script>

<template>
    <div class="w-full sm:w-auto sm:mr-auto">
        <nav>
            <ul class="flex w-full mr-0 sm:w-auto sm:mr-auto">
                <li class="flex-1 sm:flex-initial">
                    <a :href="prevPageUrl"
                          :class="linkGeneralStyles"
                          class="flex items-center justify-center"
                          style="height: 100%"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                </li>

                <li v-for="link in links" class="flex-1 sm:flex-initial">
                    <a v-if="link.label !== '...'"
                          :href="link.url"
                       :class="[link.active ? 'bg-white' : '', ...linkGeneralStyles]"

                    >
                        {{ link.label }}
                    </a>
                    <button v-else
                            :class="linkGeneralStyles"
                    >
                        {{ link.label }}
                    </button>
                </li>

                <li class="flex-1 sm:flex-initial">
                    <a :href="nextPageUrl"
                          :class="linkGeneralStyles"
                          class="flex items-center justify-center"
                          style="height: 100%"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
    <div>
        <select :class="selectClasses" v-model="parameters.perPage" class="w-20 mt-3 !box sm:mt-0">
            <option v-for="option in options" :value="option">{{ option }}</option>
        </select>
    </div>
</template>

