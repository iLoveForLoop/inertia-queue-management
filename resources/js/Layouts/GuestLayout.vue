<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gray-200 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl sm:mx-auto">
            <div
                class="absolute inset-0 bg-gradient-to-r from-blue-300 to-blue-600 shadow-lg transform -skew-y-6 sm:skew-y-0 sm:-rotate-6 sm:rounded-3xl">
            </div>
            <div class="relative px-4 py-10 bg-white shadow-lg sm:rounded-3xl sm:p-20">
                <!-- <div class="flex justify-center mb-8">
                    <Link href="/">
                    <ApplicationLogo class="h-16 w-16 fill-current text-blue-600" />
                    </Link>
                </div> -->
                <div class="max-w-md mx-auto">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
