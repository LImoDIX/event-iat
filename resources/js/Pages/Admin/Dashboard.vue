<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage, Head } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const page = usePage();
const profilePhoto = ref(page.props.auth?.user?.profile_photo_path || null);
const profilePhotoPreview = ref(null);

const handleProfilePhotoUpdate = (event) => {
    profilePhotoPreview.value = event.detail.photoPath;
    profilePhoto.value = event.detail.photoPath;
};

onMounted(() => {
    window.addEventListener('profile-photo-updated', handleProfilePhotoUpdate);
});

onUnmounted(() => {
    window.removeEventListener('profile-photo-updated', handleProfilePhotoUpdate);
});
</script>

<template>
    <Head title="Панель администратора" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Welcome Section -->
                <div class="mb-12 text-center relative">
                    <!-- Декоративные элементы -->
                    <div class="absolute top-0 left-1/4 w-8 h-8 bg-blue-200 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-0 right-1/3 w-6 h-6 bg-indigo-200 rounded-full animate-pulse delay-500"></div>
                    <div class="absolute top-1/3 right-1/4 w-4 h-4 bg-blue-100 rounded-full animate-pulse delay-700"></div>
                    
                    <div class="mx-auto max-w-3xl">
                        <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6 text-balance text-gray-900">
                            Добро пожаловать,
                            <span class="bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">{{ page.props.auth.user.name }}</span>!
                        </h1>
                        <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto text-pretty leading-relaxed">
                            Обзор статистики и активности системы
                        </p>
                        
                        <!-- Ссылки на новости -->
                        <div class="flex gap-4 justify-center flex-wrap">
                            <Link
                                :href="route('admin.news.index')"
                                class="px-6 py-3 text-lg font-bold rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                                Управление новостями
                            </Link>
                            <Link
                                :href="route('admin.news.create')"
                                class="px-6 py-3 text-lg font-bold rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 hover:border-blue-300 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Создать новость
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <!-- Total Users -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Всего пользователей</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">{{ page.props.stats.totalUsers }}</p>
                    </div>

                    <!-- Total Events -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Всего мероприятий</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">{{ page.props.stats.totalEvents }}</p>
                    </div>

                    <!-- Blocked Users -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Заблокированные</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-red-600 to-pink-600 bg-clip-text text-transparent">{{ page.props.stats.blockedUsers }}</p>
                    </div>

                    <!-- Active Events -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group text-center">
                        <div class="mb-6 transform transition-transform group-hover:scale-110 flex justify-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-600 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold mb-3 text-gray-900">Активные мероприятия</h4>
                        <p class="text-4xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">{{ page.props.stats.activeEvents }}</p>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                    <!-- Recent Users -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-pink-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Новые пользователи</h3>
                                    <p class="text-gray-600">{{ page.props.recentUsers.length }} новых</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div 
                                v-for="user in page.props.recentUsers" 
                                :key="user.id" 
                                class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-200 hover:bg-purple-50 transition-all duration-300 hover:scale-105 group/user"
                            >
                                <div class="relative">
                                    <div v-if="user.profile_photo_path" class="w-14 h-14 rounded-full overflow-hidden border-2 border-gray-300 group-hover/user:border-purple-500 transition-colors">
                                        <img :src="`/storage/${user.profile_photo_path}`" :alt="user.name" class="w-full h-full object-cover">
                                    </div>
                                    <div v-else class="w-14 h-14 rounded-full bg-gradient-to-r from-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xl border-2 border-gray-300 group-hover/user:border-purple-500 transition-colors">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white"
                                         :class="[
                                             user.role === 'admin' ? 'bg-red-500' :
                                             user.role === 'organizer' ? 'bg-blue-500' :
                                             'bg-green-500'
                                         ]">
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-lg text-gray-900 truncate">{{ user.name }}</p>
                                    <p class="text-gray-600 truncate">{{ user.email }}</p>
                                </div>
                                <span
                                    :class="[
                                        'px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-xl border transition-all duration-300',
                                        user.role === 'admin' ? 'bg-red-100 text-red-800 border-red-200 group-hover/user:bg-red-200' :
                                        user.role === 'organizer' ? 'bg-blue-100 text-blue-800 border-blue-200 group-hover/user:bg-blue-200' :
                                        'bg-green-100 text-green-800 border-green-200 group-hover/user:bg-green-200'
                                    ]"
                                >
                                    {{ user.role === 'admin' ? 'Админ' : user.role === 'organizer' ? 'Организатор' : 'Посетитель' }}
                                </span>
                            </div>
                        </div>
                        
                        <Link
                            :href="route('admin.users.index')"
                            class="mt-8 w-full px-6 py-4 rounded-2xl bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-3 group/link"
                        >
                            <span>Посмотреть всех пользователей</span>
                            <svg class="w-5 h-5 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>

                    <!-- Recent Events -->
                    <div class="p-8 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                        <div class="flex items-center justify-between mb-8">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-2xl flex items-center justify-center shadow-lg">
                                    <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">Новые мероприятия</h3>
                                    <p class="text-gray-600">{{ page.props.recentEvents.length }} новых</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="space-y-4">
                            <div 
                                v-for="event in page.props.recentEvents" 
                                :key="event.id" 
                                class="flex items-center gap-4 p-4 rounded-2xl bg-gray-50 border border-gray-200 hover:bg-blue-50 transition-all duration-300 hover:scale-105 group/event"
                            >
                                <div class="relative">
                                    <div v-if="event.photo_path" class="w-14 h-14 rounded-xl overflow-hidden border-2 border-gray-300 group-hover/event:border-blue-500 transition-colors">
                                        <img :src="`/storage/${event.photo_path}`" :alt="event.name" class="w-full h-full object-cover">
                                    </div>
                                    <div v-else class="w-14 h-14 rounded-xl bg-gradient-to-r from-blue-600 to-cyan-600 flex items-center justify-center border-2 border-gray-300 group-hover/event:border-blue-500 transition-colors">
                                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-lg text-gray-900 truncate">{{ event.name }}</p>
                                    <p class="text-gray-600 truncate">Организатор: {{ event.organizer.name }}</p>
                                </div>
                                <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-xl border bg-green-100 text-green-800 border-green-200 group-hover/event:bg-green-200 transition-all duration-300">
                                    Одобрено
                                </span>
                            </div>
                        </div>
                        
                        <Link
                            :href="route('admin.events.index')"
                            class="mt-8 w-full px-6 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-cyan-600 text-white font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center gap-3 group/link"
                        >
                            <span>Посмотреть все мероприятия</span>
                            <svg class="w-5 h-5 transform group-hover/link:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>