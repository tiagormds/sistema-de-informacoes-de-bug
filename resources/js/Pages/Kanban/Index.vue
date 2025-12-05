<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';

const props = defineProps(['tasks']);

// --- 1. Separar as tarefas nas 3 colunas (Lógica Local) ---
// Criamos refs para cada coluna para o Drag&Drop poder manipular
const pendingTasks = ref(props.tasks.filter(t => t.status === 'pending'));
const inProgressTasks = ref(props.tasks.filter(t => t.status === 'in_progress'));
const completedTasks = ref(props.tasks.filter(t => t.status === 'completed'));

// --- 2. Função "Fake" para testar o visual (Log no console) ---
const onDragChange = (event, newStatus) => {

    // Cenário 1: "added" (Veio de outra coluna)
    // Cenário 2: "moved" (Mudou de ordem na mesma coluna)
    let item = null;
    let newIndex = null;

    if (event.added) {
        item = event.added.element;
        newIndex = event.added.newIndex;
    } else if (event.moved) {
        item = event.moved.element;
        newIndex = event.moved.newIndex;
    }

    // Se houve mudança real, chama a API
    if (item) {
        axios.put(route('tasks.move', item.id), {
            status: newStatus,
            newPosition: newIndex
        }).catch(error => {
            console.error("Erro ao salvar movimento:", error);
            alert("Erro ao sincronizar! Dê F5.");
        });
    }
};
</script>

<template>
    <Head title="Quadro Kanban" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quadro Kanban 📋</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                    <div class="bg-gray-100 p-4 rounded-lg shadow-inner min-h-[400px]">
                        <h3 class="font-bold text-gray-700 mb-4 flex items-center">
                            🔵 A Fazer
                            <span class="ml-2 bg-gray-300 text-xs px-2 py-1 rounded-full">{{ pendingTasks.length }}</span>
                        </h3>

                        <draggable
                            v-model="pendingTasks"
                            group="tasks"
                            item-key="id"
                            @change="(e) => onDragChange(e, 'pending')"
                            class="space-y-3 min-h-[200px]"
                        >
                            <template #item="{ element }">
                                <div class="bg-white p-4 rounded shadow cursor-move hover:shadow-lg transition border-l-4 border-gray-400">
                                    {{ element.title }}
                                </div>
                            </template>
                        </draggable>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg shadow-inner min-h-[400px]">
                        <h3 class="font-bold text-blue-700 mb-4 flex items-center">
                            🟠 Fazendo
                            <span class="ml-2 bg-blue-200 text-xs px-2 py-1 rounded-full">{{ inProgressTasks.length }}</span>
                        </h3>

                        <draggable
                            v-model="inProgressTasks"
                            group="tasks"
                            item-key="id"
                            @change="(e) => onDragChange(e, 'in_progress')"
                            class="space-y-3 min-h-[200px]"
                        >
                            <template #item="{ element }">
                                <div class="bg-white p-4 rounded shadow cursor-move hover:shadow-lg transition border-l-4 border-blue-400">
                                    {{ element.title }}
                                </div>
                            </template>
                        </draggable>
                    </div>

                    <div class="bg-green-50 p-4 rounded-lg shadow-inner min-h-[400px]">
                        <h3 class="font-bold text-green-700 mb-4 flex items-center">
                            🟢 Concluído
                            <span class="ml-2 bg-green-200 text-xs px-2 py-1 rounded-full">{{ completedTasks.length }}</span>
                        </h3>

                        <draggable
                            v-model="completedTasks"
                            group="tasks"
                            item-key="id"
                            @change="(e) => onDragChange(e, 'completed')"
                            class="space-y-3 min-h-[200px]"
                        >
                            <template #item="{ element }">
                                <div class="bg-white p-4 rounded shadow cursor-move hover:shadow-lg transition border-l-4 border-green-400 opacity-75">
                                    <span class="line-through text-gray-500">{{ element.title }}</span>
                                </div>
                            </template>
                        </draggable>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
