<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head, Link } from '@inertiajs/vue3';

// Recebe os bugs vindos do Controller
defineProps(['bugs']);

// Configura o formulário
const form = useForm({
    title: '',
    platform: '',
    error_message: '',
    solution: '',
});
</script>

<template>
    <Head title="Cemitério de Bugs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">🪦 Cemitério de Bugs</h2>
        </template>

        <div class="max-w-4xl mx-auto p-4 sm:p-6 lg:p-8">

            <form @submit.prevent="form.post(route('bugs.store'), { onSuccess: () => form.reset() })" class="bg-white p-6 rounded-lg shadow mb-8">
                <h3 class="text-lg font-bold mb-4">Registrar novo bug resolvido</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <input v-model="form.title" type="text" placeholder="Título do Bug (ex: Erro 500 no Nginx)"
                               class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                        <InputError :message="form.errors.title" class="mt-2" />
                    </div>

                    <div>
                        <input v-model="form.platform" type="text" placeholder="Plataforma (ex: Docker, Laravel)"
                               class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                    </div>
                </div>

                <textarea v-model="form.error_message" placeholder="Cole o log de erro aqui (opcional)"
                          class="mt-4 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm h-20"></textarea>

                <textarea v-model="form.solution" placeholder="A solução mágica (Como você resolveu?)"
                          class="mt-4 block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm h-24 border-green-300 bg-green-50"></textarea>
                <InputError :message="form.errors.solution" class="mt-2" />

                <PrimaryButton class="mt-4 bg-green-600 hover:bg-green-700">Salvar Solução</PrimaryButton>
            </form>

            <div class="space-y-4">
                <div v-for="bug in bugs" :key="bug.id" class="p-6 bg-white border-l-4 border-indigo-500 rounded-lg shadow hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="px-2 py-1 text-xs font-bold text-white bg-gray-800 rounded mr-2">{{ bug.platform || 'Geral' }}</span>
                            <h4 class="text-lg font-bold text-gray-900 inline">{{ bug.title }}</h4>
                        </div>
                        <small class="text-gray-500">{{ new Date(bug.created_at).toLocaleDateString() }}</small>

                        <Link
                            :href="route('bugs.destroy', bug.id)"
                            method="delete"
                            as="button"
                            class="text-red-400 hover:text-red-600 font-bold ml-2 text-sm"
                        >
                            🗑️
                        </Link>
                    </div>

                    <div v-if="bug.error_message" class="mt-3 bg-gray-100 p-2 rounded text-xs font-mono text-red-600 overflow-x-auto">
                        {{ bug.error_message }}
                    </div>

                    <p class="mt-4 text-gray-800 font-medium">✅ Solução:</p>
                    <p class="text-gray-600 whitespace-pre-wrap">{{ bug.solution }}</p>
                </div>

                <p v-if="bugs.length === 0" class="text-center text-gray-500 mt-10">Nenhum bug enterrado aqui... ainda. 🧟‍♂️</p>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
