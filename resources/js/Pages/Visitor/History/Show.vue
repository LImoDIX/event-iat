<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
    attendance: Object,
});
</script>

<template>
    <Head title="Мероприятие" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center gap-3">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 p-2 rounded-lg shadow-lg">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold tracking-wide">{{ event.name }}</h1>
            </div>
        </template>

        <div class="py-8 px-8">
            <div class="max-w-7xl mx-auto">
                <div class="bg-white border border-gray-300 rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Основная информация -->
                            <div class="lg:col-span-2">
                                <!-- Фотография мероприятия -->
                                <div v-if="event.photo_path" class="mb-6 rounded-xl overflow-hidden">
                                    <img
                                        :src="`/storage/${event.photo_path}`"
                                        :alt="event.name"
                                        class="w-full h-64 object-cover"
                                    >
                                </div>
                                <div v-else class="mb-6 h-64 bg-gradient-to-r from-purple-900/20 to-pink-900/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-20 h-20 text-gray-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                                
                                <div class="mb-8">
                                    <h3 class="text-xl font-bold text-black mb-4">Описание мероприятия</h3>
                                    <p class="text-gray-600 text-lg leading-relaxed">{{ event.description || 'Описание отсутствует' }}</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 shadow-lg">
                                       <div class="flex items-center mb-3">
                                           <div class="bg-purple-600 p-2 rounded-lg mr-3">
                                               <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                   <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                               </svg>
                                           </div>
                                           <h3 class="text-lg font-semibold text-black">Категория</h3>
                                       </div>
                                   </div>
                                    
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 shadow-lg">
                                        <div class="flex items-center mb-3">
                                            <div class="bg-pink-600 p-2 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-black">Организатор</h3>
                                        </div>
                                        <div class="flex items-center mt-3">
                                            <img
                                                v-if="event.organizer.profile_photo_path"
                                                :src="`/storage/${event.organizer.profile_photo_path}`"
                                                :alt="event.organizer.name"
                                                class="w-12 h-12 rounded-full object-cover mr-3"
                                            >
                                            <div
                                                v-else
                                                class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-600/20 to-pink-600/20 border-2 border-purple-500 flex items-center justify-center mr-3"
                                            >
                                                <span class="text-lg font-bold text-purple-600">
                                                    {{ event.organizer.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                            <span class="text-gray-800 text-lg">{{ event.organizer.name }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 shadow-lg">
                                        <div class="flex items-center mb-3">
                                            <div class="bg-blue-600 p-2 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-black">Дата и время</h3>
                                        </div>
                                        <p class="text-gray-600 text-lg">{{ new Date(event.start_time).toLocaleString() }}</p>
                                    </div>
                                    
                                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 shadow-lg">
                                        <div class="flex items-center mb-3">
                                            <div class="bg-green-600 p-2 rounded-lg mr-3">
                                                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="currentColor">
                                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                                </svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-black">Продолжительность</h3>
                                        </div>
                                        <p class="text-gray-600 text-lg">{{ event.duration }} минут</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Боковая панель с информацией о регистрации -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6 shadow-xl sticky top-8">
                                    <h3 class="text-xl font-bold text-black mb-4">Информация о посещении</h3>
                                    <div class="mb-6">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-600">Статус:</span>
                                            <span class="px-2 py-1 bg-green-500/20 text-green-400 text-xs font-medium rounded-full">Завершено</span>
                                        </div>
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="text-gray-600">Дата посещения:</span>
                                            <span class="text-black font-semibold">{{ new Date(attendance.attended_at).toLocaleString() }}</span>
                                        </div>
                                    </div>
    
                                    <div class="flex flex-col gap-4">
                                        <a
                                            :href="route('visitor.history')"
                                            class="px-6 py-3 border border-gray-300 rounded-xl font-medium text-black hover:bg-gray-100 transition text-center"
                                        >
                                            Назад к истории
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>