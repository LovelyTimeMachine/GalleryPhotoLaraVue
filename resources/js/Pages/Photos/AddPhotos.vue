<template>
    <Head title="Add New Photo" />

    <AuthenticatedLayout :user="$page.props.auth.user">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-pink-400">
                Add New Photo
            </h2>
        </template>

        <div class="py-12 bg-gray-900">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-300">
                        <form @submit.prevent="submitPhoto" enctype="multipart/form-data">
                            <!-- Title -->
                            <div class="mb-4">
                                <label class="block text-white-300 text-sm mb-2" for="title">Title</label>
                                <input v-model="form.title" class="w-full px-3 py-2 text-gray-900" id="title" type="text" placeholder="Photo Title" required>
                            </div>

                            <!-- Description -->
                            <div class="mb-4">
                                <label class="block text-white-300 text-sm mb-2" for="description">Description</label>
                                <textarea v-model="form.description" class="w-full px-3 py-2 text-gray-900" id="description" rows="4" placeholder="Photo Description"></textarea>
                            </div>

                            <!-- Tags -->
                            <div class="mb-4">
                                <label class="block text-white-300 text-sm mb-2" for="tags">Tags</label>
                                <input v-model="form.tags" class="w-full px-3 py-2 text-gray-900" id="tags" type="text" placeholder="Tags (comma-separated)">
                            </div>

                            <!-- File Upload for Photo -->
                            <div class="mb-4">
                                <label class="block text-white-300 text-sm mb-2" for="file">Photo Upload</label>
                                <input type="file" @change="handleFileChange" class="w-full px-3 py-2 text-gray-900" id="file" required>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="px-4 py-2 bg-pink-400 text-gray-900 font-semibold rounded">Add Photo</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';


const form = useForm({
    title: '',
    description: '',
    tags: '',
    file: null, // Only keep file for upload
});

// Submit the photo upload
const submitPhoto = () => {
    form.post(route('photos.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: (errors) => {
            console.error(errors);
        },
    });
};

// Handle file change event
const handleFileChange = (event) => {
    form.file = event.target.files[0]; // Assign the selected file to the form
};
const props = defineProps(['photos', 'wallpapers', 'user']);

</script>
