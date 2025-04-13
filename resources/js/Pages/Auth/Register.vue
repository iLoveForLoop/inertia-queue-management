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

        <div class="w-full flex justify-center items-center mb-5">
            <h1 class="text-2xl font-bold text-indigo-500 uppercase">register</h1>
        </div>

        <form @submit.prevent="submit">
            <transition name="notification">
                <PopUp v-if="showError" :message="errorMessage" title="Error" :duration="3000" />
            </transition>
            <div>
                <InputLabel for="name" value="Name" />

                <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus
                    autocomplete="name" />

                <!-- <InputError class="mt-2" :message="form.errors.name" /> -->
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required
                    autocomplete="username" />

                <!-- <InputError class="mt-2" :message="form.errors.email" /> -->
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="new-password" />

                <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
            </div>

            <div class="mt-4">
                <InputLabel for="password_confirmation" value="Confirm Password" />

                <TextInput id="password_confirmation" type="password" class="mt-1 block w-full"
                    v-model="form.password_confirmation" required autocomplete="new-password" />

                <!-- <InputError class="mt-2" :message="form.errors.password_confirmation" /> -->
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                Already registered?
                </Link>

                <PrimaryButton class="ms-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
