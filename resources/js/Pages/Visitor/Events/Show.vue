<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
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
    <Head title="Мероприятие" />

    <AuthenticatedLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок и навигация -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
                    <div>
                        <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ event.name }}</h1>
                        <p class="text-gray-600">Подробная информация о мероприятии</p>
                    </div>
                    <Link 
                        :href="route('visitor.events.index')" 
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Все мероприятия
                    </Link>
                </div>

                <!-- Основной контент -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Левая колонка - фото и основная информация -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Фотография мероприятия -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                            <div class="h-80 bg-gray-100">
                                <img
                                    v-if="event.photo_path"
                                    :src="getImageUrl(event.photo_path)"
                                    :alt="event.name"
                                    class="w-full h-full object-cover"
                                    loading="lazy"
                                    width="400"
                                    height="300"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-20 h-20" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Описание мероприятия -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                            <h2 class="text-2xl font-bold text-gray-900 mb-4">Описание мероприятия</h2>
                            <p class="text-gray-700 leading-relaxed">{{ event.description || 'Описание отсутствует' }}</p>
                        </div>
                    </div>

                    <!-- Правая колонка - детали и регистрация -->
                    <div class="space-y-6">
                        <!-- Организатор -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 bg-blue-100 rounded-xl">
                                    <svg class="w-6 h-6 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">Организатор</h3>
                            </div>
                            <div class="flex items-center gap-4">
                                <div v-if="event.organizer.profile_photo_path" class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                    <img
                                        :src="getImageUrl(event.organizer.profile_photo_path)"
                                        :alt="event.organizer.name"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                        width="64"
                                        height="64"
                                    >
                                </div>
                                <div v-else class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-white shadow-sm">
                                    <span class="text-xl font-bold text-blue-600">
                                        {{ event.organizer.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900">{{ event.organizer.name }}</p>
                                    <p class="text-gray-600">Организатор</p>
                                </div>
                            </div>
                        </div>

                        <!-- Дата и время -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 bg-green-100 rounded-xl">
                                    <svg class="w-6 h-6 text-green-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">Дата и время</h3>
                            </div>
                            <p class="text-gray-700 text-lg font-medium">{{ new Date(event.start_time).toLocaleString('ru-RU') }}</p>
                        </div>

                        <!-- Продолжительность -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="p-3 bg-purple-100 rounded-xl">
                                    <svg class="w-6 h-6 text-purple-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900">Продолжительность</h3>
                            </div>
                            <p class="text-gray-700 text-lg font-medium">{{ event.duration }} минут</p>
                        </div>

                        <!-- Регистрация -->
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6">
                            <h3 class="text-2xl font-bold text-gray-900 mb-6">Регистрация</h3>
                            
                            <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">Максимум участников:</span>
                                    <span class="text-gray-900 font-semibold">{{ event.max_attendees }}</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <Link
                                    v-if="!event.user_registered"
                                    :href="route('visitor.events.confirm-registration', event.id)"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20 text-lg"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    Зарегистрироваться
                                </Link>
                                
                                <div v-else class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                                    <div class="flex items-center justify-center gap-3 mb-3">
                                        <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-green-800 font-semibold text-lg">Вы зарегистрированы</span>
                                    </div>
                                    <p class="text-green-700 mb-4">Код подтверждения: <span class="font-mono font-bold">{{ event.user_registration_code }}</span></p>
                                    
                                    <!-- Кнопка отмены регистрации -->
                                    <form
                                        v-if="new Date(event.start_time) > new Date()"
                                        @submit.prevent="$event.target.submit()"
                                        :action="route('visitor.events.cancel-registration', event.id)"
                                        method="post"
                                    >
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-red-600/20"
                                            onclick="return confirm('Вы уверены, что хотите отменить регистрацию на это мероприятие?')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Отменить регистрацию
                                        </button>
                                    </form>
                                </div>

                                <div class="space-y-3">
                                    <Link
                                        :href="route('dashboard.visitor')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                        </svg>
                                        На главную
                                    </Link>
                                    
                                    <Link
                                        v-if="event.is_completed"
                                        :href="route('visitor.history')"
                                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        К истории мероприятий
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>