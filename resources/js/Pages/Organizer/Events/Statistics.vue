<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

defineProps({
    events: Object,
});
</script>

<template>
    <Head title="Статистика мероприятий" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div class="bg-white/80 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8">
                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-10 gap-6">
                            <div class="flex items-center gap-4">
                                <Link
                                    :href="route('dashboard.organizer')"
                                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Назад
                                </Link>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900">Статистика мероприятий</h2>
                                    <p class="text-gray-600 mt-2 text-lg">Аналитика завершенных мероприятий</p>
                                </div>
                            </div>
                        </div>

                        <!-- Events List -->
                        <div v-if="events.data.length > 0" class="space-y-6">
                            <div
                                v-for="event in events.data"
                                :key="event.id"
                                class="bg-white border border-gray-200 rounded-2xl p-8 hover:bg-blue-50/50 transition-all duration-300 shadow-lg hover:shadow-xl group"
                            >
                                <div class="flex flex-col lg:flex-row gap-8">
                                    <!-- Event Photo -->
                                    <div class="flex-shrink-0">
                                        <div v-if="event.photo_path" class="w-40 h-40 rounded-2xl overflow-hidden border-2 border-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <img
                                                :src="`/storage/${event.photo_path}`"
                                                :alt="event.name"
                                                class="w-full h-full object-cover"
                                                loading="lazy"
                                                width="160"
                                                height="160"
                                            >
                                        </div>
                                        <div v-else class="w-40 h-40 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center border-2 border-white shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <svg class="w-12 h-12 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Event Details -->
                                    <div class="flex-1">
                                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-6">
                                            <div class="flex-1">
                                                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ event.name }}</h3>
                                                <p class="text-gray-600 text-lg mb-6">{{ event.description }}</p>
                                                
                                                <!-- Event Info -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shadow-sm border border-blue-200">
                                                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm text-gray-500">Дата и время</div>
                                                            <div class="font-semibold text-gray-900">{{ new Date(event.start_time).toLocaleString() }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shadow-sm border border-green-200">
                                                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm text-gray-500">Продолжительность</div>
                                                            <div class="font-semibold text-gray-900">{{ event.duration }} минут</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center shadow-sm border border-purple-200">
                                                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm text-gray-500">Участники</div>
                                                            <div class="font-semibold text-gray-900">{{ event.attendees_count }} / {{ event.max_attendees }}</div>
                                                        </div>
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center shadow-sm border border-orange-200">
                                                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <div class="text-sm text-gray-500">Оценки</div>
                                                            <div class="font-semibold text-gray-900">{{ event.likes_count }} 👍 / {{ event.dislikes_count }} 👎</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Actions -->
                                            <div class="flex flex-wrap gap-3 lg:justify-end">
                                                <Link
                                                    :href="route('organizer.events.report', event.id)"
                                                    class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-700 text-white hover:from-purple-700 hover:to-indigo-800 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 group"
                                                >
                                                    <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Подробный отчет
                                                </Link>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-12 flex flex-col sm:flex-row justify-between items-center gap-6">
                                <div class="flex items-center gap-4">
                                    <div v-if="events.prev_page_url">
                                        <Link
                                            :href="events.prev_page_url"
                                            class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                        >
                                            <svg class="w-5 h-5 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Назад
                                        </Link>
                                    </div>
                                    <div v-else>
                                        <span class="px-6 py-3 bg-gray-100 text-gray-400 rounded-xl border border-gray-200 cursor-not-allowed flex items-center gap-3">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                            Назад
                                        </span>
                                    </div>

                                    <div class="text-lg font-medium text-gray-700">
                                        Страница <span class="font-bold text-blue-600">{{ events.current_page }}</span> из <span class="font-bold text-gray-900">{{ events.last_page }}</span>
                                    </div>
                                </div>

                                <div v-if="events.next_page_url">
                                    <Link
                                        :href="events.next_page_url"
                                        class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                    >
                                        Вперед
                                        <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </Link>
                                </div>
                                <div v-else>
                                    <span class="px-6 py-3 bg-gray-100 text-gray-400 rounded-xl border border-gray-200 cursor-not-allowed flex items-center gap-3">
                                        Вперед
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Empty State -->
                        <div v-else class="text-center py-20">
                            <div class="max-w-md mx-auto">
                                <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center shadow-lg">
                                    <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">У вас пока нет завершенных мероприятий</h3>
                                <p class="text-gray-600 text-lg mb-8">Завершите мероприятия, чтобы увидеть здесь статистику</p>
                                <Link
                                    :href="route('organizer.events.index')"
                                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 rounded-xl font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl inline-flex items-center gap-3 group transform hover:-translate-y-1"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    Перейти к мероприятиям
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>