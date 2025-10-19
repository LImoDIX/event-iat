иь<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    news: Object,
});

// Функция для получения корректного URL изображения
const getImageUrl = (imagePath) => {
    if (!imagePath) return null;
    
    if (imagePath.startsWith('http')) {
        return imagePath;
    }
    
    if (imagePath.startsWith('storage/')) {
        return `/${imagePath}`;
    }
    
    return `/storage/${imagePath}`;
};
</script>

<template>
    <Head :title="news.title" />

    <AuthenticatedLayout>
        <div class="py-8 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 min-h-screen">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок и навигация -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ news.title }}</h1>
                        <p class="text-gray-600">Подробная информация о новости</p>
                    </div>
                    <Link 
                        :href="route('dashboard.visitor')" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Назад к дашборду
                    </Link>
                </div>

                <!-- Основной контент -->
                <div class="bg-white border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <!-- Фотография новости -->
                    <div v-if="news.photo_path" class="h-96 bg-gray-100">
                        <img
                            :src="getImageUrl(news.photo_path)"
                            :alt="news.title"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            width="800"
                            height="384"
                        >
                    </div>
                    <div v-else class="h-96 bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-24 h-24 text-blue-300 mx-auto mb-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Изображение отсутствует</p>
                        </div>
                    </div>

                    <!-- Содержание новости -->
                    <div class="p-8">
                        <div class="prose max-w-none">
                            <p class="text-gray-700 text-lg leading-relaxed whitespace-pre-line">{{ news.description || 'Описание отсутствует' }}</p>
                        </div>
                        
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Опубликовано: {{ new Date(news.created_at).toLocaleDateString('ru-RU') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>