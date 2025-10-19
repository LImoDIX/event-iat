<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <Link href="/">
                    <ApplicationLogo class="h-20 w-20 fill-current text-indigo-600" />
                </Link>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                <slot name="title" />
            </h2>
            <p v-if="$slots.description" class="mt-2 text-center text-sm text-gray-600">
                <slot name="description" />
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-xl rounded-lg sm:px-10 transition-all duration-300 hover:shadow-2xl">
                <slot />
            </div>
        </div>
    </div>
</template>
