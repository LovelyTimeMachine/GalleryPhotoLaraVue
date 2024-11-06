<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-pink-400">{{ user.name }}'s Profile</h2>
        </template>

        <div class="py-12 bg-gray-900">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-300">
                        <div class="flex items-center mb-4">
                            <img :src="user.profile_picture" alt="Profile Picture" class="w-16 h-16 rounded-full mr-4" />
                            <div>
                                <h3 class="text-xl font-semibold text-white">{{ user.name }}</h3>
                                <p class="text-gray-400">{{ user.bio }}</p>
                            </div>
                        </div>
                        <h2 class="text-xl mb-5 font-semibold leading-tight text-pink-400">Uploaded Photos</h2>
                        <div class="masonry">
                            <div 
                                v-for="photo in userPhotos" 
                                :key="photo.id" 
                                class="masonry-item overflow-hidden rounded-lg bg-gray-700 shadow mb-4 relative cursor-pointer" 
                                @click="openPhoto(photo)"
                            >
                                <img :src="photo.url" :alt="photo.alt" class="w-full h-48 object-cover rounded-lg transition-transform duration-200 transform hover:scale-105" />
                                <p class="text-sm text-white-300 font-semibold ml-5 mt-3">{{ photo.title }}</p>
                                <p class="text-sm text-white-300 font-semibold ml-5 mb-3">{{ photo.description }}</p>
                                <button 
                                    v-if="photo.user.id === user.id" 
                                    @click.stop="editPhoto(photo)" 
                                    class="absolute top-2 right-2 bg-blue-500 text-white rounded px-2 py-1 text-xs hover:bg-blue-600 transition duration-200"
                                >
                                    Edit
                                </button>
                                <button 
                                    v-if="photo.user.id === user.id" 
                                    @click.stop="confirmDeletePhoto(photo.id)" 
                                    class="absolute bottom-2 right-2 bg-red-500 text-white rounded px-2 py-1 text-xs hover:bg-red-600 transition duration-200"
                                >
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div v-if="userPhotos.length === 0" class="text-gray-400 mt-4">No photos uploaded yet.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Component -->
        <Alert 
            v-if="alert.show" 
            :message="alert.message" 
            :type="alert.type" 
            :show="alert.show" 
            @close="alert.show = false" 
        />

        <!-- Confirmation Modal for Deletion -->
        <div v-if="showConfirmationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full relative">
                <h3 class="text-lg font-semibold text-white">Confirm Deletion</h3>
                <p class="text-gray-400 mt-2">Are you sure you want to delete this photo?</p>
                <div class="flex justify-end mt-4">
                    <button 
                        @click="deletePhoto(selectedPhotoId)" 
                        class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600 transition duration-200"
                    >
                        Yes, Delete
                    </button>
                    <button 
                        @click="closeConfirmationModal" 
                        class="ml-2 bg-gray-500 text-white rounded px-4 py-2 hover:bg-gray-600 transition duration-200"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- Photo Popup Modal -->
        <div v-if="showPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full relative overflow-hidden">
                <button @click="closePhotoModal" class="text-gray-400 hover:text-white absolute top-4 right-4 text-2xl">&times;</button>
                <div class="overflow-y-auto max-h-[75vh]">
                    <img :src="selectedPhoto.url" :alt="selectedPhoto.alt" class="w-full max-w-md h-auto rounded-md mb-4 mx-auto" />
                    <h3 class="text-xl font-semibold text-white">{{ selectedPhoto.title }}</h3>
                    <p class="text-gray-400">{{ selectedPhoto.description }}</p>
                    <div class="flex items-center space-x-2 mt-4">
                        <button 
                            class="flex items-center space-x-1" 
                            :class="{'text-red-500': selectedPhoto.isLiked, 'text-gray-300': !selectedPhoto.isLiked}" 
                            @click="toggleLikePhoto(selectedPhoto.id)"
                        >
                            <i class="fas fa-heart"></i>
                            <span>{{ selectedPhoto.likes }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Photo Modal -->
        <div v-if="showEditPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
            <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full relative">
                <button @click="closeEditPhotoModal" class="text-gray-400 hover:text-white absolute top-4 right-4 text-2xl">&times;</button>
                <h3 class="text-xl font-semibold text-white">Edit Photo</h3>
                <input 
                    v-model="editForm.title" 
                    type="text" 
                    placeholder="Title" 
                    class="mt-2 p-2 w-full bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring focus:ring-pink-500"
                />
                <textarea 
                    v-model="editForm.description" 
                    placeholder="Description" 
                    class="mt-2 p-2 w-full bg-gray-700 text-white rounded border border-gray-600 focus:outline-none focus:ring focus:ring-pink-500"
                ></textarea>
                <div class="flex justify-between items-center mt-4">
                    <button 
                        @click="submitEditPhoto" 
                        class="bg-pink-500 text-white rounded px-4 py-2 hover:bg-pink-600 transition duration-200"
                    >
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Alert from '@/Components/Alert.vue'; // Import the Alert component
import { ref } from 'vue';
import axios from 'axios';

// Props from the server-side
const props = defineProps(['user', 'photos']);

// Reactive variables
const userPhotos = ref(props.photos ? [...props.photos] : []);
const showPhotoModal = ref(false);
const showEditPhotoModal = ref(false);
const showConfirmationModal = ref(false);
const selectedPhoto = ref(null);
const selectedPhotoId = ref(null); // To hold the ID of the photo to be deleted
const editForm = ref({
    title: '',
    description: '',
});

// Alert state
const alert = ref({
    show: false,
    message: '',
    type: 'success', // Can be 'success' or 'error'
});

// Function to show alert
const showAlert = (message, type = 'success') => {
    alert.value = { show: true, message, type };
};

// Open photo in modal
const openPhoto = (photo) => {
    selectedPhoto.value = photo;
    showPhotoModal.value = true;
};

// Close photo modal
const closePhotoModal = () => {
    showPhotoModal.value = false;
    selectedPhoto.value = null;
};

// Open edit photo modal
const editPhoto = (photo) => {
    selectedPhoto.value = photo;
    editForm.value.title = photo.title;
    editForm.value.description = photo.description;
    showEditPhotoModal.value = true;
};

// Close edit photo modal
const closeEditPhotoModal = () => {
    showEditPhotoModal.value = false;
    selectedPhoto.value = null; // Reset selected photo
};

// Confirm delete photo
const confirmDeletePhoto = (photoId) => {
    selectedPhotoId.value = photoId; // Store the photo ID to delete
    showConfirmationModal.value = true; // Show confirmation modal
};

// Close confirmation modal
const closeConfirmationModal = () => {
    showConfirmationModal.value = false;
    selectedPhotoId.value = null; // Reset selected photo ID
};

// Submit edit photo
const submitEditPhoto = async () => {
    if (!editForm.value.title || !editForm.value.description) {
        showAlert('Please fill in all fields.', 'error');
        return;
    }

    try {
        const response = await axios.put(`/photos/${selectedPhoto.value.id}`, {
            title: editForm.value.title,
            description: editForm.value.description,
        });

        // Update the photo in the reactive array
        const updatedPhoto = userPhotos.value.find(p => p.id === selectedPhoto.value.id);
        if (updatedPhoto) {
            updatedPhoto.title = response.data.photo.title; 
            updatedPhoto.description = response.data.photo.description; 
        }

        closeEditPhotoModal(); 
        showAlert('Photo updated successfully.'); // Show success alert
    } catch (error) {
        console.error('Error updating photo:', error.response ? error.response.data : error.message);
        showAlert(`Error updating photo: ${error.response ? error.response.data.message : error.message}`, 'error'); // Show error alert
    }
};

// Delete Photo Function
const deletePhoto = async (photoId) => {
    try {
        await axios.delete(`/photos/${photoId}`);
        userPhotos.value = userPhotos.value.filter(photo => photo.id !== photoId); // Remove photo from display
        closeConfirmationModal(); // Close modal after deleting
        
        // Show success alert
        showAlert('Photo deleted successfully.'); 
    } catch (error) {
        console.error('Error deleting photo:', error.response ? error.response.data : error.message);
        showAlert(`Error deleting photo: ${error.response ? error.response.data.message : error.message}`, 'error'); // Show error alert
    }
};

// Toggle like/unlike photo
const toggleLikePhoto = async (photoId) => {
    try {
        const response = await axios.post(`/photos/${photoId}/like`);
        const likedPhoto = userPhotos.value.find(p => p.id === photoId);
        if (likedPhoto) {
            likedPhoto.isLiked = !likedPhoto.isLiked;
            likedPhoto.likes = response.data.likes;
        }
    } catch (error) {
        console.error('Failed to like/unlike the photo:', error);
    }
};
</script>

<style scoped>
/* Overlay styling for modal */
.modal-overlay {
    display: flex;
    align-items: center;
    justify-content: center;
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.75);
    z-index: 1000;
}

