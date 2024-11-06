    <template>
        <Head title="Dashboard" />

        <AuthenticatedLayout>
            <template #header>
                <h2 class="text-xl font-semibold leading-tight text-pink-400">
                    Popular Images
                </h2>
            </template>

            <div class="py-12 bg-gray-900">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="overflow-hidden bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-300">
                            <h2 class="text-xl mb-5 font-semibold leading-tight text-pink-400">
                                Popular Images
                            </h2>
                            <div class="masonry">
                                <div v-for="photo in photos" :key="photo.id" 
    class="masonry-item overflow-hidden rounded-lg bg-gray-700 shadow mb-auto relative cursor-pointer" 
    @click="openPhoto(photo)">
        <img :src="photo.url" :alt="photo.alt" 
            class="w-full h-48 object-cover rounded-lg transition-transform duration-200 transform hover:scale-105" />
            <div class="flex justify-between items-center p-4 space-x-1">
                <button 
    class="flex items-center space-x-2 hover:text-pink-400" 
    @click.stop="showProfile(photo.user)"
>
    <img :src="photo.user.profile_picture" alt="Profile Picture" 
         class="w-10 h-10 rounded-full" />
    <span class="ml-2 text-sm text-white-300 font-medium">{{ photo.user.name }}</span>

    <span v-if="photo.user.role === 'admin'" class="glowing-text">
        <i class="fas fa-user-shield"></i>    </span>
</button>

    
    <div class="flex items-center space-x-4">
        <button 
            class="flex items-center space-x-1" 
            :class="{'text-red-500 leading-loose': photo.isLiked, 'like-pulse': photo.isAnimating}" 
            aria-label="Like"
            @click.stop="toggleLikePhoto(photo.id)"
        >
            <i class="fas fa-heart transition-all duration-500 ease-out" 
               :style="{ transform: photo.isLiked ? 'scale(1.3)' : 'scale(1)' }"></i>
            <transition name="fade-likes" mode="out-in">
                <span :key="photo.likes">{{ photo.likes }}</span>
            </transition>
        </button>
        
        <button 
            class="flex items-center space-x-1 text-gray-300 hover:text-blue-400" 
            aria-label="Comment"
        >
            <i class="fas fa-comment"></i>
            <span>{{ photo.comments.length }}</span> <!-- Display the number of comments -->
        </button>

        <!-- Add Dropdown Menu -->
        <button 
            class="flex items-center space-x-1 text-gray-300 hover:text-pink-400" 
            aria-label="More options"
            @click.stop="toggleDropdown(photo.id)"
        >
            <i class="fas fa-ellipsis-h"></i>
        </button>
        
        <div v-if="dropdownOpen === photo.id" class="absolute bg-gray-800 text-white rounded-md shadow-lg mt-2 p-2">
            <button @click="downloadPhoto(photo)" class="block w-full text-left">Download</button>
        </div>

<!-- Admin Delete Button -->
<button 
    v-if="isAdmin" 
    @click.stop="openDeleteConfirm(photo.id)" 
    class="flex items-center space-x-1 text-gray-300 hover:text-red-400" 
    aria-label="Delete"
>
    <i class="fas fa-trash-alt"></i>
</button>

<!-- Confirmation Modal -->
<div v-if="showDeleteConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full">
        <h3 class="text-lg font-semibold text-white mb-4">Confirm Deletion</h3>
        <p class="text-gray-400 mb-6">Are you sure you want to delete this photo? This action cannot be undone.</p>
        <div class="flex justify-end space-x-2">
            <button 
                @click="confirmDelete(photoToDelete)" 
                class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150 ease-in-out">
                Delete
            </button>
            <button 
                @click.stop="showDeleteConfirmModal = false" 
                class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
                Cancel
            </button>
        </div>
    </div>
