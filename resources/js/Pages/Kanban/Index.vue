<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import axios from 'axios';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import draggable from 'vuedraggable';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3'

// Controle do Modal
const showCreateModal = ref(false);

const form = useForm({
    title: '',
    description: ''
});

const submitTask = () => {
    form.post(route('tasks.store'), {
        onSuccess: () => {
            form.reset();
            showCreateModal.value = false;
        }
    });
};

const props = defineProps(['tasks']);

// Refs das colunas (Inicializamos vazios ou com o valor inicial)
const pendingTasks = ref([]);
const inProgressTasks = ref([]);
const completedTasks = ref([]);

// Função que distribui as tarefas nas colunas certas
const refreshColumns = () => {
    pendingTasks.value = props.tasks.filter(t => t.status === 'pending');
    inProgressTasks.value = props.tasks.filter(t => t.status === 'in_progress');
    completedTasks.value = props.tasks.filter(t => t.status === 'completed');
};

// O VIGIA: Sempre que props.tasks mudar, rodamos a função de novo
watch(() => props.tasks, () => {
    refreshColumns();
}, { immediate: true }); // 'immediate' garante que rode também ao abrir a página

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
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Quadro Kanban 📋</h2>
                <PrimaryButton @click="showCreateModal = true">
                    + Nova Tarefa
                </PrimaryButton>
            </div>
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

        <Modal :show="showCreateModal" @close="showCreateModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">Nova Tarefa 📌</h2>

                <div class="mt-6">
                    <InputLabel for="title" value="Título" />
                    <TextInput
                        id="title"
                        v-model="form.title"
                        type="text"
                        class="mt-1 block w-full"
                        placeholder="Ex: Comprar café"
                        autofocus
                        @keyup.enter="submitTask"
                    />
                    <p v-if="form.errors.title" class="text-sm text-red-600 mt-2">{{ form.errors.title }}</p>
                </div>

                <div class="mt-4">
                    <InputLabel for="description" value="Descrição (Opcional)" />
                    <textarea
                        v-model="form.description"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        rows="3"
                        placeholder="Detalhes da tarefa..."
                    ></textarea>
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showCreateModal = false"> Cancelar </SecondaryButton>
                    <PrimaryButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing" @click="submitTask">
                        Salvar Tarefa
                    </PrimaryButton>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>
