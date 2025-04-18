<script setup>
import { useForm, usePoll, router } from '@inertiajs/vue3';
import { computed, onMounted, onBeforeUnmount, ref, inject, watch } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePollingStore } from '@/store/polling';
import PopUp from '@/Components/PopUp.vue';





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
})

//NOTIF DATA
const notifMessage = ref('')
const notifType = ref('')
const notifTitle = ref('')
const isNotif = ref(false);

const form = useForm()
const pollingStore = usePollingStore()

const { stop, start } = usePoll(1000, {
    only: ['queuesAhead', 'waitTime', 'currentlyServing', 'isCurrentlyServing', 'user'],
});



watch(() => pollingStore.isPaused, (paused) => {
    console.log('WATCH WORKING: ', paused)
    if (paused) {
        console.log('CHILD PAUSING: ', paused)
        stop()
        setTimeout(() => { }, 3000)
    } else {
        console.log('CHILD PAUSING: ', paused)
        start()
    }
})







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
    if (!props.user.queue || props.user.queue.status !== 'pending') return '5%'; // not in queue
    if (props.isCurrentlyServing) return '100%'; // being served now

    const currentPosition = props.queuesAhead + 1; // e.g., if 2 ahead of you, you're 3rd
    const percentage = (1 / currentPosition) * 100; // closer to 1st = higher %

    return `${Math.max(5, percentage.toFixed(2))}%`; // minimum visible bar width
});

const requestNewQueue = () => {
    form.post(route('user.queue.request'), {
        onSuccess: () => {
            notifMessage.value = "You requested a new queue"
            notifType.value = "info"
            notifTitle.value = ("Request")
            isNotif.value = true
        },
        onFinish: () => {
            setTimeout(() => {
                isNotif.value = false
            }, 3000)
        }
    });
}



const cancelQueue = () => {
    router.patch(route('user.queue.cancel', props.user.queue.id), {}, {
        preserveScroll: true,
        onSuccess: () => {
            notifMessage.value = "You cancelled your queue"
            notifType.value = "error"
            notifTitle.value = ("Cancelled")
            isNotif.value = true
        },
        onFinish: () => {
            setTimeout(() => {
                isNotif.value = false
            }, 3000)
        }
    })
}


</script>

