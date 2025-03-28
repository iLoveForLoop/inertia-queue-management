<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch, computed } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    queues: Array,
    stats: Object,
    filters: Object,
});

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || 'all');

const nextQueueNumber = computed(() => {
    const pendingQueues = props.queues.filter(q => q.status === 'pending');
    return pendingQueues.length ? Math.min(...pendingQueues.map(q => q.queue_number)) : null;
});

const isNextQueue = (queue) => {
    return queue.status === 'pending' && queue.queue_number === nextQueueNumber.value;
};


const completeQueue = async (queueId) => {
    await router.patch(route('admin.queue.complete', queueId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Refresh the page to get updated queue order
            router.reload({ only: ['queues', 'stats'] });
        }
    });
};

const cancelQueue = async (queueId) => {
    await router.patch(route('admin.queue.cancel', queueId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            // Refresh the page to get updated queue order
            router.reload({ only: ['queues', 'stats'] });
        }
    });
};

watch(search, debounce((value) => {
    router.get(route('dashboard'), { search: value, status: statusFilter.value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

watch(statusFilter, (value) => {
    router.get(route('dashboard'), { status: value, search: search.value }, {
        preserveState: true,
        replace: true,
    });
});



</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Queue Management</h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500">
                        <h3 class="text-gray-500 text-sm font-medium">Pending Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.pending }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-gray-500 text-sm font-medium">Completed Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.completed }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <h3 class="text-gray-500 text-sm font-medium">Canceled Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.canceled }}</p>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <h3 class="text-gray-500 text-sm font-medium">Total Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.total }}</p>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="w-full md:w-1/2">
                            <input type="text" v-model="search" placeholder="Search queue number or user..."
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="w-full md:w-1/4">
                            <select v-model="statusFilter"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Queues Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Queue #
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Added
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="queue in queues" :key="queue.id" :class="{
                                    'bg-blue-50': isNextQueue(queue)
                                }">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ queue.queue_number }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ queue.user.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{
                                            'bg-yellow-100 text-yellow-800': queue.status === 'pending',
                                            'bg-green-100 text-green-800': queue.status === 'completed',
                                            'bg-red-100 text-red-800': queue.status === 'canceled'
                                        }" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                            {{ queue.status }}
                                            <span
                                                v-if="queue.status === 'pending' && queue.queue_number === nextQueueNumber"
                                                class="ml-1">
                                                (Next)
                                            </span>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button v-if="queue.status === 'pending'" @click="completeQueue(queue.id)"
                                            class="text-green-600 hover:text-green-900 mr-4">
                                            Complete
                                        </button>
                                        <button v-if="queue.status === 'pending'" @click="cancelQueue(queue.id)"
                                            class="text-red-600 hover:text-red-900">
                                            Cancel
                                        </button>
                                        <span v-else class="text-gray-400">No actions available</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(queue.created_at).toLocaleTimeString() }}
                                    </td>
                                </tr>
                                <tr v-if="queues.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No queues found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
