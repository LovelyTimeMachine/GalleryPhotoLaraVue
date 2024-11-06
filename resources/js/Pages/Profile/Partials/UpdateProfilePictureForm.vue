<template>
    <div class="bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-lg font-semibold leading-tight text-pink-400 mb-4">Update Profile Picture</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Profile Picture Upload Section -->
            <div>
                <label for="profile_picture" class="block text-sm font-medium text-gray-300">Profile Picture</label>
                <div class="mt-2 flex flex-col items-center">
                    <input
                        type="file"
                        id="profile_picture"
                        accept="image/*"
                        @change="handleFileChange"
                        class="block w-full text-sm text-gray-400 file:py-2 file:px-4 file:rounded-md file:border-0
                        file:text-sm file:font-semibold file:bg-indigo-500 file:text-white
                        hover:file:bg-indigo-600 focus:file:bg-indigo-600"
                    />
                    <p class="text-xs text-gray-500 mt-1">Choose an image file (JPEG, PNG)</p>
                </div>
            </div>

            <!-- Preview Section -->
            <div v-if="form.profile_picture" class="mt-4">
                <h3 class="text-sm font-medium text-gray-300">Preview:</h3>
                <img :src="URL.createObjectURL(form.profile_picture)" alt="Profile Picture Preview" 
                     class="mt-2 w-32 h-32 object-cover rounded-full border-4 border-indigo-500 shadow-md"/>
            </div>

            <!-- Error Message -->
            <InputError class="mt-2" :message="form.errors.profile_picture" />

            <!-- Update Button -->
            <div class="mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing" class="w-full bg-indigo-500">
                    Update Profile Picture
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const form = useForm({
    profile_picture: null,
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    form.profile_picture = file;
};

const submit = () => {
    form.post(route('profile.updatePicture'), {
        onFinish: () => form.reset('profile_picture'),
    });
};
</script>

<style scoped>
/* Add any styles specific to the UpdateProfilePictureForm here */
</style>
