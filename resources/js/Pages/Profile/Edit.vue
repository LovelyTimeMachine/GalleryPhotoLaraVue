<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateProfilePictureForm from './Partials/UpdateProfilePictureForm.vue';
import { Head } from '@inertiajs/vue3';

// Mendefinisikan props yang diterima dari Inertia
const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-2xl font-bold leading-tight text-white">
                Profile Settings
            </h2>
        </template>

        <div class="py-12 bg-gray-900">
            <div class="mx-auto max-w-7xl space-y-8 sm:px-6 lg:px-8">
                <!-- Current Profile Section -->
                <div class="bg-gray-800 p-6 shadow rounded-lg flex items-center space-x-6">
                    <img 
                        :src="$page.props.auth.user.profile_picture" 
                        alt="Profile Picture" 
                        class="w-28 h-28 rounded-full border-4 border-indigo-500 shadow-lg"
                    />
                    <div>
                        <h3 class="text-xl font-semibold text-white">{{ $page.props.auth.user.name }}</h3>
                        <p class="text-gray-400">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>

                <!-- Forms Section -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div class="bg-gray-800 p-6 shadow rounded-lg">
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>

                    <div class="bg-gray-800 p-6 shadow rounded-lg">
                        <UpdateProfilePictureForm />
                    </div>

                    <div class="bg-gray-800 p-6 shadow rounded-lg">
                        <UpdatePasswordForm />
                    </div>

                    <div class="bg-gray-800 p-6 shadow rounded-lg">
                        <DeleteUserForm />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Style tambahan jika diperlukan */
</style>
