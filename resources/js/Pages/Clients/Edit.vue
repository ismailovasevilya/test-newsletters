<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import DatePicker from "@/CustomComponents/DatePicker.vue";

const client = usePage().props.client;

const form = client != null ?
    useForm({
        fio: client.fio,
        email: client.email,
        phone_number: client.phone_number,
        birth_date: client.birth_date
    }) : useForm({
        fio: '',
        email: '',
        phone_number: '',
        birth_date: ''
    })

function updateOrCreate() {
    if (client != null) {
        form.put(route('clients.update', client.id))
    } else {
        form.post(route('clients.store'))
    }
}
</script>

<template>
    <Head title="Client" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Клиент</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="updateOrCreate" class="space-y-6">
                        <div>
                            <InputLabel for="name_ru" value="ФИО" />

                            <TextInput
                                id="fio"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.fio"
                                required
                                autofocus
                            />

                            <InputError class="mt-2" :message="form.errors.fio" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email" />

                            <TextInput
                                id="name_uz"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="phone_number" value="Номер телефона" />

                            <TextInput
                                id="name_uz"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.phone_number"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.phone_number" />
                        </div>

                        <div>
                            <InputLabel for="birth_date" value="Дата рождения" />
                            <DatePicker  v-model="form.birth_date" class="mt-3"/>
                            <InputError class="mt-2" :message="form.errors.birth_date" />
                        </div>

                        <div class="flex items-center gap-4">
                            <PrimaryButton :disabled="form.processing">Сохранить</PrimaryButton>

                            <Transition
                                enter-active-class="transition ease-in-out"
                                enter-from-class="opacity-0"
                                leave-active-class="transition ease-in-out"
                                leave-to-class="opacity-0"
                            >
                                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Сохранено.</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
