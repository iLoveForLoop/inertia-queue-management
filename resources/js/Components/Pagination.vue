<template>
    <div v-if="links.length > 3" class="flex items-center justify-between border-t border-gray-200 px-4 py-3 sm:px-6">
        <div class="flex flex-1 justify-between sm:hidden">
            <Link @click="handleClick(links[0].url || '')" :href="links[0].url || ''" :class="{
                'pointer-events-none opacity-50': !links[0].url
            }"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Previous
            </Link>
            <Link @click="handleClick(links[links.length - 1].url || '')" :href="links[links.length - 1].url || ''"
                :class="{
                    'pointer-events-none opacity-50': !links[links.length - 1].url
                }"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
            Next
            </Link>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    <span class="font-medium">{{ meta?.from }}</span>
                    to
                    <span class="font-medium">{{ meta?.to }}</span>
                    of
                    <span class="font-medium">{{ meta?.total }}</span>
                    results
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <Link v-for="(link, index) in links" :key="index" @click="handleClick(link.url)"
                        :href="link.url || ''" v-html="link.label" :class="{
                            'z-10 bg-blue-50 border-blue-500 text-blue-600': link.active,
                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                            'text-gray-400 pointer-events-none': !link.url,
                            'rounded-l-md': index === 0,
                            'rounded-r-md': index === links.length - 1,
                            'hidden md:inline-flex': index !== 0 && index !== links.length - 1 && index !== currentPage - 1 && index !== currentPage && index !== currentPage + 1
                        }" class="relative inline-flex items-center border px-4 py-2 text-sm font-medium focus:z-20"
                        preserve-state />
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const emit = defineEmits(['paginating']);

const handleClick = (url) => {
    if (!url) return;
    emit('paginating');
    // Inertia will handle the actual navigation
};


const props = defineProps({
    links: Array,
    meta: Object
});

const currentPage = computed(() => {
    const activeLink = props.links.find(link => link.active);
    return activeLink ? parseInt(activeLink.label) : 1;
});
</script>
