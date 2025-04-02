<script setup>
import { useForm, usePoll } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    user: {
        type: Object,
        required: true,
    },
    queuesAhead: {
        type: Number,
        default: 0
    },
    waitTime: {
        type: Number,
        default: 0
    },
    currentlyServing: {
        type: Number,
        required: true
    },
    isCurrentlyServing: {
        type: Boolean,
        default: false
    }
});

const form = useForm();

const logout = () => {
    form.post(route('logout'));
};

const formattedWaitTime = computed(() => {
    if (props.isCurrentlyServing) return 'Now serving!';
    if (props.waitTime <= 0) return 'Ready now';

    const hours = Math.floor(props.waitTime / 60);
    const minutes = props.waitTime % 60;

    let result = '';
    if (hours > 0) result += `${hours} hr${hours > 1 ? 's' : ''} `;
    if (minutes > 0) result += `${minutes} min${minutes > 1 ? 's' : ''}`;

    return result || 'Less than 1 minute';
});

const positionInQueue = computed(() => {
    if (!props.user.queue || props.user.queue.status !== 'pending') return null;
    if (props.isCurrentlyServing) return 'Now serving';
    return props.queuesAhead + 1;
});

const progressWidth = computed(() => {
    if (!props.user.queue || props.user.queue.status !== 'pending') return '5%';
    if (props.isCurrentlyServing) return '100%';

    const totalQueues = props.user.queue.queue_number - props.currentlyServing;
    if (totalQueues <= 0) return '5%';

    const percentage = 100 - (props.queuesAhead / totalQueues) * 100;
    return `${Math.max(5, percentage)}%`;
});

const requestNewQueue = () => {
    form.post(route('user.queue.request'));
}

usePoll(1000, {

})
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-gray-50 p-4">
        <!-- Logout Button -->
        <button @click="logout" class="fixed right-4 top-4 group">
            <span class="flex items-center gap-1 text-red-500 hover:text-red-600 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                <span class="font-medium hidden sm:inline">Logout</span>
            </span>
        </button>

        <div class="max-w-md w-full space-y-6">
            <!-- Welcome Header -->
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800">Welcome, {{ user.name }}</h1>
                <p class="text-gray-500 mt-2">Your queue status</p>
            </div>

            <!-- Queue Status Card -->
            <div v-if="user.queue" class="bg-white rounded-xl shadow-lg overflow-hidden transition-all hover:shadow-xl">
                <!-- Status Bar -->
                <div class="h-2" :class="{
                    'bg-yellow-400': user.queue.status === 'pending',
                    'bg-green-500': user.queue.status === 'completed',
                    'bg-red-500': user.queue.status === 'canceled',
                }"></div>

                <div v-if="user.queue && user.queue.status !== 'pending'" class="text-center mt-6">
                    <button @click="requestNewQueue"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50"
                        :disabled="form.processing">
                        <div class="flex items-center justify-center space-x-2">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Request New Queue</span>
                        </div>
                    </button>
                </div>

                <div class="p-6">
                    <!-- Queue Number -->
                    <div class="text-center mb-6">
                        <p class="text-gray-500 text-sm uppercase tracking-wider">Your Queue Number</p>
                        <p class="text-5xl font-bold text-blue-600 mt-1">{{ user.queue.queue_number }}</p>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex justify-center mb-6">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold" :class="{
                            'bg-yellow-100 text-yellow-800': user.queue.status === 'pending',
                            'bg-green-100 text-green-800': user.queue.status === 'completed',
                            'bg-red-100 text-red-800': user.queue.status === 'canceled',
                        }">
                            {{ user.queue.status.charAt(0).toUpperCase() + user.queue.status.slice(1) }}
                            <span v-if="isCurrentlyServing" class="ml-1">(Now Serving)</span>
                        </span>
                    </div>



                    <!-- Queue Progress (only shown for pending) -->
                    <div v-if="user.queue.status === 'pending'" class="space-y-4">
                        <div class="bg-gray-100 rounded-full h-4 overflow-hidden">
                            <div class="bg-blue-500 h-full transition-all duration-500"
                                :style="{ width: progressWidth }"></div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 text-center">
                            <div>
                                <p class="text-gray-500 text-sm">Position</p>
                                <p class="font-bold text-lg">{{ positionInQueue }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Serving Now</p>
                                <p class="font-bold text-lg">{{ currentlyServing }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-sm">Queues Ahead</p>
                                <p class="font-bold text-lg">{{ isCurrentlyServing ? 0 : queuesAhead }}</p>
                            </div>
                        </div>

                        <div class="text-center pt-4">
                            <p class="text-gray-500">Estimated Wait Time</p>
                            <p class="text-2xl font-bold text-blue-600">{{ formattedWaitTime }}</p>
                        </div>
                    </div>

                    <!-- Completed/Canceled Message -->
                    <div v-else class="text-center py-4">
                        <p v-if="user.queue.status === 'completed'" class="text-green-600 font-medium">
                            Your queue has been completed. Thank you!
                        </p>
                        <p v-else class="text-red-600 font-medium">
                            Your queue has been canceled.
                        </p>
                    </div>
                </div>
            </div>

            <!-- No Queue Message -->
            <div v-else class="bg-white rounded-xl shadow-lg p-6 text-center">
                <p class="text-gray-600">You don't have an active queue.</p>
            </div>
        </div>
    </div>
</template>
