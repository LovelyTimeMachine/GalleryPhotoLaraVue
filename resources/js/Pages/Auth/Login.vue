<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">

            <div class="mb-5">
                <InputLabel for="email" value="Email" />
                <TextInput
                    id="email"
                    type="email"
                    class="mt-2 block w-full rounded-md  border border-pink-400 p-3 text-black focus:outline-none focus:ring focus:ring-pink-500"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-5">
                <InputLabel for="password" value="Password" />
                <TextInput
                    id="password"
                    type="password"
                    class="mt-2 block w-full rounded-md border border-pink-400 p-3 text-black focus:outline-none focus:ring focus:ring-pink-500"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                />
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 flex items-center">
                <label class="flex items-center text-gray-400">
                    <Checkbox name="remember" v-model:checked="form.remember" class="form-checkbox h-4 w-4" />
                    <span class="ml-2 text-sm">Remember me</span>
                </label>
            </div>

            <div class="mt-6 flex items-center justify-between">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm underline hover:text-pink-400 transition duration-150"
                >
                    Forgot your password?
                </Link>

                <PrimaryButton
                    class="px-5 py-2 rounded-md font-medium transition duration-200 focus:outline-none"
                    :class="{ 'opacity-50 cursor-not-allowed': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </PrimaryButton>
            </div>

            <div class="mt-8 text-center">
                <span class="text-sm text-pink-400">Don't have an account?</span>
                <Link
                    :href="route('register')"
                    class="ml-2 font-medium underline hover:text-pink-400 transition duration-150"
                >
                    Register
                </Link>
            </div>
        </form>
    </GuestLayout>
</template>