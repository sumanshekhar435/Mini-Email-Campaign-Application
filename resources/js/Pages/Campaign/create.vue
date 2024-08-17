<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

const form = useForm({
    name: "",
    contacts_file: null,
});

const handleFileChange = (e) => {
    form.contacts_file = e.target.files[0];
};

const submit = (e) => {
    e.preventDefault();
    form.post(route("campaign.store"));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Campaign" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create a New Campaign
            </h2>
        </template>

        <div
            class="max-w-2xl mx-auto p-4 mt-5 sm:p-6 lg:p-8 bg-white shadow sm:rounded-lg"
        >
            <form @submit.prevent="submit" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Campaign Name" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="name"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="contacts_file" value="Upload CSV File" />
                    <input
                        id="contacts_file"
                        type="file"
                        @change="handleFileChange"
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                    />
                    <InputError
                        :message="form.errors.contacts_file"
                        class="mt-2"
                    />
                </div>
                <div
                    v-if="form.errors.csv_errors"
                    class="text-red-600 text-sm mt-4"
                >
                    <strong>CSV Validation Errors:</strong>
                    <ul>
                        <li
                            v-for="(
                                error, index
                            ) in form.errors.csv_errors.split('; ')"
                            :key="index"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </div>
                <div class="flex items-center justify-end">
                    <PrimaryButton :disabled="form.processing">
                        {{
                            form.processing
                                ? "Processing..."
                                : "Create Campaign"
                        }}
                    </PrimaryButton>
                </div>
            </form>
            <div class="mt-6">
                <Link
                    :href="route('campaign.index')"
                    class="text-indigo-600 hover:text-indigo-900"
                    >Back to Campaigns</Link
                >
            </div>
        </div>
    </AuthenticatedLayout>
</template>
