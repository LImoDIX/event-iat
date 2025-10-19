<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

defineProps({
    users: Object,
    authUser: Object,
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

const confirmDelete = (message) => {
    return confirm(message);
};
</script>

<template>
    <Head title="Управление пользователями" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div class="bg-white/80 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8">
                        <!-- Header -->
                        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between mb-10 gap-6">
                            <div class="flex items-center gap-4">
                                <Link
                                    :href="route('dashboard.admin')"
                                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Назад
                                </Link>
                                <div>
                                    <h2 class="text-3xl font-bold text-gray-900">Управление пользователями</h2>
                                    <p class="text-gray-600 mt-2 text-lg">Всего пользователей: <span class="font-semibold text-blue-600">{{ users.total }}</span></p>
                                </div>
                            </div>
                            <Link
                                :href="route('admin.users.create')"
                                class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 rounded-xl font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl flex items-center gap-3 group transform hover:-translate-y-1"
                            >
                                <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Добавить пользователя
                            </Link>
                        </div>

                        <!-- Users Table -->
                        <div v-if="users.data.length > 0" class="overflow-hidden rounded-2xl border border-gray-200 shadow-lg">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-blue-50 to-indigo-50">
                                    <tr>
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Пользователь</th>
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Логин</th>
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Роль</th>
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Статус</th>
                                        <th class="px-8 py-5 text-left text-sm font-bold text-gray-900 uppercase tracking-wider">Действия</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr v-for="user in users.data" :key="user.id" class="hover:bg-blue-50/50 transition-all duration-300 group">
                                        <!-- User Info -->
                                        <td class="px-8 py-6">
                                            <div class="flex items-center gap-4">
                                                <div class="relative">
                                                    <div v-if="user.profile_photo_path" class="w-12 h-12 rounded-2xl overflow-hidden border-2 border-white shadow-lg group-hover:scale-110 transition-transform duration-300">
                                                        <img
                                                            :src="getImageUrl(user.profile_photo_path)"
                                                            :alt="user.name"
                                                            class="w-full h-full object-cover"
                                                            @error="(e) => { e.target.style.display = 'none'; }"
                                                            loading="lazy"
                                                            width="48"
                                                            height="48"
                                                        >
                                                    </div>
                                                    <div v-else class="w-12 h-12 rounded-2xl bg-gradient-to-r from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-lg shadow-lg group-hover:scale-110 transition-transform duration-300 border-2 border-white">
                                                        {{ user.name.charAt(0).toUpperCase() }}
                                                    </div>
                                                    <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-lg"
                                                         :class="user.is_blocked ? 'bg-red-500' : 'bg-green-500'">
                                                    </div>
                                                </div>
                                                <div>
                                                    <span class="text-lg font-semibold text-gray-900 block">{{ user.name }}</span>
                                                    <span class="text-sm text-gray-500">ID: {{ user.id }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <!-- Username -->
                                        <td class="px-8 py-6">
                                            <span class="text-lg font-medium text-gray-700">@{{ user.username }}</span>
                                        </td>
                                        
                                        <!-- Role -->
                                        <td class="px-8 py-6">
                                            <span
                                                :class="[
                                                    'px-4 py-2 inline-flex text-sm font-bold rounded-xl border shadow-sm transition-all duration-300 group-hover:scale-105',
                                                    user.role === 'admin' ? 'bg-red-100 text-red-800 border-red-200' :
                                                    user.role === 'organizer' ? 'bg-blue-100 text-blue-800 border-blue-200' :
                                                    'bg-green-100 text-green-800 border-green-200'
                                                ]"
                                            >
                                                {{ user.role === 'admin' ? 'Администратор' : user.role === 'organizer' ? 'Организатор' : 'Посетитель' }}
                                            </span>
                                        </td>
                                        
                                        <!-- Status -->
                                        <td class="px-8 py-6">
                                            <span
                                                :class="[
                                                    'px-4 py-2 inline-flex text-sm font-bold rounded-xl border shadow-sm transition-all duration-300 group-hover:scale-105',
                                                    user.is_blocked ? 'bg-red-100 text-red-800 border-red-200' : 'bg-green-100 text-green-800 border-green-200'
                                                ]"
                                            >
                                                {{ user.is_blocked ? 'Заблокирован' : 'Активен' }}
                                            </span>
                                        </td>
                                        
                                        <!-- Actions -->
                                        <td class="px-8 py-6">
                                            <div v-if="authUser && authUser.id !== user.id" class="flex flex-wrap gap-3">
                                                <Link
                                                    :href="route('admin.users.edit', user.id)"
                                                    class="px-4 py-2 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-2 border border-gray-200 group/btn"
                                                >
                                                    <svg class="w-4 h-4 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                    Редакт.
                                                </Link>
                                                <Link
                                                    v-if="!user.is_blocked"
                                                    :href="route('admin.users.toggle-block', user.id)"
                                                    method="post"
                                                    as="button"
                                                    type="button"
                                                    class="px-4 py-2 bg-yellow-500/10 hover:bg-yellow-500/20 text-yellow-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-2 border border-yellow-200 group/btn"
                                                >
                                                    <svg class="w-4 h-4 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                    </svg>
                                                    Блок.
                                                </Link>
                                                <Link
                                                    v-else
                                                    :href="route('admin.users.toggle-block', user.id)"
                                                    method="post"
                                                    as="button"
                                                    type="button"
                                                    class="px-4 py-2 bg-green-500/10 hover:bg-green-500/20 text-green-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-2 border border-green-200 group/btn"
                                                >
                                                    <svg class="w-4 h-4 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                                    </svg>
                                                    Разблок.
                                                </Link>
                                                <Link
                                                    :href="route('admin.users.destroy', user.id)"
                                                    method="delete"
                                                    as="button"
                                                    type="button"
                                                    class="px-4 py-2 bg-red-500/10 hover:bg-red-500/20 text-red-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-2 border border-red-200 group/btn"
                                                    @click="confirmDelete('Вы уверены, что хотите удалить этого пользователя?')"
                                                >
                                                    <svg class="w-4 h-4 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                    Удалить
                                                </Link>
                                            </div>
                                            <div v-else class="text-gray-500 italic">
                                                Недоступно
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- No users -->
                        <div v-else class="text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center shadow-lg">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Пользователи не найдены</h3>
                                <p class="text-gray-600 mb-8 text-lg">Начните с добавления первого пользователя</p>
                                <Link
                                    :href="route('admin.users.create')"
                                    class="px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 rounded-xl font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl inline-flex items-center gap-3 group transform hover:-translate-y-1"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Добавить пользователя
                                </Link>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="users.data.length > 0" class="mt-12 flex flex-col sm:flex-row justify-between items-center gap-6">
                            <div class="flex items-center gap-4">
                                <div v-if="users.prev_page_url">
                                    <Link
                                        :href="users.prev_page_url"
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
                                    Страница <span class="font-bold text-blue-600">{{ users.current_page }}</span> из <span class="font-bold text-gray-900">{{ users.last_page }}</span>
                                </div>
                            </div>

                            <div v-if="users.next_page_url">
                                <Link
                                    :href="users.next_page_url"
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
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>