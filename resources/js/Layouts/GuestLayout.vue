<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-teal-50 to-blue-50 flex flex-col justify-center">
        <!-- Header / Logo Area -->
        <div class="fixed inset-0 flex w-full h-16 justify-center md:justify-start">
            <Link class="pt-6 md:ps-10" href="/">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                    class="w-8 h-8 text-teal-600">
                    <path
                        d="M11.25 4.533A9.707 9.707 0 006 3a9.735 9.735 0 00-3.25.555.75.75 0 00-.5.707v14.25a.75.75 0 001 .707A8.237 8.237 0 016 18.75c1.995 0 3.823.707 5.25 1.886V4.533zM12.75 20.636A8.214 8.214 0 0118 18.75c.966 0 1.89.166 2.75.47a.75.75 0 001-.708V4.262a.75.75 0 00-.5-.707A9.735 9.735 0 0018 3a9.707 9.707 0 00-5.25 1.533v16.103z" />
                </svg>
                <h2 class="font-semibold text-xl text-teal-600 leading-tight ml-2">MediQueue</h2>
            </div>
            </Link>
        </div>

        <!-- Card Container -->
        <div class="relative py-3 sm:max-w-md mx-auto w-full px-4 bg-gradient-to-br from-teal-50 to-blue-50 ">
            <div
                class="absolute -top-8 -left-8 w-64 h-64 bg-teal-100 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute -bottom-8 -right-8 w-64 h-64 bg-blue-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute top-1/2 left-1/2 w-64 h-64 bg-teal-200 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000">
            </div>
            <div
                class="absolute inset-0 bg-gradient-to-r from-teal-400 to-blue-500 shadow-lg transform -rotate-2 rounded-2xl mx-1">

            </div>
            <div class="relative bg-white shadow-lg rounded-2xl px-8 py-10 animate-slide-up-2">

                <slot />
            </div>
        </div>
    </div>
</template>
