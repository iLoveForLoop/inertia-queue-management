<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});


</script>

<template>

    <Head title="Welcome to MediQueue" />

    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-white">
        <!-- Navigation -->
        <nav class="flex justify-between items-center p-6 max-w-7xl mx-auto">
            <div class="md:flex md:items-center">
                <div>
                    <h1 class="text-2xl font-bold text-blue-600 animate-fade-in">MediQueue</h1>
                </div>
            </div>
            <div class="flex space-x-4">
                <Link v-if="canLogin" :href="route('login')"
                    class="px-4 py-2 text-blue-600 font-medium hover:text-blue-800 transition animate-fade-in">
                Sign In
                </Link>
                <Link v-if="canRegister" :href="route('register')"
                    class="px-4 py-2 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition animate-fade-in">
                Sign Up
                </Link>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 py-16 sm:py-24 lg:py-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="animate-slide-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        Welcome to <span class="text-blue-600">MediQueue</span>
                    </h1>
                    <p class="mt-6 text-xl text-gray-600">
                        Your go-to solution for efficient and stress-free pharmacy queues.
                    </p>
                    <div class="mt-10">
                        <Link :href="route('register')"
                            class="px-8 py-3 bg-blue-600 text-white font-medium rounded-md text-lg hover:bg-blue-700 transition shadow-lg hover:shadow-xl animate-pulse">
                        Get Started
                        </Link>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute -top-8 -left-8 w-64 h-64 bg-blue-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-8 -right-8 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                    </div>
                    <div class="relative animate-float">
                        <img src="/images/queue.png" alt="Pharmacy illustration" class="w-full h-auto rounded-lg">
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white py-12 mt-16 animate-fade-in">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Saint Luke's BGC Taguig</h3>
                        <p class="mt-2 text-gray-600">Premium healthcare services</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Contact Us</h3>
                        <p class="mt-2 text-gray-600">mediqueue2025@gmail.com</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Operating Hours</h3>
                        <p class="mt-2 text-gray-600">Mon-Sat, 8:00 AM - 5:00 PM</p>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-200 text-center text-gray-500">
                    <p>© 2025 MediQueue. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style>
@keyframes blob {
    0% {
        transform: translate(0px, 0px) scale(1);
    }

    33% {
        transform: translate(30px, -50px) scale(1.1);
    }

    66% {
        transform: translate(-20px, 20px) scale(0.9);
    }

    100% {
        transform: translate(0px, 0px) scale(1);
    }
}

@keyframes fade-in {
    from {
        opacity: 0;
    }

    to {
        opacity: 1;
    }
}

@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0% {
        transform: translateY(0px);
    }

    50% {
        transform: translateY(-10px);
    }

    100% {
        transform: translateY(0px);
    }
}

@keyframes pulse {
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.05);
    }

    100% {
        transform: scale(1);
    }
}

.animate-blob {
    animation: blob 7s infinite;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animate-fade-in {
    animation: fade-in 1s ease-out forwards;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out forwards;
    opacity: 0;
    animation-delay: 0.3s;
}

.animate-float {
    animation: float 4s ease-in-out infinite;
    animation-delay: 0.8s;
}

.animate-pulse {
    animation: pulse 2s infinite;
}
</style>
