<template>
    <transition name="notification">
        <PopUp v-if="isError" :message="message" :title="title" :type="type" :duration="3000" />
    </transition>
    <div class="max-w-md w-full mx-auto bg-white p-8 rounded-xl shadow-md animate-slide-up-2">
        <div class="w-full flex justify-center mb-6">
            <!-- Optional brand icon -->
            <div
                class="h-16 w-16 bg-gradient-to-br from-teal-400 to-blue-500 rounded-full shadow-lg flex items-center justify-center mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" viewBox="0 0 20 20"
                    fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
            </div>
        </div>

        <h1 class="text-2xl font-bold text-center text-teal-600 mb-2">Verify Your Email First</h1>

        <div class="text-center mb-6">
            <p class="text-gray-600 mb-1">We've sent a verification code to</p>
            <p class="font-medium text-teal-600">{{ email }}</p>
        </div>

        <div class="mb-6">
            <label for="verification-code" class="block text-sm font-medium text-gray-700 mb-1">Enter verification
                code</label>
            <input type="text" id="verification-code" v-model="verificationKey"
                class="w-full px-4 py-3 text-center text-lg tracking-widest font-mono rounded-lg border-gray-300 shadow-sm focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50"
                maxlength="6" placeholder="• • • • • •" />
        </div>

        <button @click="checkCode"
            class="w-full py-3 bg-gradient-to-r from-teal-500 to-blue-500 text-white font-medium rounded-lg shadow-md hover:from-teal-600 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-opacity-50 transition-all duration-200">
            Verify Email
        </button>

        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Didn't receive the code?
                <button @click="resendCode" class="font-medium text-teal-600 hover:text-teal-500 ml-1">Resend
                    Code</button>
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import PopUp from './PopUp.vue';

const props = defineProps({
    email: {
        type: String
    },
    code: {
        type: String
    }
})

const message = ref('')
const title = ref('')
const type = ref('')

const emit = defineEmits(['submit', 'resend'])
const isError = ref(false)

const verificationKey = ref('')

const checkCode = () => {
    if (verificationKey.value === props.code) emit('submit');

    message.value = 'Invalid Code'
    title.value = 'Invalid'
    type.value = 'error'
    isError.value = true
    setTimeout(() => {
        isError.value = false
    }, 3000)
    console.log('Test')




}

const resendCode = () => {

    emit('resend')
    message.value = 'New Code Sent'
    title.value = 'Sent'
    type.value = 'info'
    isError.value = true
    setTimeout(() => {
        isError.value = false
    }, 3000)
}
</script>


<style></style>
