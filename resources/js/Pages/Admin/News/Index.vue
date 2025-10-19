<script setup>
import { ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const props = defineProps({
    news: Object,
});

const deleteNews = (id) => {
    if (confirm('Вы уверены, что хотите удалить эту новость?')) {
        router.delete(route('admin.news.destroy', id));
    }
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
    <Head title="Управление новостями" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Управление новостями</h1>
                        <p class="text-lg text-gray-600">Создание и редактирование новостей</p>
                    </div>
                    <Link 
                        :href="route('admin.news.create')"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Создать новость
                    </Link>
                </div>

                <!-- News List -->
                <div v-if="news.data.length > 0" class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-xl font-bold text-gray-900">Список новостей</h3>
                        <p class="text-gray-600 text-sm">Все созданные новости</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Новость</th>
                                    <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Статус</th>
                                    <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Дата создания</th>
                                    <th scope="col" class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Действия</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-for="item in news.data" :key="item.id" class="hover:bg-gray-50 transition-colors duration-200">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div v-if="item.photo_path" class="flex-shrink-0 h-12 w-12 rounded-lg overflow-hidden mr-3 border border-gray-200">
                                                <img
                                                    :src="getImageUrl(item.photo_path)"
                                                    :alt="item.title"
                                                    class="h-full w-full object-cover"
                                                    @error="(e) => { e.target.style.display = 'none'; }"
                                                >
                                            </div>
                                            <div v-else class="flex-shrink-0 h-12 w-12 bg-gray-100 rounded-lg flex items-center justify-center mr-3 border border-gray-200">
                                                <svg class="h-6 w-6 text-gray-500" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                                </svg>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-semibold text-gray-900">{{ item.title }}</div>
                                                <div class="text-sm text-gray-600 truncate max-w-xs">{{ item.description }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span 
                                            :class="[
                                                'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border',
                                                item.is_active ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200'
                                            ]"
                                        >
                                            {{ item.is_active ? 'Активна' : 'Неактивна' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ new Date(item.created_at).toLocaleDateString('ru-RU') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center gap-3">
                                            <Link
                                                :href="route('admin.news.edit', item.id)"
                                                class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-800 font-medium transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                                                </svg>
                                                Редактировать
                                            </Link>
                                            <button
                                                @click="deleteNews(item.id)"
                                                class="inline-flex items-center gap-1 text-red-600 hover:text-red-800 font-medium transition-colors"
                                            >
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                </svg>
                                                Удалить
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div v-if="news.links.length > 3" class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-700">
                                Показано <span class="font-medium">{{ news.from }}</span> до <span class="font-medium">{{ news.to }}</span> из <span class="font-medium">{{ news.total }}</span> результатов
                            </div>
                            <div class="flex gap-2">
                                <template v-for="(link, index) in news.links" :key="index">
                                    <div v-if="link.url === null" class="px-3 py-1 text-gray-400 rounded-md">
                                        {{ link.label }}
                                    </div>
                                    <Link 
                                        v-else 
                                        :href="link.url" 
                                        class="px-3 py-1 rounded-md transition-all duration-200"
                                        :class="link.active ? 'bg-blue-600 text-white shadow-sm' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-300'"
                                        v-html="link.label"
                                    />
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-else class="bg-white border border-gray-200 rounded-2xl shadow-lg p-12 text-center">
                    <div class="max-w-md mx-auto">
                        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Новости не найдены</h3>
                        <p class="text-gray-600 mb-6">Создайте первую новость, чтобы начать</p>
                        <Link
                            :href="route('admin.news.create')"
                            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-xl transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                            </svg>
                            Создать новость
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>