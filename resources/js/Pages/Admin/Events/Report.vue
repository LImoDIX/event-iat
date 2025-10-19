<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const props = defineProps({
    event: Object,
    attendedRegistrations: Array,
    nonAttendedRegistrations: Array,
});

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
    <Head title="Отчет по мероприятию" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <!-- Заголовок -->
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-8">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Отчет по мероприятию</h2>
                                <p class="text-gray-600 mt-2">Детальная статистика и список участников</p>
                            </div>
                            <Link
                                :href="route('admin.events.index')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Назад к списку
                            </Link>
                        </div>

                        <!-- Информация о мероприятии -->
                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-8 mb-8">
                            <div class="flex flex-col md:flex-row gap-8">
                                <!-- Фотография мероприятия -->
                                <div class="flex-shrink-0">
                                    <div v-if="event?.photo_path" class="w-64 h-64 rounded-2xl overflow-hidden border border-gray-200">
                                        <img
                                            :src="getImageUrl(event.photo_path)"
                                            :alt="event.name || 'Мероприятие'"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            width="256"
                                            height="256"
                                            @error="(e) => { e.target.style.display = 'none'; }"
                                        >
                                    </div>
                                    <div v-else class="w-64 h-64 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-200">
                                        <svg class="w-16 h-16 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                        </svg>
                                    </div>
                                </div>

                                <!-- Детали мероприятия -->
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6">{{ event?.name || 'Без названия' }}</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                                            <div class="text-gray-600 text-sm mb-1">Организатор</div>
                                            <div class="text-gray-900 font-semibold">{{ event.organizer?.name || 'Не указан' }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                                            <div class="text-gray-600 text-sm mb-1">Дата и время</div>
                                            <div class="text-gray-900 font-semibold">{{ event.start_time ? new Date(event.start_time).toLocaleString('ru-RU') : 'Не указана' }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                                            <div class="text-gray-600 text-sm mb-1">Длительность</div>
                                            <div class="text-gray-900 font-semibold">{{ event.duration || 0 }} минут</div>
                                        </div>
                                        
                                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                                            <div class="text-gray-600 text-sm mb-1">Максимум участников</div>
                                            <div class="text-gray-900 font-semibold">{{ event.max_attendees || 0 }}</div>
                                        </div>
                                        
                                        <div class="bg-white p-4 rounded-xl border border-gray-200">
                                            <div class="text-gray-600 text-sm mb-1">Зарегистрировано</div>
                                            <div class="text-gray-900 font-semibold">{{ event.registrations_count || (event.registrations ? event.registrations.length : 0) || 0 }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-white p-4 rounded-xl border border-gray-200">
                                        <div class="text-gray-600 text-sm mb-2">Описание</div>
                                        <div class="text-gray-900">{{ event.description || 'Нет описания' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Статистика посещения -->
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-green-700 mb-2">
                                    {{ attendedRegistrations.length }}
                                </div>
                                <div class="text-green-700 font-medium">Посетили</div>
                            </div>
                            
                            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-red-700 mb-2">
                                    {{ nonAttendedRegistrations.length }}
                                </div>
                                <div class="text-red-700 font-medium">Не пришли</div>
                            </div>
                            
                            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-blue-700 mb-2">
                                    {{ attendedRegistrations.length + nonAttendedRegistrations.length }}
                                </div>
                                <div class="text-blue-700 font-medium">Всего зарегистрировано</div>
                            </div>
                            
                            <div class="bg-purple-50 border border-purple-200 rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-purple-700 mb-2">
                                    {{ event.likes_count || 0 }}
                                </div>
                                <div class="text-purple-700 font-medium">Положительных оценок</div>
                            </div>
                            
                            <div class="bg-pink-50 border border-pink-200 rounded-2xl p-6 text-center">
                                <div class="text-3xl font-bold text-pink-700 mb-2">
                                    {{ event.dislikes_count || 0 }}
                                </div>
                                <div class="text-pink-700 font-medium">Отрицательных оценок</div>
                            </div>
                        </div>

                        <!-- Список посетителей -->
                        <div class="space-y-8">
                            <!-- Посетили мероприятие -->
                            <div v-if="attendedRegistrations.length > 0" class="bg-white border border-gray-200 rounded-2xl p-6">
                                <h4 class="text-xl font-bold text-green-700 mb-6 flex items-center gap-3">
                                    <div class="p-2 bg-green-100 rounded-lg">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    Посетили мероприятие ({{ attendedRegistrations.length }})
                                </h4>
                                
                                <div class="overflow-x-auto rounded-xl border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Пользователь</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Дата регистрации</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="registration in attendedRegistrations" :key="registration.id" 
                                                class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-3">
                                                        <div v-if="registration.user?.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                                            <img
                                                                :src="getImageUrl(registration.user.profile_photo_path)"
                                                                :alt="registration.user.name"
                                                                class="h-full w-full object-cover"
                                                                loading="lazy"
                                                                width="40"
                                                                height="40"
                                                                @error="(e) => { e.target.style.display = 'none'; }"
                                                            >
                                                        </div>
                                                        <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border border-gray-200">
                                                            <span class="text-sm font-bold text-blue-600">
                                                                {{ registration.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                                            </span>
                                                        </div>
                                                        <div class="font-medium text-gray-900">{{ registration.user?.name || 'Не указано' }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    {{ registration.user?.email || 'Не указан' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    {{ registration.created_at ? new Date(registration.created_at).toLocaleString('ru-RU') : 'Не указана' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Не пришли на мероприятие -->
                            <div v-if="nonAttendedRegistrations.length > 0" class="bg-white border border-gray-200 rounded-2xl p-6">
                                <h4 class="text-xl font-bold text-red-700 mb-6 flex items-center gap-3">
                                    <div class="p-2 bg-red-100 rounded-lg">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    Не пришли на мероприятие ({{ nonAttendedRegistrations.length }})
                                </h4>
                                
                                <div class="overflow-x-auto rounded-xl border border-gray-200">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Пользователь</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Email</th>
                                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-900 uppercase tracking-wider">Дата регистрации</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <tr v-for="registration in nonAttendedRegistrations" :key="registration.id" 
                                                class="hover:bg-gray-50 transition-colors">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center gap-3">
                                                        <div v-if="registration.user?.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border border-gray-200">
                                                            <img
                                                                :src="getImageUrl(registration.user.profile_photo_path)"
                                                                :alt="registration.user.name"
                                                                class="h-full w-full object-cover"
                                                                loading="lazy"
                                                                width="40"
                                                                height="40"
                                                                @error="(e) => { e.target.style.display = 'none'; }"
                                                            >
                                                        </div>
                                                        <div v-else class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border border-gray-200">
                                                            <span class="text-sm font-bold text-blue-600">
                                                                {{ registration.user?.name?.charAt(0)?.toUpperCase() || 'U' }}
                                                            </span>
                                                        </div>
                                                        <div class="font-medium text-gray-900">{{ registration.user?.name || 'Не указано' }}</div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    {{ registration.user?.email || 'Не указан' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-gray-900">
                                                    {{ registration.created_at ? new Date(registration.created_at).toLocaleString('ru-RU') : 'Не указана' }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Нет зарегистрированных пользователей -->
                            <div v-if="attendedRegistrations.length === 0 && nonAttendedRegistrations.length === 0" 
                                class="bg-white border border-gray-200 rounded-2xl p-12 text-center">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center border border-gray-200">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2">Нет зарегистрированных пользователей</h3>
                                <p class="text-gray-600">На это мероприятие еще никто не зарегистрировался.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>