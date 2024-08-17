<script setup>
import { defineProps } from "vue";
import { Link } from "@inertiajs/vue3";

// Props for pagination links
const props = defineProps({
    paginationLinks: Array,
});
</script>

<template>
    <nav role="navigation" aria-label="Pagination Navigation">
        <ul class="flex justify-center space-x-2">
            <li v-for="link in paginationLinks" :key="link.url">
                <Link
                    v-if="link.url"
                    :href="link.url"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium"
                    :class="{
                        'bg-blue-600 text-white': link.active, // Active link
                        'bg-white text-gray-700 hover:bg-gray-50': !link.active, // Inactive links
                    }"
                >
                    <span v-html="link.label"></span>
                </Link>
                <span
                    v-else
                    class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium bg-white text-gray-500"
                >
                    <span v-html="link.label"></span>
                </span>
            </li>
        </ul>
    </nav>
</template>
