<script setup>
import { onMounted, ref } from 'vue';

const model = defineModel({
    type: String,
    required: true,
});

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({ focus: () => input.value.focus() });
</script>

<template>
    <input class="rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 " v-model="model"
        ref="input" />

</template>

<!-- shadow-[0_4px_6px_-1px_rgba(0,0,0,0.2),4px_0_6px_-1px_rgba(0,0,0,0.2)] -->
