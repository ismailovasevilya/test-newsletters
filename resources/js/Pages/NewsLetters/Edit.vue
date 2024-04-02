<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {useForm, Head, usePage} from '@inertiajs/vue3';
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import DatePicker from "@/CustomComponents/DatePicker.vue";
import TextareaInput from "@/CustomComponents/TextareaInput.vue";
import ItemSelect from "@/CustomComponents/ItemSelect.vue";
import { ref } from "vue";

const newsletter = usePage().props.newsletter;
const statuses = usePage().props.statuses;

const form = newsletter != null ?
    useForm({
        title: newsletter.title,
        text: newsletter.text,
        status: newsletter.status,
        immediate_send: newsletter.immediate_send,
        days_before_birthday: newsletter.days_before_birthday,
        time_to_send: newsletter.time_to_send
    }) : useForm({
        title: '',
        text: '',
        status: '',
        immediate_send: false,
        days_before_birthday: '',
        time_to_send: ''
    })

function updateOrCreate() {
    if (newsletter != null) {
        form.put(route('newsletters.update', newsletter.id))
    } else {
        form.post(route('newsletters.store'))
    }
}

function start(id) {
    axios.post('/newsletters/start', {
        params: {
            id: id,
        }
    })
}

function stop(id) {
  axios.post('/newsletters/stop', {
    params: {
      id: id,
    }
  })
}


function continueSend(id) {
  axios.post('/newsletters/continue', {
    params: {
      id: id,
    }
  })
}
</script>

<template>
    <Head title="Newsletter" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Рассылка</h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <form @submit.prevent="updateOrCreate" class="space-y-6">
                        <div>
                            <InputLabel for="title" value="Название" />

                            <TextInput
                                id="title"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.title"
                                required
                                autofocus
                            />

                            <InputError class="mt-2" :message="form.errors.title" />
                        </div>

                        <div>
                            <InputLabel for="text" value="Текст"/>

                            <TextareaInput
                                id="text"
                                type="text"
                                class="mt-1 block w-full"
                                v-model="form.text"
                                required
                                autofocus
                            />
                            <InputError class="mt-2" :message="form.errors.text" />
                        </div>

                        <div>
                            <ItemSelect label="Статус" v-model="form.status" :items="statuses"/>

                            <InputError class="mt-2" :message="form.errors.statuses" />
                        </div>
                        <div>
                            <div class="flex">
                                <InputLabel for="schedule" value="Отправить сразу" />
                                <Checkbox class="ml-3" v-model:checked="form.immediate_send" />
                            </div>
                            <div class="flex mt-3">
                                <InputLabel class="mr-3 my-auto" value="Регулярно за" />
                                <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       type="number"
                                       v-model="form.days_before_birthday" >
                              <InputLabel class="mx-3 my-auto" value="дней в" />
                                <input class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                       v-model="form.time_to_send"
                                       type="time">
                            </div>
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
                        <div class="flex items-center gap-4">
                            <button @click="start(newsletter.id)"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Запустить
                            </button>
                            <button @click="stop(newsletter.id)"
                                class="inline-flex items-center px-4 py-2 bg-yellow-400 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Остановить
                            </button>
                            <button @click="continueSend(newsletter.id)"
                                class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Продолжить
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
