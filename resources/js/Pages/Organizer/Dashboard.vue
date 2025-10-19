<script setup>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';
import NewsBanner from '@/Components/NewsBanner.vue';

const props = defineProps({
    events: Object,
    news: Array,
});
</script>

<template>
    <Head title="Панель организатора" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- News Banner -->
                <NewsBanner v-if="news && news.length > 0" :news="news" />
                
                <!-- Welcome Section -->
                <div class="mb-12 text-center relative">
                    <!-- Декоративные элементы -->
                    <div class="absolute top-0 left-1/4 w-8 h-8 bg-blue-200 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-0 right-1/3 w-6 h-6 bg-indigo-200 rounded-full animate-pulse delay-500"></div>
                    <div class="absolute top-1/3 right-1/4 w-4 h-4 bg-blue-100 rounded-full animate-pulse delay-700"></div>
                    
                    <div class="mx-auto max-w-3xl">
                        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6 text-balance text-gray-900">
                            Добро пожаловать,
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">{{ $page.props.auth.user.name }}</span>!
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto text-pretty leading-relaxed">
                            Обзор ваших мероприятий и статистика
                        </p>
                    </div>
                </div>
                
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                    <!-- Total Events -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Всего мероприятий</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ events?.data?.length || 0 }}</p>
                    </div>
                    
                    <!-- Total Participants -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Участников всего</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                                            {{ events?.data?.reduce((sum, e) => sum + (e.registrations?.length || 0), 0) }}
                                        </p>
                    </div>
                    
                    <!-- Next Event -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Ближайшее событие</h4>
                        <p class="text-2xl font-bold text-gray-900 truncate">
                            {{ events?.data?.[0]?.name || 'Нет событий' }}
                        </p>
                        <div class="mt-2 text-gray-600">
                            <span v-if="events?.data?.[0]">
                                {{ new Date(events.data[0].start_time).toLocaleDateString() }}
                            </span>
                            <span v-else>—</span>
                        </div>
                    </div>
                </div>

                <!-- Recent Events -->
                <div v-if="events?.data?.length > 0" class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900">Последние мероприятия</h3>
                                <p class="text-gray-600">Ваши недавно созданные мероприятия</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div 
                            v-for="event in events.data.slice(0, 5)" 
                            :key="event.id" 
                            class="flex items-center gap-6 p-6 rounded-2xl bg-gray-50 border border-gray-200 hover:bg-indigo-50 transition-all duration-300 hover:scale-105 group/event"
                        >
                            <div class="relative">
                                <div v-if="event.photo_path" class="w-16 h-16 rounded-xl overflow-hidden border-2 border-gray-300 group-hover/event:border-indigo-500 transition-colors">
                                    <img
                                        :src="`/storage/${event.photo_path}`"
                                        :alt="event.name"
                                        class="w-full h-full object-cover"
                                        loading="lazy"
                                        width="64"
                                        height="64"
                                    >
                                </div>
                                <div v-else class="w-16 h-16 rounded-xl bg-gradient-to-r from-indigo-600 to-purple-600 flex items-center justify-center border-2 border-gray-300 group-hover/event:border-indigo-500 transition-colors">
                                    <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-lg text-gray-900 truncate">{{ event.name }}</p>
                                <p class="text-gray-600 truncate">{{ event.description }}</p>
                                <div class="flex items-center gap-4 mt-2 text-sm text-gray-500">
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ new Date(event.start_time).toLocaleDateString() }}
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        {{ new Date(event.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="text-center">
                                    <div class="text-xl font-bold text-gray-900">{{ event.registrations?.length || 0 }}</div>
                                    <div class="text-sm text-gray-600">Регистрации</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-xl font-bold text-gray-900">{{ event.attendances?.length || 0 }}</div>
                                    <div class="text-sm text-gray-600">Посещения</div>
                                </div>
                            </div>
                            
                            <div class="flex flex-col gap-2">
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-xl border bg-green-100 text-green-800 border-green-200 group-hover/event:bg-green-200 transition-all duration-300">
                                    Активное
                                </span>
                                <Link
                                    v-if="!event.is_completed"
                                    :href="route('organizer.events.mark-attendance', event.id)"
                                    class="px-4 py-2 text-sm font-medium rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl transform text-center"
                                >
                                    Отметить
                                </Link>
                                <span v-else class="px-4 py-2 text-sm text-gray-500 font-medium text-center">
                                    Завершено
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <Link
                        :href="route('organizer.events.index')"
                        class="mt-8 w-full px-6 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-3 group/link"
                    >
                        <span>Просмотреть все мероприятия</span>
                        <svg class="w-5 h-5 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </Link>
                </div>
                
                <!-- Empty State -->
                <div v-else class="p-12 rounded-3xl bg-white border border-gray-200 shadow-lg text-center hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="max-w-md mx-auto">
                        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br from-gray-100 to-gray-200 rounded-2xl flex items-center justify-center border border-gray-300 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-10 h-10 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-3">У вас пока нет мероприятий</h3>
                        <p class="text-gray-600 mb-8">Начните с создания вашего первого мероприятия</p>
                        <Link
                            :href="route('organizer.events.create')"
                            class="px-8 py-4 text-lg font-bold rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 inline-flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Создать мероприятие
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>