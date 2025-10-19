<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';
import { ref } from 'vue';

const props = defineProps({
    events: Object,
});

const searchQuery = ref('');

const handleSearch = () => {
    if (searchQuery.value.trim()) {
        router.get(route('admin.events.index'), { search: searchQuery.value.trim() }, { preserveState: true });
    } else {
        router.get(route('admin.events.index'), {}, { preserveState: true });
    }
};

const resetSearch = () => {
    searchQuery.value = '';
    router.get(route('admin.events.index'), {}, { preserveState: true });
};

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
    <Head title="Управление мероприятиями" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                        <div class="flex items-center gap-4">
                            <Link
                                :href="route('dashboard.admin')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                На главную
                            </Link>
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Управление мероприятиями</h2>
                                <p class="text-gray-600 mt-2">Всего мероприятий: {{ events.total }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Search Form -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-6 mb-8">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex flex-1">
                            <input
                                type="text"
                                v-model="searchQuery"
                                placeholder="Поиск мероприятий по названию..."
                                class="flex-1 border border-gray-300 rounded-l-xl px-6 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white text-gray-900 placeholder-gray-500"
                                @keyup.enter="handleSearch"
                            />
                            <button
                                @click="handleSearch"
                                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-r-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Поиск
                            </button>
                        </div>
                        <button
                            @click="resetSearch"
                            class="inline-flex items-center gap-2 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Сброс
                        </button>
                    </div>
                </div>
                
                <!-- Events Table -->
                <div v-if="events && events.data && events.data.length > 0">
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <!-- Table Head -->
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Фото</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Название</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Организатор</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Дата</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Участники</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Статус</th>
                                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Действия</th>
                                    </tr>
                                </thead>

                                <!-- Table Body -->
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="event in events.data" :key="event.id"
                                        class="hover:bg-gray-50 transition-colors">
                                        
                                        <!-- Фотография -->
                                        <td class="px-6 py-4">
                                            <div v-if="event.photo_path" class="w-16 h-16 rounded-lg overflow-hidden border border-gray-200">
                                                <img
                                                    :src="getImageUrl(event.photo_path)"
                                                    :alt="event.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="64"
                                                    height="64"
                                                    @error="(e) => { e.target.style.display = 'none'; }"
                                                >
                                            </div>
                                            <div v-else class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center border border-gray-200">
                                                <svg class="w-6 h-6 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                                </svg>
                                            </div>
                                        </td>
                                        
                                        <!-- Название -->
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-900">{{ event.name }}</div>
                                        </td>

                                        <!-- Организатор -->
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <div class="flex items-center gap-2">
                                                <div v-if="event.organizer?.profile_photo_path" class="w-8 h-8 rounded-full overflow-hidden border border-gray-200">
                                                    <img
                                                        :src="getImageUrl(event.organizer.profile_photo_path)"
                                                        :alt="event.organizer.name"
                                                        class="w-full h-full object-cover"
                                                        loading="lazy"
                                                        width="32"
                                                        height="32"
                                                    >
                                                </div>
                                                <div v-else class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border border-gray-200">
                                                    <span class="text-sm font-bold text-blue-600">
                                                        {{ event.organizer?.name?.charAt(0)?.toUpperCase() || 'O' }}
                                                    </span>
                                                </div>
                                                <span>{{ event.organizer?.name || 'Не указан' }}</span>
                                            </div>
                                        </td>

                                        <!-- Дата -->
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            {{ new Date(event.start_time).toLocaleString('ru-RU') }}
                                        </td>

                                        <!-- Участники -->
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1">
                                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                    Регистрации: {{ event.registrations_count || event.registrations?.length || 0 }} / {{ event.max_attendees }}
                                                </span>
                                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                    Посетили: {{ event.attendances_count || 0 }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Статус -->
                                        <td class="px-6 py-4">
                                            <span v-if="event.is_completed" class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800 border border-blue-200">
                                                Завершено
                                            </span>
                                            <span v-else class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                Одобрено
                                            </span>
                                        </td>

                                        <!-- Действия -->
                                        <td class="px-6 py-4 text-sm font-medium">
                                            <div class="flex flex-wrap gap-2">
                                                <Link
                                                    :href="route('admin.events.report', event.id)"
                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Подробнее
                                                </Link>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="events.data.length > 0" class="mt-8 flex justify-center items-center gap-4">
                        <Link
                            v-if="events.prev_page_url"
                            :href="events.prev_page_url"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            Назад
                        </Link>
                        <span v-else class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </Link>
                        <span v-else class="inline-flex items-center gap-2 px-6 py-3 bg-gray-100 text-gray-400 rounded-xl font-medium">
                            Вперед
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white border border-gray-200 rounded-2xl shadow-lg p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Мероприятия не найдены</h3>
                        <p class="text-gray-600">На данный момент нет мероприятий для отображения.</p>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>