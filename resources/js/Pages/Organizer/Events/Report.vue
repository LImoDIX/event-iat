<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

defineProps({
    event: Object,
    attendedRegistrations: Array,
    nonAttendedRegistrations: Array,
    likesCount: Number,
    dislikesCount: Number,
});
</script>

<template>
    <Head title="Отчет по мероприятию" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div class="bg-white/80 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8">
                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-10">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Отчет по мероприятию</h2>
                                <p class="text-gray-600 mt-2 text-lg">Детальная статистика посещаемости</p>
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <Link
                                    :href="route('organizer.events.index')"
                                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Назад
                                </Link>
                            </div>
                        </div>

                        <!-- Event Summary -->
                        <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-8 mb-10 shadow-lg">
                            <div class="flex flex-col lg:flex-row lg:items-start gap-8">
                                <div class="flex-shrink-0">
                                    <div v-if="event.photo_path" class="w-40 h-40 rounded-2xl overflow-hidden border-2 border-white shadow-lg">
                                        <img
                                            :src="`/storage/${event.photo_path}`"
                                            :alt="event.name"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            width="160"
                                            height="160"
                                        >
                                    </div>
                                    <div v-else class="w-40 h-40 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-2xl flex items-center justify-center border-2 border-white shadow-lg">
                                        <svg class="w-12 h-12 text-blue-600" viewBox="0 0 24 24" fill="currentColor">
                                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ event.name }}</h3>
                                    <p class="text-gray-600 text-lg mb-6">{{ event.description }}</p>
                                    
                                    <!-- Stats Grid -->
                                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                        <div class="text-center p-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-xl font-bold text-gray-900 mb-1">{{ new Date(event.start_time).toLocaleDateString() }}</div>
                                            <div class="text-sm text-gray-500">Дата</div>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-xl font-bold text-gray-900 mb-1">{{ event.duration }}</div>
                                            <div class="text-sm text-gray-500">Минут</div>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-xl font-bold text-gray-900 mb-1">{{ event.registrations.length }}/{{ event.max_attendees }}</div>
                                            <div class="text-sm text-gray-500">Участники</div>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded-xl border border-green-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-xl font-bold text-green-600 mb-1">{{ likesCount }}</div>
                                            <div class="text-sm text-gray-500">Положительные</div>
                                        </div>
                                        <div class="text-center p-4 bg-white rounded-xl border border-red-200 shadow-sm hover:shadow-md transition-shadow">
                                            <div class="text-xl font-bold text-red-600 mb-1">{{ dislikesCount }}</div>
                                            <div class="text-sm text-gray-500">Отрицательные</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                            <div class="text-center p-8 bg-green-50 border border-green-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-5xl font-bold text-green-600 mb-3">{{ attendedRegistrations.length }}</div>
                                <div class="text-green-700 font-semibold text-xl">Посетили мероприятие</div>
                            </div>
                            <div class="text-center p-8 bg-red-50 border border-red-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-5xl font-bold text-red-600 mb-3">{{ nonAttendedRegistrations.length }}</div>
                                <div class="text-red-700 font-semibold text-xl">Не посетили</div>
                            </div>
                            <div class="text-center p-8 bg-blue-50 border border-blue-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-5xl font-bold text-blue-600 mb-3">{{ event.registrations.length }}</div>
                                <div class="text-blue-700 font-semibold text-xl">Всего зарегистрировано</div>
                            </div>
                        </div>

                        <!-- Attended Registrations -->
                        <div class="mb-12">
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center shadow-sm border border-green-200">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Посетили мероприятие</h3>
                                    <p class="text-gray-600 text-lg">{{ attendedRegistrations.length }} участников</p>
                                </div>
                            </div>
                            
                            <div v-if="attendedRegistrations.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div
                                    v-for="registration in attendedRegistrations"
                                    :key="registration.id"
                                    class="p-6 bg-white border border-gray-200 rounded-2xl hover:bg-green-50/50 transition-all duration-300 shadow-lg hover:shadow-xl group"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div v-if="registration.user.profile_photo_path" class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <img
                                                    :src="`/storage/${registration.user.profile_photo_path}`"
                                                    :alt="registration.user.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="56"
                                                    height="56"
                                                >
                                            </div>
                                            <div v-else class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300 border-2 border-white">
                                                {{ registration.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white bg-green-500 shadow-lg"></div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900">{{ registration.user.name }}</h4>
                                            <p class="text-gray-600">{{ registration.user.email }}</p>
                                            <p class="text-gray-500 mt-2 font-medium">
                                                Код: <span class="font-bold text-blue-600">{{ registration.confirmation_code }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-200">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-300">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600 text-lg">Никто не посетил мероприятие</p>
                            </div>
                        </div>

                        <!-- Non-Attended Registrations -->
                        <div>
                            <div class="flex items-center gap-4 mb-6">
                                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center shadow-sm border border-red-200">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Не посетили мероприятие</h3>
                                    <p class="text-gray-600 text-lg">{{ nonAttendedRegistrations.length }} участников</p>
                                </div>
                            </div>
                            
                            <div v-if="nonAttendedRegistrations.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div
                                    v-for="registration in nonAttendedRegistrations"
                                    :key="registration.id"
                                    class="p-6 bg-white border border-gray-200 rounded-2xl hover:bg-red-50/50 transition-all duration-300 shadow-lg hover:shadow-xl group"
                                >
                                    <div class="flex items-center gap-4">
                                        <div class="relative">
                                            <div v-if="registration.user.profile_photo_path" class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                <img
                                                    :src="`/storage/${registration.user.profile_photo_path}`"
                                                    :alt="registration.user.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="56"
                                                    height="56"
                                                >
                                            </div>
                                            <div v-else class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300 border-2 border-white">
                                                {{ registration.user.name.charAt(0).toUpperCase() }}
                                            </div>
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white bg-red-500 shadow-lg"></div>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold text-gray-900">{{ registration.user.name }}</h4>
                                            <p class="text-gray-600">{{ registration.user.email }}</p>
                                            <p class="text-gray-500 mt-2 font-medium">
                                                Код: <span class="font-bold text-blue-600">{{ registration.confirmation_code }}</span>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else class="text-center py-12 bg-gray-50 rounded-2xl border border-gray-200">
                                <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 rounded-2xl flex items-center justify-center border border-gray-300">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <p class="text-gray-600 text-lg">Все зарегистрированные участники посетили мероприятие</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>