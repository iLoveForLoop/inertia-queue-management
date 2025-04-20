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

    <div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50">
        <!-- Navigation -->
        <nav class="flex justify-between items-center p-6 max-w-7xl mx-auto">
            <div class="flex items-center">
                <div class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="w-8 h-8 text-teal-600 mr-2">
                        <path
                            d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                    </svg>
                    <h1 class="text-2xl font-bold text-teal-600 animate-fade-in">MediQueue</h1>
                </div>
            </div>
            <div class="flex space-x-4">
                <Link v-if="canLogin" :href="route('login')"
                    class="px-4 py-2 text-teal-600 font-medium hover:text-teal-800 transition animate-fade-in">
                Sign In
                </Link>
                <Link v-if="canRegister" :href="route('register')"
                    class="px-4 py-2 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium rounded-md hover:from-teal-600 hover:to-blue-600 transition animate-fade-in shadow-md">
                Sign Up
                </Link>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="max-w-7xl mx-auto px-6 py-12 sm:py-20 lg:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <div class="animate-slide-up">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 leading-tight">
                        Simplify Your <span class="text-teal-600">Pharmacy</span> Experience
                    </h1>
                    <p class="mt-6 text-xl text-gray-600">
                        MediQueue streamlines pharmacy services with our smart queuing system, reducing wait times and
                        improving patient satisfaction.
                    </p>
                    <div class="mt-10 flex gap-4">
                        <Link :href="route('register')"
                            class="px-8 py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium rounded-lg text-lg hover:from-teal-600 hover:to-blue-600 transition shadow-lg hover:shadow-xl animate-pulse">
                        Get Started
                        </Link>
                        <a href="#features"
                            class="px-8 py-3 border border-teal-500 text-teal-600 font-medium rounded-lg text-lg hover:bg-teal-50 transition">
                            Learn More
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute -top-8 -left-8 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
                    </div>
                    <div
                        class="absolute -bottom-8 -right-8 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
                    </div>
                    <div
                        class="absolute top-1/2 left-1/2 w-64 h-64 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
                    </div>
                    <div class="relative animate-float rounded-2xl overflow-hidden">
                        <img src="/images/queue.png" alt="Pharmacy illustration" class="w-full h-auto">
                        <!-- <div class="absolute inset-0 bg-gradient-to-t from-teal-500/20 to-transparent"></div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-6">
                <h2 class="text-3xl font-bold text-gray-900 text-center mb-12">Why Choose MediQueue</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div
                        class="bg-gradient-to-br from-teal-50 to-blue-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Reduced Wait Times</h3>
                        <p class="text-gray-600">Our intelligent queuing system minimizes wait times and manages patient
                            flow efficiently.</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-teal-50 to-blue-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Enhanced Patient Safety</h3>
                        <p class="text-gray-600">Digital tracking and verification ensures medications are dispensed
                            correctly every time.</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-teal-50 to-blue-50 p-6 rounded-xl shadow-md transform hover:scale-105 transition duration-300">
                        <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Convenient Notifications</h3>
                        <p class="text-gray-600">Receive real-time updates on your queue status and queue
                            position.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-16 bg-gradient-to-r from-teal-500 to-blue-500">
            <div class="max-w-4xl mx-auto px-6 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">Ready to experience a better pharmacy service?</h2>
                <p class="text-xl text-white opacity-90 mb-8">Join thousands of satisfied customers who have simplified
                    their pharmacy visits with MediQueue.</p>
                <Link :href="route('register')"
                    class="px-8 py-3 bg-white text-teal-600 font-medium rounded-lg text-lg hover:bg-teal-50 transition shadow-lg hover:shadow-xl inline-block">
                Create Your Account
                </Link>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-white py-12 animate-fade-in">
            <div class="max-w-7xl mx-auto px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="w-6 h-6 text-teal-600 mr-2">
                                <path
                                    d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900">MediQueue</h3>
                        </div>
                        <p class="text-gray-600">Saint Luke's BGC Taguig</p>
                        <p class="text-gray-600">Premium healthcare services</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Us</h3>
                        <div class="flex items-center mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600">mediqueue2025@gmail.com</p>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <p class="text-gray-600">(02) 8-XXX-XXXX</p>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Operating Hours</h3>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-600">Mon-Sat, 8:00 AM - 5:00 PM</p>
                        </div>
                    </div>
                </div>
                <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                    <p class="text-gray-500">© 2025 MediQueue. All rights reserved.</p>
                </div>
            </div>
        </footer>
    </div>
</template>

<style></style>
