<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {useForm, Head, router} from '@inertiajs/vue3';
import Pagination from "@/CustomComponents/Pagination.vue";
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import {ref} from "vue";

defineProps({'newsletters': Object, 'statuses': Object});

const form = useForm({
  message: '',
});

</script>

<template>
  <Head title="Statistics" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">Статистика</h2>

    </template>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Название</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Статус</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Отправлено</th>
              <th class="px-6 py-3 text-rigth text-xs font-medium text-gray-500 uppercase tracking-wider">Доставлено</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="newsletter in newsletters.data">
              <td class="px-6 py-4 whitespace-nowrap">{{ newsletter.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ newsletter.title }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ statuses[newsletter.status].label }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ newsletter.clients_count }}</td>
              <td class="px-6 py-4 whitespace-nowrap">{{ newsletter.delivered_clients_count }}</td>
            </tr>
            <tr v-if="newsletters.data.length === 0">
              <td colspan="4" class="px-6 py-4 whitespace-nowrap">На данный статистики нет</td>
            </tr>
            </tbody>
          </table>
        </div>
        <div
            class="flex flex-wrap items-center col-span-12 intro-y sm:flex-row sm:flex-nowrap"
        >
          <Pagination :data="newsletters" />
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
