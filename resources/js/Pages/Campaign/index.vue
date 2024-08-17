<script setup>
import { computed } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";
import Pagination from "@/Components/Campaign/Pagination.vue";
import ProgressBar from "@/Components/Campaign/ProgressBar.vue";

const props = defineProps({
    campaigns: Object, // Expected to be an object for paginated data
});

const paginationLinks = computed(() => props.campaigns.links);

// Function to determine if the campaign is still in progress
const isInProgress = (campaign) => {
    const totalProcessed = campaign.sent_emails + campaign.failed_emails;
    const totalRemaining = campaign.total_contacts - totalProcessed;
    return totalRemaining > 0 || totalProcessed < campaign.total_contacts;
};

// Helper function for progress bar width
const getProgressStyle = (value) => ({
    width: `${value}%`,
});
</script>

<template>
    <Head title="Campaigns" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Campaigns
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow-sm sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Your Campaigns
                        </h3>
                        <Link
                            :href="route('campaign.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Create New Campaign
                        </Link>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
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
                                    Status
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="text-align: center"
                                >
                                    Progress
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    style="text-align: center"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr
                                v-for="campaign in props.campaigns.data"
                                :key="campaign.id"
                            >
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                                >
                                    {{ campaign.name }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                >
                                    {{ campaign.status }}
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
                                    style="width: 300px"
                                >
                                    <!-- Sent Progress Bar -->
                                    <ProgressBar
                                        :value="
                                            (campaign.sent_emails /
                                                campaign.total_contacts) *
                                            100
                                        "
                                        color="blue-600"
                                    />
                                    <!-- Failed Progress Bar -->
                                    <ProgressBar
                                        :value="
                                            (campaign.failed_emails /
                                                campaign.total_contacts) *
                                            100
                                        "
                                        color="red-600"
                                    />
                                    <!-- In Progress Bar -->
                                    <ProgressBar
                                        :value="
                                            isInProgress(campaign)
                                                ? ((campaign.total_contacts -
                                                      (campaign.sent_emails +
                                                          campaign.failed_emails)) /
                                                      campaign.total_contacts) *
                                                  100
                                                : 100
                                        "
                                        color="yellow-600"
                                    />
                                    <span class="text-xs text-gray-500">
                                        Sent:
                                        {{
                                            Math.round(
                                                (campaign.sent_emails /
                                                    campaign.total_contacts) *
                                                    100
                                            )
                                        }}% | Failed:
                                        {{
                                            Math.round(
                                                (campaign.failed_emails /
                                                    campaign.total_contacts) *
                                                    100
                                            )
                                        }}% | In Progress:
                                        {{
                                            isInProgress(campaign)
                                                ? Math.round(
                                                      ((campaign.total_contacts -
                                                          (campaign.sent_emails +
                                                              campaign.failed_emails)) /
                                                          campaign.total_contacts) *
                                                          100
                                                  )
                                                : 100
                                        }}%
                                    </span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium"
                                    style="text-align: center"
                                >
                                    <Link
                                        :href="
                                            route('campaign.show', campaign.id)
                                        "
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        View
                                    </Link>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-6">
                        <Pagination :paginationLinks="paginationLinks" />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