<template>
    <transition name="notification">
        <PopUp v-if="isNotif" class="z-80" :duration="3000" :type="notifType" :message="notifMessage"
            :title="notifTitle" />
    </transition>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-r from-teal-50 to-blue-100 py-10 px-4">
            <div class="max-w-lg mx-auto">
                <!-- Header with User Welcome -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Hello, {{ user.name }}</h1>
                    <p class="text-gray-500 mt-2">Track your queue status below</p>
                </div>

                <!-- Main Queue Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                    <!-- Queue Number Hero Section -->
                    <div v-if="user.queue" class="bg-blue-500 px-6 py-8 text-center">
                        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Your Queue</p>
                        <div class="flex items-center justify-center">
                            <div class="bg-white rounded-full px-8 py-4 shadow-lg inline-block">
                                <h1 class="text-5xl font-bold text-blue-600">{{ user.queue.queue_number }}</h1>
                            </div>
                        </div>
                        <div class="mt-4 flex justify-center">
                            <span class="px-4 py-1 rounded-full text-sm font-semibold inline-flex items-center" :class="{
                                'bg-yellow-100 text-yellow-800': user.queue.status === 'pending',
                                'bg-green-100 text-green-800': user.queue.status === 'completed',
                                'bg-red-100 text-red-800': user.queue.status === 'canceled'
                            }">
                                <span class="h-2 w-2 rounded-full mr-2" :class="{
                                    'bg-yellow-500': user.queue.status === 'pending',
                                    'bg-green-500': user.queue.status === 'completed',
                                    'bg-red-500': user.queue.status === 'canceled'
                                }"></span>
                                {{ user.queue.status.charAt(0).toUpperCase() + user.queue.status.slice(1) }}
                                <span v-if="user.queue && isCurrentlyServing"
                                    class="ml-2 bg-white px-2 py-1 rounded-full text-xs font-bold text-blue-700">
                                    Now Serving
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Empty State Hero Section -->
                    <div v-else class="bg-gray-100 px-6 py-8 text-center">
                        <div class="bg-white rounded-full p-6 shadow-lg inline-flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-700 mt-4">No Active Queue</h2>
                    </div>

                    <!-- Queue Content -->
                    <div v-if="user.queue" class="p-6">
                        <!-- Pending Status Content -->
                        <div v-if="user.queue.status === 'pending'" class="space-y-6">
                            <!-- Wait Time Banner -->
                            <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-500 mr-3"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-blue-600 text-sm">Estimated Wait Time</p>
                                        <p class="text-blue-800 text-xl font-bold">{{ formattedWaitTime }}</p>
                                    </div>
                                </div>
                                <div class="bg-white px-4 py-2 rounded-lg shadow border border-blue-100">
                                    <p class="text-sm text-gray-500">Now Serving</p>
                                    <p class="text-xl font-bold text-blue-600">{{ currentlyServing }}</p>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-500">Your Position: <span
                                            class="font-medium text-gray-700">{{ positionInQueue }}</span></span>
                                    <span class="text-gray-500">Queues Ahead: <span class="font-medium text-gray-700">{{
                                        queuesAhead }}</span></span>
                                </div>
                                <div class="h-4 bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full transition-all duration-500"
                                        :style="{ width: progressWidth }"></div>
                                </div>
                            </div>

                            <!-- Cancel Button -->
                            <div class="pt-2">
                                <button @click="cancelQueue"
                                    class="w-full py-4 border-2 border-red-300 text-red-600 rounded-xl hover:bg-red-50 transition-colors font-medium flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Cancel My Queue
                                </button>
                            </div>
                        </div>

                        <!-- Completed Status Content -->
                        <div v-else-if="user.queue.status === 'completed'" class="text-center py-4 space-y-4">
                            <div class="inline-flex items-center justify-center p-4 bg-green-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-green-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Queue Completed</h3>
                                <p class="text-gray-500 mt-1">Thank you for your patience!</p>
                            </div>
                            <button @click="requestNewQueue"
                                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md transition-all duration-200 w-full flex items-center justify-center">
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
                            </button>
                        </div>

                        <!-- Canceled Status Content -->
                        <div v-else class="text-center py-4 space-y-4">
                            <div class="inline-flex items-center justify-center p-4 bg-red-100 rounded-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-red-500" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold text-gray-800">Queue Canceled</h3>
                                <p class="text-gray-500 mt-1">Your queue request has been canceled</p>
                            </div>
                            <button @click="requestNewQueue"
                                class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md transition-all duration-200 w-full flex items-center justify-center">
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
                            </button>
                        </div>
                    </div>

                    <!-- No Queue Content -->
                    <div v-else class="p-6 text-center space-y-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">No Active Queue</h3>
                            <p class="text-gray-500 mt-1">Create a new queue request to get started</p>
                        </div>
                        <button @click="requestNewQueue"
                            class="mt-4 bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 px-6 rounded-lg shadow-md transition-all duration-200 w-full flex items-center justify-center">
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            <span v-if="form.processing">Processing...</span>
                            <span v-else>Create New Queue</span>
                        </button>
                    </div>
                </div>

                <!-- FAQ Section -->
                <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4">Frequently Asked Questions</h3>
                    <div class="space-y-3">
                        <div>
                            <h4 class="font-medium text-gray-700">How is wait time calculated?</h4>
                            <p class="text-gray-500 text-sm">Wait time is estimated based on the number of customers
                                ahead of you and average service time.</p>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-700">What happens if I miss my turn?</h4>
                            <p class="text-gray-500 text-sm">If you miss your turn, you'll need to request a new queue
                                number.</p>
                        </div>
                    </div>
                </div>

                <!-- Need Help Section -->
                <div class="bg-blue-50 rounded-xl p-4 flex items-center">
                    <div class="bg-blue-100 p-2 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-medium text-gray-800">Need help?</h3>
                        <p class="text-gray-500 text-sm">Contact our support team at support@queue-system.com</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-6 text-center">
            <p class="text-sm text-gray-500">© 2025 Queue Management System | All rights reserved</p>
        </footer>
    </AuthenticatedLayout>
</template>
