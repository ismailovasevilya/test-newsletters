<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {useForm, Head, router} from '@inertiajs/vue3';
import Pagination from "@/CustomComponents/Pagination.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import {ref} from "vue";

defineProps({'clients': Object });

const form = useForm({
    message: '',
});

const enableModalWindow = ref(false)

const file = ref()

function enableModal() {
    enableModalWindow.value = true
}

function uploadFile(event) {
    file.value = event.target.files[0]

}

function importClients() {
    enableModalWindow.value = false
    router.post(`/clients/import`, {
        file: file.value
    })
}
</script>

<template>
    <Head title="Clients" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Клиенты</h2>
            <a :href="route('clients.create')"
               class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                Добавить клиента
            </a>
            <PrimaryButton class="ml-3" @click="enableModal">Импортировать</PrimaryButton>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ФИО</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Телефон</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">День рождения</th>
                            <th class="px-6 py-3 text-rigth text-xs font-medium text-gray-500 uppercase tracking-wider">Действия</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        <tr v-for="client in clients.data">
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.fio }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.phone_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.email }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ client.birth_date }}</td>
                            <td class="px-6 py-4 whitespace-nowrap flex justify-center">
                                <a class="w-10 p-2 hover:bg-gray-100" :href="route('clients.edit', client.id)" >
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                    </svg>
                                </a>
                                <a class="w-10 p-2 hover:bg-gray-100" :href="route('clients.destroy', client.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <tr v-if="clients.data.length === 0">
                            <td colspan="4" class="px-6 py-4 whitespace-nowrap">На данный момент клиентов нет</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="enableModalWindow">
                    <Modal :show="true">
                        <div class="flex file:border-1">
                            <input
                                @input="uploadFile"
                                type="file"
                                class="block w-full text-sm text-slate-500
                                    file:mr-4 file:py-2 file:px-4 file:rounded-md
                                    file:border-0 file:text-sm file:font-semibold
                                    file:bg-gray-200 file:text-black-700
                                    hover:file:bg-gray-300"
                            />
                            <PrimaryButton @click="importClients">Импорт</PrimaryButton>
                        </div>
                    </Modal>
                </div>
                <div
                    class="flex flex-wrap items-center col-span-12 intro-y sm:flex-row sm:flex-nowrap"
                >
                    <Pagination :data="clients" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