/* Main modal container styling */
.modal {
    background-color: #1a1a1a;
    padding: 2rem;
    border-radius: 0.5rem;
    max-width: 500px;
    width: 100%;
}

/* Masonry styling */
.masonry, .masonry-modal {
    column-count: 4;
    column-gap: 10px;
}

.masonry-item, .masonry-item-modal {
    break-inside: avoid;
    border-radius: 0.5rem;
    margin-bottom: 10px;
}

/* Responsive columns for both masonry and masonry modal */
@media (max-width: 768px) {
    .masonry, .masonry-modal {
        column-count: 2;
    }
}

@media (max-width: 480px) {
    .masonry, .masonry-modal {
        column-count: 1;
    }
}

/* Pulse effect on like button */
.like-pulse {
    animation: pulse 0.8s ease-out forwards;
}

/* Smooth opacity transition for like count */
.fade-likes-enter-active, .fade-likes-leave-active {
    transition: opacity 0.5s ease;
}
.fade-likes-enter, .fade-likes-leave-to {
    opacity: 0;
}

/* Keyframes for like button pulse animation */
@keyframes pulse {
    0% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.2);
    }
    100% {
        transform: scale(1);
    }
}

/* Heart icon transition for like/unlike */
i {
    transition: transform 0.5s ease, color 0.3s ease;
}

/* Smooth color transition when liked */
.text-red-500 {
    transition: color 0.5s ease-in-out;
}

/* General image styling */
img {
    height: auto;
    object-fit: cover;
}
</style>
