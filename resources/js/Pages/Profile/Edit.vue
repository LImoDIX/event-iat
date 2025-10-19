<script setup>
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    updateRoute: {
        type: String,
        default: 'profile.update'
    }
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

const activeTab = ref('profile');
</script>

<template>
    <Head title="Редактирование профиля" />

    <AdminOrganizerLayout>
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h2 class="text-3xl font-bold text-gray-900">Профиль пользователя</h2>
                                <p class="text-gray-600 mt-2">Управление настройками вашего профиля</p>
                            </div>
                            <Link
                                :href="route($page.props.auth.user.role === 'admin' ? 'dashboard.admin' : 'dashboard.organizer')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                                </svg>
                                На главную
                            </Link>
                        </div>
                        
                        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                            <!-- Левая колонка - информация о пользователе -->
                            <div class="lg:col-span-1">
                                <div class="bg-gray-50 border border-gray-200 rounded-2xl p-6">
                                    <div class="flex flex-col items-center">
                                        <div class="relative">
                                            <img
                                                v-if="$page.props.auth.user.profile_photo_path"
                                                :src="getImageUrl($page.props.auth.user.profile_photo_path)"
                                                class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-lg"
                                                alt="Фото профиля"
                                                loading="lazy"
                                                width="128"
                                                height="128"
                                                @error="(e) => { e.target.style.display = 'none'; }"
                                            />
                                            <div
                                                v-else
                                                class="h-32 w-32 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center shadow-lg border-4 border-white"
                                            >
                                                <span class="text-4xl font-bold text-blue-600">
                                                    {{ $page.props.auth.user.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                            <div class="absolute bottom-2 right-2 bg-blue-600 rounded-full p-2 border-2 border-white shadow-sm">
                                                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="mt-6 text-center">
                                            <h2 class="text-2xl font-bold text-gray-900">{{ $page.props.auth.user.name }}</h2>
                                            <p class="mt-2 text-gray-600">{{ $page.props.auth.user.email }}</p>
                                            <div class="mt-4">
                                                <span
                                                    :class="[
                                                        'inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border shadow-sm',
                                                        $page.props.auth.user.role === 'admin'
                                                            ? 'bg-red-100 text-red-800 border-red-200'
                                                            : $page.props.auth.user.role === 'organizer'
                                                            ? 'bg-blue-100 text-blue-800 border-blue-200'
                                                            : 'bg-green-100 text-green-800 border-green-200'
                                                    ]"
                                                >
                                                    <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                    </svg>
                                                    {{ $page.props.auth.user.role === 'admin' ? 'Администратор' : $page.props.auth.user.role === 'organizer' ? 'Организатор' : 'Посетитель' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-8 w-full">
                                            <nav class="space-y-2">
                                                <button
                                                    @click="activeTab = 'profile'"
                                                    :class="[
                                                        'w-full flex items-center gap-3 px-4 py-3 rounded-xl text-left transition-all duration-200 border',
                                                        activeTab === 'profile'
                                                            ? 'bg-blue-50 text-blue-700 border-blue-200 shadow-sm'
                                                            : 'text-gray-600 hover:text-gray-900 hover:bg-white border-transparent'
                                                    ]"
                                                >
                                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                    </svg>
                                                    <span class="font-medium">Информация профиля</span>
                                                </button>
                                            </nav>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Правая колонка - содержимое профиля -->
                            <div class="lg:col-span-3">
                                <!-- Форма обновления профиля -->
                                <div v-show="activeTab === 'profile'" class="bg-white border border-gray-200 rounded-2xl p-8 transition-all duration-300">
                                    <div class="flex items-center gap-4 mb-6">
                                        <div class="p-3 bg-blue-100 rounded-xl">
                                            <svg class="h-7 w-7 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <h2 class="text-2xl font-bold text-gray-900">Информация профиля</h2>
                                            <p class="text-gray-600 mt-1">
                                                Обновите информацию профиля вашей учетной записи и адрес электронной почты
                                            </p>
                                        </div>
                                    </div>
                                    <div v-if="$page.props.auth.user.role === 'admin' || $page.props.auth.user.role === 'organizer'" class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-5 w-5 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <p class="text-yellow-800 text-sm font-medium">
                                                Администраторы и организаторы не могут изменять имя и email.
                                            </p>
                                        </div>
                                    </div>
                                    <UpdateProfileInformationForm
                                        v-else
                                        :must-verify-email="mustVerifyEmail"
                                        :status="status"
                                        :update-route="updateRoute"
                                        class="max-w-3xl"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>