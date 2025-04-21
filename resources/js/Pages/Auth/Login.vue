<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import PopUp from '@/Components/PopUp.vue';
import { watch, ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};

const showError = ref(false)

watch(
    () => form.errors,
    (newErrors) => {
        // console.log('New error: ', form.errors.email)
        showError.value = true
        setTimeout(() => {
            showError.value = false
        }, 5000)
    },
    { deep: true }
);
</script>

<template>
    <transition name="notification">
        <PopUp v-if="showError" :message="form.errors.email" title="Error" :duration="3000" />
    </transition>
    <GuestLayout>

        <Head title="Log in" />

        <div class="w-full flex justify-center items-center mb-6">
            <h1 class="text-2xl font-bold text-teal-600">Welcome Back</h1>
        </div>

        <!-- Notification popup -->



        <form @submit.prevent="submit">
            <!-- Email Field -->
            <div class="mb-4">
                <InputLabel for="email" value="Email" class="text-gray-700" />
                <TextInput id="email" type="email"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.email" required autofocus autocomplete="username" />
            </div>

            <!-- Password Field -->
            <div class="mb-4">
                <InputLabel for="password" value="Password" class="text-gray-700" />
                <TextInput id="password" type="password"
                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                    v-model="form.password" required autocomplete="current-password" />
            </div>

            <!-- Remember Me and Forgot Password (Side by Side) -->
            <div class="mb-6 flex items-center justify-between">
                <label class="flex items-center">
                    <Checkbox name="remember" v-model:checked="form.remember"
                        class="rounded border-gray-300 text-teal-600 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50" />
                    <span class="ms-2 text-sm text-gray-600">Remember me</span>
                </label>

                <Link v-if="canResetPassword" :href="route('password.request')"
                    class="text-sm text-teal-600 hover:text-teal-800 transition-colors duration-200">
                Forgot password?
                </Link>
            </div>

            <!-- Submit Button -->
            <div class="flex flex-col items-center">
                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium rounded-lg shadow-md hover:from-teal-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-all duration-200"
                    :class="{ 'opacity-75 cursor-not-allowed': form.processing }" :disabled="form.processing">
                    <span v-if="form.processing" class="inline-block mr-2">
                        <!-- Loading spinner icon could go here -->
                    </span>
                    Sign In
                </button>

                <div class="mt-6 text-center text-sm text-gray-600">
                    Don't have an account?
                    <Link href="/register" class="font-medium text-teal-600 hover:text-teal-500">Sign up</Link>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>


<style></style>
