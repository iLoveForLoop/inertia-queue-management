<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
import { debounce } from 'lodash';
import Pagination from '@/Components/Pagination.vue';
import PopUp from '@/Components/PopUp.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    queues: Object,
    stats: Object,
    filters: Object,
});

const isNotif = ref(false);
const notifType = ref()
const notifMessage = ref()
const notifTitle = ref()
const notifQueueNumber = ref()

// Reactive state
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || 'all');
const isPollingActive = ref(true);
const pollingInterval = ref(null);

// Computed properties
const nextQueueNumber = computed(() => {
    const pendingQueues = props.queues?.data?.filter(q => q.status === 'pending') || [];
    return pendingQueues.length ? Math.min(...pendingQueues.map(q => q.queue_number)) : null;
});

// Methods
const refreshData = () => {
    if (!isPollingActive.value) return;

    router.reload({
        only: ['queues', 'stats'],
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            // Optional: Add any post-refresh logic
        }
    });
};

const startPolling = () => {
    stopPolling(); // Clear any existing interval
    pollingInterval.value = setInterval(refreshData, 1000);
};

const stopPolling = () => {
    if (pollingInterval.value) {
        clearInterval(pollingInterval.value);
        pollingInterval.value = null;
    }
};

const pausePolling = () => {
    isPollingActive.value = false;
    stopPolling();
};

const resumePolling = () => {
    isPollingActive.value = true;
    startPolling();
};

// Watchers
watch(search, debounce((value) => {
    pausePolling();
    router.get(route('dashboard'), { search: value, status: statusFilter.value }, {
        preserveState: true,
        replace: true,
        onFinish: resumePolling
    });
}, 300));

watch(statusFilter, (value) => {
    pausePolling();
    router.get(route('dashboard'), { status: value, search: search.value }, {
        preserveState: true,
        replace: true,
        onFinish: resumePolling
    });
});

const isPaginating = ref(false);

const onPaginating = () => {
    isPaginating.value = true;
    pausePolling();

    // Resume after navigation completes
    const unwatch = watch(() => props.queues, () => {
        isPaginating.value = false;
        resumePolling();
        unwatch(); // Clean up the watcher
    }, { deep: true });
};

// Component lifecycle
onMounted(() => {
    startPolling();
});

onUnmounted(() => {
    stopPolling();
});

// Queue actions
const completeQueue = async (queueId, qnum) => {
    notifQueueNumber.value = qnum
    pausePolling();
    await router.patch(route('admin.queue.complete', queueId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            refreshData();
            notifMessage.value = "Queue Completed Successfully"
            notifType.value = "success"
            notifTitle.value = ("Success - Queue #" + (notifQueueNumber.value))
            isNotif.value = true
        },
        onFinish: () => {
            setTimeout(() => {
                isNotif.value = false
                resumePolling()
            }, 3000)


        }
    });
};

const cancelQueue = async (queueId, qnum) => {
    notifQueueNumber.value = qnum
    pausePolling();
    await router.patch(route('admin.queue.cancel', queueId), {}, {
        preserveScroll: true,
        onSuccess: () => {
            refreshData();
            notifMessage.value = "Queue Cancelled"
            notifType.value = "error"
            notifTitle.value = ("Cancelled - Queue #" + (notifQueueNumber.value))
            isNotif.value = true
        },
        onFinish: () => {
            setTimeout(() => {
                isNotif.value = false
                resumePolling()
            }, 3000)


        }
    });
};
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2 class="font-semibold text-xl text-indigo-600 leading-tight">MediQueue</h2>
        </template> -->

        <!-- {{ console.log('Notif: ', notifType, notifMessage, 'Boolean: ', isNotif) }} -->
        <transition name="notification">
            <PopUp v-if="isNotif" class="z-80" :duration="3000" :type="notifType" :message="notifMessage"
                :title="notifTitle" />
        </transition>


        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div
                        class=" overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-yellow-500 bg-yellow-500 bg-opacity-20">
                        <h3 class="text-gray-500 text-sm font-medium">Pending Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.pending }}</p>
                    </div>
                    <div
                        class="bg-green-500 bg-opacity-20 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-gray-500 text-sm font-medium">Completed Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.completed }}</p>
                    </div>
                    <div
                        class="bg-red-500 bg-opacity-20 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <h3 class="text-gray-500 text-sm font-medium">Canceled Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.canceled }}</p>
                    </div>
                    <div
                        class="bg-purple-500 bg-opacity-20 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                        <h3 class="text-gray-500 text-sm font-medium">Total Queues</h3>
                        <p class="text-2xl font-bold">{{ stats.total }}</p>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="bg-indigo-2 00 overflow-hidden shadow-sm sm:rounded-lg mb-6 p-4">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div class="w-full md:w-1/2">
                            <input @focus="pausePolling" @blur="resumePolling" type="text" v-model="search"
                                placeholder="Search queue number or user..."
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="w-full md:w-1/4">
                            <select @focus="pausePolling" @blur="resumePolling" v-model="statusFilter"
                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="all">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="completed">Completed</option>
                                <option value="canceled">Canceled</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Currently Serving Card -->
                <div v-if="nextQueueNumber" class="mb-6">
                    <div class="bg-blue-500 bg-opacity-20 border-l-4 border-blue-500 p-4 rounded-lg shadow">
                        <h3 class="text-lg font-semibold text-blue-800 mb-2">Currently Serving</h3>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-sm text-gray-500">Queue Number</p>
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ nextQueueNumber }}
                                </p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-sm text-gray-500">Customer</p>
                                <p class="text-lg font-medium">
                                    {{queues?.data.find(q => q.queue_number === nextQueueNumber)?.user.name || 'N/A'}}
                                </p>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm">
                                <p class="text-sm text-gray-500">Status</p>
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 text-sm font-medium rounded-full">
                                    Pending (Current)
                                </span>
                            </div>
                            <div class="bg-white p-4 rounded-lg shadow-sm flex items-center">
                                <button
                                    @click="completeQueue(queues?.data.find(q => q.queue_number === nextQueueNumber)?.id, queues?.data.find(q => q.queue_number === nextQueueNumber)?.queue_number)"
                                    class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded mr-2">
                                    Complete
                                </button>
                                <button
                                    @click="cancelQueue(queues?.data.find(q => q.queue_number === nextQueueNumber)?.id, queues?.data.find(q => q.queue_number === nextQueueNumber)?.queue_number)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Queues Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Queue #</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        User</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Added</th>
                                    <!-- <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions</th> -->
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="queue in queues?.data.filter(q => q.queue_number !== nextQueueNumber)"
                                    :key="queue.id" class="hover:bg-gray-50">
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
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ new Date(queue.created_at).toLocaleTimeString() }}
                                    </td>
                                    <!-- <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button v-if="queue.status === 'pending'" @click="completeQueue(queue.id)"
                                            class="text-green-600 hover:text-green-900 mr-4">
                                            Complete
                                        </button>
                                        <button v-if="queue.status === 'pending'" @click="cancelQueue(queue.id)"
                                            class="text-red-600 hover:text-red-900">
                                            Cancel
                                        </button>
                                        <span v-else class="text-gray-400">No actions</span>
                                    </td> -->
                                </tr>
                                <tr v-if="queues?.data.filter(q => q.queue_number !== nextQueueNumber).length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No other queues found.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="px-6 py-4 border-t border-gray-200">
                            <Pagination :links="queues.links" @paginating="onPaginating" :meta="queues" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