</div>




    
<div v-if="dropdownOpen === photo.id" class="absolute bg-gray-800 text-white rounded-md shadow-lg mt-2 p-2">
    <button @click="downloadPhoto(photo)" class="block w-full text-left">Download</button>
  

</div>

                                        </div>
                                    </div>
                                    <p class="text-sm text-white font-bold ml-5 mb-1">{{ photo.title }}</p>
                                    <p class="text-sm text-white-300 font-semibold ml-5 mb-3">{{ photo.description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profile Modal -->
            <div v-if="showProfileModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
                <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full relative overflow-y-auto h-3/4">
                    <button @click="closeProfileModal" class="text-gray-400 hover:text-white absolute top-4 right-4 text-2xl">&times;</button>
                    <div class="flex items-center mb-4">
                        <img :src="selectedProfile?.profile_picture" alt="Profile Picture" 
                            class="w-16 h-16 rounded-full mr-4" />
                        <div>
                            <h3 class="text-xl font-semibold text-white">{{ selectedProfile?.name }}</h3>
                            <p class="text-gray-400">{{ selectedProfile?.bio }}</p>
                        </div>
                    </div>
                    <h4 class="text-lg text-pink-400 font-semibold mb-2">Photos by {{ selectedProfile?.name }}</h4>
                    <div class="masonry-modal">
                        <div v-for="photo in selectedUserPhotos" :key="photo.id" 
                            class="masonry-item-modal overflow-hidden rounded-lg bg-gray-700 shadow mb-4">
                            <img :src="photo.url" :alt="photo.alt" 
                                class="w-full h-32 object-cover rounded-lg transition-transform duration-200 transform hover:scale-105" />
                        </div>
                    </div>
                </div>
            </div>

          <!-- Photo Popup Modal -->
<div v-if="showPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-2xl w-full relative overflow-hidden">
        <button @click="closePhotoModal" class="text-gray-400 hover:text-white absolute top-4 right-4 text-2xl">&times;</button>
        <div class="overflow-y-auto max-h-[75vh]">
            <img :src="selectedPhoto.url" :alt="selectedPhoto.alt" 
                 class="w-full max-w-md h-auto rounded-md mb-4 mx-auto" />

            <h3 class="text-xl font-semibold text-white">{{ selectedPhoto.title }}</h3>
            <p class="text-gray-400">{{ selectedPhoto.description }}</p>
            <div class="flex items-center space-x-2 mt-4">
                <button 
                    class="flex items-center space-x-1" 
                    :class="{'text-red-500': selectedPhoto.isLiked, 'text-gray-300': !selectedPhoto.isLiked}" 
                    @click="toggleLikePhoto(selectedPhoto.id)">
                    <i class="fas fa-heart"></i>
                    <span>{{ selectedPhoto.likes }}</span>
                </button>

                <button class="flex items-center space-x-1 text-gray-300 hover:text-blue-400">
                    <i class="fas fa-comment"></i>
                    <span>{{ selectedPhoto.comments.length }}</span>
                </button>

            
            </div>
                <!-- Download Button -->
                <button 
                    @click="downloadPhoto(selectedPhoto)" 
                    class="bg-pink-500 text-white rounded px-4 py-2 hover:bg-pink-600 transition duration-200">
                    Download
                </button>
               <!-- Check that photo is defined for Edit Button -->
           

               <h2 class="text-2xl font-semibold mt-6 text-pink-400 border-b pb-2">Comments</h2>
<div v-if="selectedPhoto.comments.length === 0" class="text-gray-400">No comments yet.</div>
<div v-else class="mt-4">
    <div v-for="comment in selectedPhoto.comments" :key="comment.id" class="comment mb-4 border-b pb-2 flex items-start">
        <img 
            v-if="comment.user.profile_picture" 
            :src="comment.user.profile_picture" 
            alt="Profile picture" 
            class="w-10 h-10 rounded-full mr-2" 
        />
        <div>
            <p class="font-semibold text-pink-300">
                {{ comment.user.name }}
                <span v-if="comment.user.role === 'admin'" class="glowing-text">
                    <i class="fas fa-user-shield"></i>                </span>:
            </p>
            <p class="text-gray-300">{{ comment.content }}</p>
        </div>
    </div>
</div>



            <div class="mt-6">
                <div class="flex items-center mt-6">
                    <input 
                        v-model="newComment" 
                        type="text" 
                        placeholder="Type your comment..." 
                        class="border border-gray-600 rounded-l-lg p-2 w-full bg-gray-800 text-white placeholder-gray-400 focus:ring transition duration-200 ease-in-out"
                    />
                    <button 
                        @click="addComment" 
                        class="bg-pink-500 text-white rounded-r-lg px-4 py-2 hover:bg-pink-600 transition duration-200 flex items-center space-x-1">
                        <i class="fas fa-paper-plane"></i>
                        <span>Send</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Floating Icon Button to Open Modal -->
<button 
    v-if="isAuthenticated" 
    @click="showAddPhotoModal = true" 
    class="fixed bottom-16 right-8 bg-pink-400 text-white p-4 rounded-full shadow-lg hover:bg-pink-500 transition-all duration-300"
    aria-label="Add New Photo">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
</button>

<!-- Jika pengguna belum login, tampilkan pesan -->
<div v-else class="fixed bottom-8 right-8">
    <button 
        @click="window.location.href = '/login'" 
        class="bg-gray-400 text-white p-4 rounded-full shadow-lg hover:bg-gray-500 transition-all duration-300"
        aria-label="Login to Add Photo">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </button>
</div>



<!-- Add Photo Modal -->
<div v-if="showAddPhotoModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 transition-opacity duration-300 ease-out">
    <div class="relative bg-gray-800 p-8 rounded-lg shadow-xl max-w-lg w-full max-h-[90vh] overflow-y-auto transform transition-transform duration-300 scale-95 ">
        <!-- Close Button -->
        <button @click="showAddPhotoModal = false" class="text-gray-400 hover:text-pink-400 absolute top-4 right-4 text-3xl transition duration-300">
            &times;
        </button>
        
        <!-- Modal Title -->
        <h2 class="text-2xl font-semibold text-pink-400 text-center mb-6">Add New Photo</h2>
        
        <!-- Form -->
        <form @submit.prevent="submitPhoto" enctype="multipart/form-data" class="space-y-6">
            <!-- Title Input -->
            <div>
                <label for="title" class="block text-sm font-medium text-gray-300 mb-1">Title</label>
                <input 
                    v-model="form.title" 
                    type="text" 
                    id="title" 
                    placeholder="Enter photo title" 
                    required 
                    class="p-3 w-full bg-gray-700 rounded-lg text-gray-100 placeholder-gray-400 border border-gray-600 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 transition"
                >
            </div>

            <!-- Description Input -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-300 mb-1">Description</label>
                <textarea 
                    v-model="form.description" 
                    id="description" 
                    rows="3" 
                    placeholder="Enter a brief description" 
                    class="p-3 w-full bg-gray-700 rounded-lg text-gray-100 placeholder-gray-400 border border-gray-600 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 transition"
                ></textarea>
            </div>

            <!-- File Upload with Preview -->
            <div>
                <label for="file" class="block text-sm font-medium text-gray-300 mb-1">Photo Upload</label>
                <input 
                    type="file" 
                    @change="handleFileChange" 
                    id="file" 
                    accept="image/*" 
                    required 
                    class="p-3 w-full bg-gray-700 rounded-lg text-gray-100 placeholder-gray-400 border border-gray-600 focus:border-pink-400 focus:ring-1 focus:ring-pink-400 transition"
                >
                
                <!-- Preview Selected Image -->
                <div v-if="previewImage" class="mt-4 relative group">
                    <img :src="previewImage" alt="Selected Photo" class="w-full h-48 object-cover rounded-lg shadow-lg transition duration-300 ease-out transform hover:scale-105">
                    
                    <!-- Remove Preview Button -->
                    <button @click="removePreview" class="absolute top-2 right-2 bg-gray-700 bg-opacity-70 text-white rounded-full p-2 text-xs font-semibold opacity-0 group-hover:opacity-100 transition-opacity duration-200 ease-in-out hover:bg-pink-500">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full py-3 bg-pink-400 text-gray-900 font-semibold rounded-lg hover:bg-pink-500 transition transform hover:scale-105 duration-300">
                Add Photo
            </button>
        </form>
    </div>
</div>
  <!-- Floating Button to Open User List Modal -->
<button 
    v-if="isAuthenticated && isAdmin" 
    @click="openUserListModal" 
    class="fixed bottom-1 right-8 bg-pink-400 text-white p-4 rounded-full shadow-lg hover:bg-pink-500 transition-all duration-300"
    aria-label="Manage Users">
    <i class="fas fa-users"></i>
</button>

<div v-if="showUserListModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 transition-opacity duration-300 ease-in-out">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-300">
        <h3 class="text-lg font-semibold text-white mb-4">User Management</h3>
        <ul>
  <li v-if="!filteredUsers.length" class="text-white">No users found.</li>
  <li v-for="user in filteredUsers" :key="user.id" class="flex justify-between items-center mb-3">
    <span class="text-white">{{ user.name }}</span>
    <button @click="openDeleteConfirmModal(user.id)" class="text-red-500 hover:text-red-400">Delete</button>
  </li>
</ul>


        <button 
            @click="closeUserListModal" 
            class="mt-4 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">
            Close
        </button>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div v-if="showDeleteUserConfirmModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 transition-opacity duration-300 ease-in-out">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md w-full transform transition-all duration-300">
        <h3 class="text-lg font-semibold text-white mb-4">Confirm Deletion</h3>
        <p class="text-white">Are you sure you want to delete this user?</p>
        <div class="mt-4 flex justify-between">
            <button @click="confirmDeleteUser(userToDelete)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-150 ease-in-out">Yes, Delete</button>
            <button @click="showDeleteUserConfirmModal = false" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-150 ease-in-out">Cancel</button>
        </div>
    </div>
</div>
<div v-if="showAlert" class="fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg transition-opacity duration-300" role="alert">
  {{ alertMessage }}
</div>

</AuthenticatedLayout>
    </template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { ref, computed, watch } from 'vue';
const showAlert = ref(false);
const alertMessage = ref('');

// Data from Laravel via Inertia
const props = defineProps({
    users: Array, // Pastikan 'users' ada dan terisi data
    photos: Array,
    user: Object
});



// Create a reactive copy of photos
const photos = ref([...props.photos]);

// Reactive variables for profile and photo modals
const showProfileModal = ref(false);
const showPhotoModal = ref(false);
const selectedProfile = ref(null);
const selectedPhoto = ref(null);
const newComment = ref('');
const showAddPhotoModal = ref(false);
const previewImage = ref(null); // For image preview    
// Reactive variable for delete confirmation modal
const showDeleteConfirmModal = ref(false);
const photoToDelete = ref(null); // Store the photo ID to be deleted

// Reactive form for new photo submission
const form = ref({
    title: '',
    description: '',
    file: null,
});

// Check if user is authenticated
const isAuthenticated = !!props.user;


// Toggle like/unlike photo
const toggleLikePhoto = async (photoId) => {
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }

    try {
        const response = await axios.post(route('photos.like', photoId));
        const likedPhoto = photos.value.find(p => p.id === photoId);
        if (likedPhoto) {
            likedPhoto.isLiked = !likedPhoto.isLiked;
            likedPhoto.likes = response.data.likes;
            likedPhoto.isAnimating = true;
            setTimeout(() => (likedPhoto.isAnimating = false), 800);
        }
    } catch (error) {
        console.error('Failed to like/unlike the photo:', error);
    }
};

// Method to remove preview image
const removePreview = () => {
    previewImage.value = null;
    form.value.file = null; // Clear file data
    document.getElementById('file').value = ''; // Reset file input
};
// Show profile modal and user photos
const showProfile = (user) => {
    selectedProfile.value = user;
    showProfileModal.value = true;
};

// Close profile modal
const closeProfileModal = () => {
    showProfileModal.value = false;
    selectedProfile.value = null;
};

// Computed to get photos by the selected user
const selectedUserPhotos = computed(() => {
    return photos.value.filter(photo => photo.user.id === selectedProfile.value?.id);
});

// Open photo in modal
const openPhoto = (photo) => {
    selectedPhoto.value = photo;
    showPhotoModal.value = true;
};


// Close photo modal
const closePhotoModal = () => {
    showPhotoModal.value = false; // Close the modal
    selectedPhoto.value = null; // Reset selected photo to null
};

// Add comment
const addComment = async () => {
    if (!isAuthenticated) {
        window.location.href = '/login';
        return;
    }

    if (!newComment.value) return;

    try {
        const response = await axios.post(`/photos/${selectedPhoto.value.id}/comments`, {
            content: newComment.value,
        });

        selectedPhoto.value.comments.push({
            id: response.data.comment.id,
            user: { name: props.user.name, profile_picture: props.user.profile_picture, role: props.user.role },
            content: newComment.value,
            created_at: new Date().toISOString(),
        });

        // Reset komentar baru
        newComment.value = '';

        // Gunakan nextTick untuk memastikan ikon ter-render
        await Vue.nextTick();
    } catch (error) {
        console.error('Error adding comment:', error);
    }
};

// Handle file change for new photo upload
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.value.file = file;
        previewImage.value = URL.createObjectURL(file); // Set preview
    }
};

