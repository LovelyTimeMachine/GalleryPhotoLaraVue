<template>
    <div v-if="visible" class="fixed top-4 right-4 w-96">
        <div 
            class="bg-green-500 text-white p-4 rounded shadow-lg" 
            v-if="type === 'success'"
        >
            <strong>Success!</strong> {{ message }}
            <button @click="close" class="ml-4 text-white font-bold">×</button>
        </div>
        <div 
            class="bg-red-500 text-white p-4 rounded shadow-lg" 
            v-if="type === 'error'"
        >
            <strong>Error!</strong> {{ message }}
            <button @click="close" class="ml-4 text-white font-bold">×</button>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        message: String,
        type: {
            type: String,
            default: 'success',
        },
    },
    data() {
        return {
            visible: true,
        };
    },
    methods: {
        close() {
            this.visible = false;
            this.$emit('close');
        },
    },
};
</script>

<style scoped>
/* Optional: Add any additional styles here */
</style>
