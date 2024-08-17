<script setup>
import { defineProps } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    campaign: Object,
});
console.log("Email Logs:", props.campaign.contacts);
</script>

<template>
    <Head title="Campaign Details" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaign Details
            </h2>
        </template>

        <div
            class="custom-max-width max-w-7xl mx-auto p-4 mt-5 sm:p-6 lg:p-8 bg-white shadow sm:rounded-lg"
        >
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700">
                        Campaign Name
                    </h4>
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ props.campaign.name }}
                    </h3>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">Status</h4>
                    <p class="text-gray-900">{{ props.campaign.status }}</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700">
                        Created At
                    </h4>
                    <p class="text-gray-900">
                        {{
                            new Date(
                                props.campaign.created_at
                            ).toLocaleDateString()
                        }}
                    </p>
                </div>
                <Link
                    :href="route('campaign.index')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Back to Campaigns
                </Link>
            </div>

            <div class="mt-6">
                <!-- <h4 class="text-sm font-medium text-gray-700">Contacts</h4> -->
                <table class="min-w-full divide-y divide-gray-200 mt-4">
                    <thead>
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Name
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Email
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Status
                            </th>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Sent At
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="contact in props.campaign.contacts"
                            :key="contact.id"
                        >
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                            >
                                {{ contact.name }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ contact.email }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{ contact.status }}
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                            >
                                {{
                                    new Date(
                                        contact.updated_at
                                    ).toLocaleDateString()
                                }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