// Submit new photo
const submitPhoto = async () => {
    if (!isAuthenticated) {
        // Redirect to login if user is not authenticated
        window.location.href = '/login';
        return;
    }

    if (!form.value.title || !form.value.file) {
        alert('Please fill in all required fields.');
        return;
    }

    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('description', form.value.description);
    formData.append('file', form.value.file);

    try {
        const response = await axios.post(route('photos.store'), formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        // Proses respon jika upload sukses
        const newPhoto = {
            id: response.data.id,
            url: response.data.url,
            alt: response.data.alt || '',
            title: form.value.title,
            description: form.value.description,
            likes: 0,
            isLiked: false,
            comments: [],
            user: {
                id: props.user.id,
                name: props.user.name,
                profile_picture: props.user.profile_picture,
                role: props.user.role
            },
        };

        // Tambahkan foto baru ke array fotos
        photos.value.push(newPhoto);
        
        // Tutup modal dan reset form
        showAddPhotoModal.value = false;
        form.value = { title: '', description: '', file: null };
        previewImage.value = null; // Hapus preview
    } catch (error) {
        console.error('Error submitting photo:', error);
    }
};

// Watch for changes in the photos array (optional)
watch(photos, (newValue) => {
    console.log('Photos updated:', newValue);
});
const dropdownOpen = ref(null); // To track which dropdown is open

// Function to toggle the dropdown
const toggleDropdown = (photoId) => {
    dropdownOpen.value = dropdownOpen.value === photoId ? null : photoId; // Toggle dropdown
};

// Close dropdown when clicking outside (optional, but recommended)
const closeDropdown = () => {
    dropdownOpen.value = null;
};

// Watch for clicks outside the dropdown to close it
document.addEventListener('click', closeDropdown);

// Method to handle photo download
const downloadPhoto = (photo) => {
    // Create a temporary link to download the photo
    const link = document.createElement('a');
    link.href = photo.url; // Ensure this URL is accessible
    link.download = photo.title || 'download'; // Use the photo title as the filename
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
};

// Method to handle photo edit
const editPhoto = (photo) => {
    if (!photo) {
        console.error('Photo is not defined');
        return;
    }
    selectedPhoto.value = photo;
    console.log('Editing photo:', photo);
};

// Check if the user is an admin
const isAdmin = props.user && props.user.role === 'admin';

// Delete photo function
const deletePhoto = async (photoId) => {
    if (!isAdmin) {
        alert("You don't have permission to delete this photo.");
        return;
    }

    try {
        // Send delete request to server
        await axios.delete(route('photos.destroy', photoId));

        // Remove the deleted photo from the photos array
        photos.value = photos.value.filter(photo => photo.id !== photoId);

        // Close the photo modal if it's open and the deleted photo was the selected one
        if (selectedPhoto.value && selectedPhoto.value.id === photoId) {
            closePhotoModal(); // Close the modal
        }

        console.log(`Photo with ID ${photoId} deleted.`);
    } catch (error) {
        console.error('Failed to delete photo:', error);
    }
};

// Method to open delete confirmation modal
const openDeleteConfirm = (photoId) => {
    photoToDelete.value = photoId; // Set the photo ID to delete
    showDeleteConfirmModal.value = true; // Open the modal
};
// Method to confirm deletion
const confirmDelete = async () => {
    if (!isAdmin || !photoToDelete.value) return; // Check admin permission

    await deletePhoto(photoToDelete.value); // Delete the photo

    showDeleteConfirmModal.value = false; // Close the delete confirmation modal
    photoToDelete.value = null; // Reset the photo to delete
};


const showUserListModal = ref(false);
const showDeleteUserConfirmModal = ref(false);
const userToDelete = ref(null);
const localUsers = ref([]);
const filteredUsers = computed(() => {
  return localUsers.value.filter(user => user.role === 'user');
});


// Open the user list modal
const openUserListModal = async () => {
    try {
        const response = await axios.get('/users');
        localUsers.value = response.data; 
    } catch (error) {
        console.error("Gagal mengambil data pengguna:", error.response ? error.response.data : error.message);
        alert('Failed to fetch users. Please try again later.');
    }
    showUserListModal.value = true;
};

// Close the user list modal
const closeUserListModal = () => {
    showUserListModal.value = false;
};

// Open the delete confirmation modal
const openDeleteConfirmModal = (userId) => {
    userToDelete.value = userId;
    showDeleteUserConfirmModal.value = true;
};

// Confirm delete user
const confirmDeleteUser = async (userId) => {
  try {
    await axios.delete(`/users/${userId}`);
    
    // Remove the user’s posts from the photos array
    photos.value = photos.value.filter(photo => photo.user.id !== userId);
    
    // Show success alert
    alertMessage.value = 'User and their posts have been successfully deleted!';
    showAlert.value = true;
    
    // Auto-hide the alert after 3 seconds
    setTimeout(() => {
      showAlert.value = false;
    }, 3000);
    
  } catch (error) {
    console.error('Failed to delete user:', error);
    alertMessage.value = 'Failed to delete user. Please try again later.';
    showAlert.value = true;
    
    setTimeout(() => {
      showAlert.value = false;
    }, 3000);
  } finally {
    showDeleteUserConfirmModal.value = false;
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
    .glowing-text i,
.glowing-text .svg-inline--fa {
    color: #3b82f6;
    animation: pulse 1.5s infinite;
}

@keyframes pulse {
    0%, 100% {
        text-shadow: 0 0 8px rgba(59, 130, 246, 0.8), 0 0 15px rgba(59, 130, 246, 0.6);
        transform: scale(1);
    }
    50% {
        text-shadow: 0 0 16px rgba(59, 130, 246, 1), 0 0 30px rgba(59, 130, 246, 0.8);
        transform: scale(1.1);
    }
}

    </style>
