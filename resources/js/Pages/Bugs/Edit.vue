<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';

const props = defineProps(['bug']);

// Preenchemos o form com os dados que vieram do banco
const form = useForm({
    title: props.bug.title,
    platform_id: props.bug.platform_id,
    error_message: props.bug.error_message,
    solution: props.bug.solution,
});
</script>

<template>
    <Head title="Editar Bug" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Solução ✏️</h2>
        </template>

        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.put(route('bugs.update', bug.id))" class="bg-white p-6 rounded-lg shadow">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <input v-model="form.title" type="text" class="block w-full border-gray-300 rounded-md shadow-sm">
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div>
                        <select v-model="form.platform_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="" disabled>Selecione a plataforma</option>
                            <option v-for="plat in platforms" :key="plat.id" :value="plat.id">
                                {{ plat.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <textarea v-model="form.error_message" class="mt-4 block w-full border-gray-300 rounded-md shadow-sm h-20"></textarea>

                <textarea v-model="form.solution" class="mt-4 block w-full border-gray-300 rounded-md shadow-sm h-24 border-green-300 bg-green-50"></textarea>
                <InputError :message="form.errors.solution" class="mt-2" />

                <div class="mt-4 space-x-2">
                    <PrimaryButton class="bg-blue-600 hover:bg-blue-700">Salvar Alterações</PrimaryButton>
                    <a :href="route('bugs.index')" class="text-gray-600 underline text-sm ml-4">Cancelar</a>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
