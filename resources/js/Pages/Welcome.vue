<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref, defineProps, reactive } from 'vue';

// Define component props
const props = defineProps({
    canLogin: {
        type: Boolean,
        default: true,
    },
    canRegister: {
        type: Boolean,
        default: true,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
    animationDuration: {
        type: Number,
        default: 3000, // Duration in milliseconds
    },
});

// Reactive state for welcome animation
const welcomeAnimation = ref(false);

// Reactive state for image load error
const imageError = ref(false);

// Handle image loading error
function handleImageError() {
    imageError.value = true;
}

// Lifecycle hook to manage welcome animation timing
onMounted(() => {
    welcomeAnimation.value = true;
    setTimeout(() => {
        welcomeAnimation.value = false;
    }, props.animationDuration);
});
</script>

<template>
    <Head title="Gallery Showcase" />

    <!-- Welcome Animation Overlay -->
    <transition name="fade">
        <div v-if="welcomeAnimation" class="fixed inset-0 flex items-center justify-center bg-black/75 text-white z-50">
            <h1 class="text-4xl lg:text-6xl font-bold">Welcome to Arrietty Media</h1>
        </div>
    </transition>

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50 min-h-screen flex flex-col items-center relative overflow-hidden">
        <!-- Background Image -->
        <img
            v-if="!imageError"
            class="absolute -left-20 top-0 max-w-[877px] opacity-20 dark:opacity-40 transition-opacity duration-500"
            @error="handleImageError"
            src="https://laravel.com/assets/img/welcome/background.svg"
            alt="Background Illustration"
        />

        <!-- Fallback for Background Image Error -->
        <div v-else class="absolute inset-0 bg-gray-200 dark:bg-gray-800"></div>

        <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl py-10">
            <!-- Header -->
            <header class="grid grid-cols-2 items-center gap-2 py-6 lg:grid-cols-3">
                <div class="flex justify-start lg:col-start-2 lg:justify-center">
                    <svg
                        class="h-12 w-auto text-[#FF2D20] lg:h-16"
                        viewBox="0 0 62 65"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                        aria-label="Arrietty Media Logo"
                    >
                        <path d="M61.8548 14.6253C61.8778 14.7102 ..." fill="currentColor" />
                    </svg>
                </div>
                <nav v-if="props.canLogin" class="-mx-3 flex flex-1 justify-end space-x-2">
                    <Link
                        v-if="$page.props.auth.user"
                        :href="route('dashboard')"
                        class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                    >
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Log in
                        </Link>
                        <Link
                            v-if="props.canRegister"
                            :href="route('register')"
                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                        >
                            Register
                        </Link>
                    </template>
                </nav>
            </header>

            <!-- Main Content -->
            <main class="mt-6 flex flex-col items-center gap-6">
    <!-- Enlarged SVG Icon -->
    <svg class="w-24 h-24 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.933 13.909A4.357 4.357 0 0 1 3 12c0-1 4-6 9-6m7.6 3.8A5.068 5.068 0 0 1 21 12c0 1-3 6-9 6-.314 0-.62-.014-.918-.04M5 19 19 5m-4 7a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
    </svg>

    <!-- Explore Button -->
    <Link :href="route('dashboard')" class="rounded-lg bg-[#FF2D20] text-white py-2 px-4 text-center hover:bg-[#e5261b] transition">
        Explore
    </Link>
</main>

            <!-- Footer -->
            <footer class="mt-10 text-center text-sm text-gray-500 dark:text-gray-400">
                <p>
                    Powered by Vue & Laravel | Laravel Version: {{ props.laravelVersion }} | PHP Version: {{ props.phpVersion }}
                </p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
/* Fade transition for welcome animation */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* FadeInOut Animation Keyframes (if needed for other elements) */
@keyframes fadeInOut {
    0%,
    100% {
        opacity: 0;
    }
    50% {
        opacity: 1;
    }
}
.animate-fade-in-out {
    animation: fadeInOut 3s ease-in-out;
}
</style>
