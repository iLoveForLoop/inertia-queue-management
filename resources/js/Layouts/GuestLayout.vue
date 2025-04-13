<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>

    <div class="min-h-screen bg-gray-200 flex flex-col justify-center ">
        <div class="fixed inset-0 flex w-full h-1/6 justify-center md:justify-start">
            <Link class="pt-10 md:ps-10" href="/">
            <!-- <ApplicationLogo class="h-auto w-32 fill-current text-blue-600" /> -->
            <h2 class="font-semibold text-xl text-indigo-600 leading-tight">MediQueue</h2>
            </Link>
        </div>
        <div class="relative py-3 sm:max-w-xl mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-600 shadow-lg transform  skew-y-0 -rotate-6 rounded-3xl mx-1">
            </div>

            <div class="relative px-20 py-[2.7em] max-w-lg min-w-lg bg-white shadow-lg rounded-3xl p-5 mx-1">

                <div class="max-w-sm mx-auto">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
