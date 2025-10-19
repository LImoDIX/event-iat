<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    events: Object,
    query: String,
});

const searchQuery = ref(props.query || '');

const handleSearch = (e) => {
    e.preventDefault();
    if (searchQuery.value.trim()) {
        router.get(route('visitor.events.search', { query: searchQuery.value.trim() }));
    }
};

const resetSearch = () => {
    searchQuery.value = '';
    router.get(route('visitor.events.index'));
};

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
    <Head title="Мероприятия" />

    <AuthenticatedLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок -->
                <div class="text-center mb-12 relative">
                    <div class="absolute top-0 right-0">
                        <Link
                            :href="route('dashboard.visitor')"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            На главную
                        </Link>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        Мероприятия
                    </h1>
                    <p class="text-gray-600 text-lg max-w-2xl mx-auto">Откройте для себя увлекательные мероприятия и присоединяйтесь к сообществу</p>
                </div>

                <!-- Поиск -->
                <form @submit="handleSearch" class="mb-12">
                    <div class="flex flex-col md:flex-row gap-4 max-w-4xl mx-auto">
                        <div class="flex flex-1">
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Поиск мероприятий по названию..."
                                class="flex-1 border border-gray-300 rounded-l-xl px-6 py-4 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg"
                            />
                            <button
                                type="submit"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-r-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Поиск
                            </button>
                        </div>
                        <button
                            type="button"
                            @click="resetSearch"
                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-4 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Сброс
                        </button>
                    </div>
                </form>

                <!-- Сетка мероприятий -->
                <div v-if="events.data.length > 0">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div
                            v-for="event in events.data"
                            :key="event.id"
                            class="group bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl hover:border-blue-300 transition-all duration-300"
                        >
                            <!-- Фотография мероприятия -->
                            <div class="h-48 overflow-hidden bg-gray-100">
                                <img
                                    v-if="event.photo_path"
                                    :src="getImageUrl(event.photo_path)"
                                    :alt="event.name"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                    loading="lazy"
                                    width="300"
                                    height="200"
                                >
                                <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                    <svg class="w-16 h-16" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-4">
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2">{{ event.name }}</h3>
                                    <!-- Статус регистрации -->
                                    <div v-if="event.user_registered" class="flex-shrink-0 ml-3">
                                        <span class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full border border-green-200">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Зарегистрирован
                                        </span>
                                    </div>
                                </div>
                                
                                <p class="text-gray-600 text-sm mb-6 line-clamp-3 leading-relaxed">{{ event.description }}</p>
                                
                                <!-- Организатор мероприятия -->
                                <div class="flex items-center text-gray-600 text-sm mb-6">
                                    <div class="flex items-center gap-3">
                                        <div class="relative">
                                            <div v-if="event.organizer.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                                <img
                                                    :src="getImageUrl(event.organizer.profile_photo_path)"
                                                    :alt="event.organizer.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="40"
                                                    height="40"
                                                >
                                            </div>
                                            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-white shadow-sm">
                                                <span class="text-sm font-bold text-blue-600">
                                                    {{ event.organizer.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="font-medium">{{ event.organizer.name }}</span>
                                    </div>
                                </div>
                                
                                <!-- Детали мероприятия -->
                                <div class="flex justify-between items-center mb-6 text-sm text-gray-600">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                        </svg>
                                        <span>{{ new Date(event.start_time).toLocaleDateString('ru-RU') }}</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                        </svg>
                                        <span>{{ event.duration }} мин</span>
                                    </div>
                                </div>
                                
                                <!-- Кнопки действий -->
                                <div class="space-y-3">
                                    <Link
                                        :href="route('visitor.events.show', event.id)"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Подробнее
                                    </Link>
                                    
                                    <!-- Кнопка отмены регистрации -->
                                    <form
                                        v-if="event.user_registered && new Date(event.start_time) > new Date()"
                                        @submit.prevent="$event.target.submit()"
                                        :action="route('visitor.events.cancel-registration', event.id)"
                                        method="post"
                                    >
                                        <input type="hidden" name="_method" value="delete">
                                        <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                        <button
                                            type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-red-600/20"
                                            onclick="return confirm('Вы уверены, что хотите отменить регистрацию на это мероприятие?')"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                            Отменить регистрацию
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Пагинация -->
                    <div class="mt-12 flex justify-center items-center gap-4">
                        <Link
                            v-if="events.prev_page_url"
                            :href="events.prev_page_url"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Назад
                        </Link>
                        <span v-else class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Назад
                        </span>
                        
                        <span class="text-gray-600 font-medium">
                            Страница {{ events.current_page }} из {{ events.last_page }}
                        </span>
                        
                        <Link
                            v-if="events.next_page_url"
                            :href="events.next_page_url"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                        >
                            Вперед
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                        <span v-else class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl font-medium">
                            Вперед
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    </div>
                </div>
                
                <!-- Пустой state -->
                <div v-else class="text-center py-20">
                    <div class="text-6xl mb-6">🔍</div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-4">Мероприятия не найдены</h3>
                    <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">
                        Попробуйте изменить параметры поиска или сбросить фильтры
                    </p>
                    <button
                        @click="resetSearch"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Сбросить поиск
                    </button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>