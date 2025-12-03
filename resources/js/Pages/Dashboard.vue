<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3'; // <--- Importei o Link aqui!
</script>

<template>
    <Head title="Painel Dev" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Painel Dev 🛠️</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500 cursor-pointer hover:shadow-lg transition">
                        <h3 class="text-lg font-bold text-gray-900">📋 Quadro Kanban</h3>
                        <p class="text-gray-600 mt-2">Gerenciar tarefas, features e ideias.</p>
                        <div class="mt-4 text-sm text-indigo-600 font-semibold">Em breve...</div>
                    </div>

                    <Link :href="route('bugs.index')" class="block bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500 cursor-pointer hover:shadow-lg transition hover:bg-red-50">
                        <h3 class="text-lg font-bold text-gray-900">🪦 Cemitério de Bugs</h3>
                        <p class="text-gray-600 mt-2">Documentar erros cabeludos e soluções (Docker, Git, etc).</p>
                        <div class="mt-4 text-sm text-red-600 font-semibold">Acessar túmulos →</div>
                    </Link>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
