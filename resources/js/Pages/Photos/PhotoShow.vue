    <template>
<AuthenticatedLayout>

        <div class="photo-show max-w-3xl mx-auto p-6 bg-gray-800 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-pink-400 mb-4">{{ photo.title }}</h1>
            <img :src="photo.url" :alt="photo.alt" class="w-full h-auto rounded-lg mb-6 shadow-md" />
            <p class="text-gray-300 mb-4">{{ photo.description }}</p>
            
            <h2 class="text-2xl font-semibold mt-6 text-pink-400 border-b pb-2">Comments</h2>
            <div v-if="photo.comments.length === 0" class="text-gray-400">No comments yet.</div>
            
            <div v-else class="mt-4">
                <div v-for="comment in photo.comments" :key="comment.id" class="comment mb-4 border-b pb-2">
                    <p class="font-semibold text-pink-300">{{ comment.user.name }}:</p>
                    <p class="text-gray-300">{{ comment.content }}</p>
                </div>
            </div>
    
            <div class="mt-6">
                <input 
                    v-model="newComment" 
                    type="text" 
                    placeholder="Add a comment..." 
                    class="border border-gray-600 rounded p-2 w-full bg-gray-700 text-white focus:ring focus:ring-pink-500" 
                />
                <button 
                    @click="addComment" 
                    class="mt-2 bg-pink-500 text-white rounded px-4 py-2 hover:bg-pink-600 transition duration-200"
                >
                    Submit
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
    </template>

<script setup>
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
const photo = props.photo; // Assuming you pass the photo data as props
const newComment = ref('');

const addComment = async () => {
    if (!newComment.value) return; // Ensure there's content

    try {
        const response = await fetch(`/photos/${photo.id}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                content: newComment.value
            })
        });

        if (!response.ok) {
            // Log the response for debugging
            const errorText = await response.text();
            console.error('Error response:', errorText);
            throw new Error('Network response was not ok: ' + errorText);
        }

        const comment = await response.json();
        photo.comments.push(comment); // Update the comments in the local state
        newComment.value = ''; // Clear the input field
    } catch (error) {
        console.error('Error adding comment:', error);
    }
};
</script>

<style scoped>
.photo-show {
    transition: all 0.3s ease;
}

.photo-show:hover {
    transform: scale(1.02);
}
</style>
