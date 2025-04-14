<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import PopUp from '@/Components/PopUp.vue';
import { ref, watch } from 'vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const showError = ref(false)
const errorMessage = ref('')

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};

watch(() => form.errors, (newError) => {

    if (newError.password) errorMessage.value = newError.password
    if (newError.name) errorMessage.value = newError.name
    if (newError.email) errorMessage.value = newError.email
    if (newError.password_confirmation) errorMessage.value = newError.password_confirmation

    showError.value = true
    setTimeout(() => {
        showError.value = false
    }, 3000)
})
</script>

<template>
    <GuestLayout>

        <Head title="Register" />

        <div class="w-full flex justify-center items-center mb-6">
            <h1 class="text-2xl font-bold text-teal-600">Create Account</h1>
        </div>

        <!-- Notification popup -->
        <transition name="notification">
            <div v-if="showError" class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ errorMessage }}</p>
                    </div>
                </div>
            </div>
        </transition>

        <form @submit.prevent="submit">
            <!-- Name Field -->
            <div class="mb-4">
                <InputLabel for="name" value="Name" class="text-gray-700" />
                <TextInput id="name" type="text"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.name" required autofocus autocomplete="name" />
            </div>

            <!-- Email Field -->
            <div class="mb-4">
                <InputLabel for="email" value="Email" class="text-gray-700" />
                <TextInput id="email" type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.email" required autocomplete="username" />
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <InputLabel for="password" value="Password" class="text-gray-700" />
                <TextInput id="password" type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.password" required autocomplete="new-password" />
            </div>

            <!-- Confirm Password Field -->
            <div class="mb-6">
                <InputLabel for="password_confirmation" value="Confirm Password" class="text-gray-700" />
                <TextInput id="password_confirmation" type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <!-- Submit Button and Login Link -->
            <div class="flex flex-col items-center">
                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium rounded-lg shadow-md hover:from-teal-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-all duration-200"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }" :disabled="form.processing">
                    Create Account
                </button>

                <div class="mt-6 text-center text-sm text-gray-600">
                    Already have an account?
                    <Link :href="route('login')" class="font-medium text-teal-600 hover:text-teal-500">
                    Sign in
                    </Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>
