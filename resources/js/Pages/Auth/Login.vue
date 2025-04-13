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
    <GuestLayout>

        <Head title="Log in" />

        <!-- <ApplicationLogo class="w-1/2 h-auto" /> -->

        <div class="w-full flex justify-center items-center mb-5">
            <h1 class="text-2xl font-bold text-indigo-500 uppercase">Log in</h1>
        </div>

        <form @submit.prevent="submit">
            <transition name="notification">
                <PopUp v-if="showError" :message="form.errors.email" title="Error" />
            </transition>

            <div>
                <InputLabel for="email" value="Email" />

                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autofocus
                    autocomplete="username" />

                <!-- <InputError class="mt-2" :message="form.errors.email" /> -->

            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" />

                <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required
                    autocomplete="current-password" />

                <!-- <InputError class="mt-2" :message="form.errors.password" /> -->
            </div>


            <!--  -->
            <div class="flex flex-col">
                <div class="mt-4">
                    <label class="flex items-center">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span class="ms-2 text-sm text-gray-600">Remember me</span>
                    </label>
                </div>

                <div class="mt-4 flex flex-col items-center justify-center gap-4">


                    <PrimaryButton class="w-full " :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing">
                        Log in
                    </PrimaryButton>
                    <Link v-if="canResetPassword" :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Forgot your password?
                    </Link>
                </div>
            </div>


        </form>
    </GuestLayout>
</template>


<style></style>
