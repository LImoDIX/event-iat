<!-- Profile.vue -->
<script setup>
import UpdateProfilePhotoForm from '@/Pages/Profile/Partials/UpdateProfilePhotoForm.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';

const { props: pageProps } = usePage();

const props = defineProps({
    mustVerifyEmail: Boolean,
    status: String,
    registrations: { type: Object, default: () => ({}) },
    attendances: { type: Object, default: () => ({}) },
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

const profilePhoto = ref(pageProps.auth?.user?.profile_photo_path || null);
const profilePhotoPreview = ref(null);

const activeSection = ref("profile");

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
    <Head title="Профиль" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        <div class="flex">
            <!-- Sidebar Navigation -->
            <aside class="w-80 min-h-screen bg-white/80 backdrop-blur-sm border-r border-blue-200/50 px-8 py-12">
                <div class="flex flex-col gap-12">
                    <!-- Profile Header -->
                    <div class="flex flex-col items-start gap-6">
                        <div class="relative group">
                            <div class="h-24 w-24 rounded-2xl border-2 border-blue-300 flex items-center justify-center overflow-hidden shadow-lg transform transition-all duration-300 group-hover:scale-105 group-hover:shadow-xl">
                                <img
                                    v-if="profilePhotoPreview"
                                    :src="getImageUrl(profilePhotoPreview)"
                                    class="h-full w-full object-cover"
                                    alt="Фото профиля"
                                    @error="(e) => { e.target.style.display = 'none'; }"
                                />
                                <img
                                    v-else-if="$page.props.auth.user.profile_photo_path"
                                    :src="getImageUrl($page.props.auth.user.profile_photo_path)"
                                    class="h-full w-full object-cover"
                                    alt="Фото профиля"
                                    @error="(e) => { e.target.style.display = 'none'; }"
                                />
                                <div
                                    v-else
                                    class="h-full w-full bg-gradient-to-br from-blue-600 to-indigo-700 text-white text-2xl font-bold flex items-center justify-center"
                                >
                                    {{ $page.props.auth.user.name.split(" ").map((n) => n[0]).join("") }}
                                </div>
                            </div>
                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-2 border-white flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <h1 class="text-2xl font-bold text-gray-900">{{ $page.props.auth.user.name }}</h1>
                            <p class="text-gray-600">@{{ $page.props.auth.user.username }}</p>

                            <div class="flex gap-2 mt-2">
                                <span class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white text-xs font-bold px-3 py-1 rounded-full border border-blue-500/30">
                                    Посетитель
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-transparent via-blue-200/50 to-transparent h-px w-full"></div>

                    <!-- Navigation Links -->
                    <nav class="flex flex-col gap-2">
                        <button
                            @click="activeSection = 'profile'"
                            class="flex items-center gap-4 px-4 py-3 text-sm rounded-xl transition-all duration-300 text-left group"
                            :class="activeSection === 'profile' 
                                ? 'bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg' 
                                : 'text-gray-700 hover:bg-blue-50 hover:shadow-md border border-transparent hover:border-blue-200'"
                        >
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="font-medium">Профиль</span>
                        </button>

                        <div class="my-2 bg-gradient-to-r from-transparent via-blue-200/30 to-transparent h-px w-full"></div>

                        <Link 
                            :href="route('visitor.history')" 
                            class="flex items-center gap-4 px-4 py-3 text-sm rounded-xl transition-all duration-300 text-left text-gray-700 hover:bg-blue-50 hover:shadow-md border border-transparent hover:border-blue-200 group"
                        >
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span class="font-medium">История</span>
                        </Link>

                        <a 
                            href="https://lk.irkat.ru/login" 
                            target="_blank" 
                            class="flex items-center gap-4 px-4 py-3 text-sm rounded-xl transition-all duration-300 text-left text-gray-700 hover:bg-blue-50 hover:shadow-md border border-transparent hover:border-blue-200 group"
                        >
                            <div class="w-8 h-8 rounded-lg bg-green-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                </svg>
                            </div>
                            <span class="font-medium">ЛК ИАТ</span>
                        </a>

                        <a 
                            href="https://schedule.irkat.ru/" 
                            target="_blank" 
                            class="flex items-center gap-4 px-4 py-3 text-sm rounded-xl transition-all duration-300 text-left text-gray-700 hover:bg-blue-50 hover:shadow-md border border-transparent hover:border-blue-200 group"
                        >
                            <div class="w-8 h-8 rounded-lg bg-purple-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <span class="font-medium">Расписание</span>
                        </a>

                        <Link 
                            :href="route('dashboard.visitor')" 
                            class="flex items-center gap-4 px-4 py-3 text-sm rounded-xl transition-all duration-300 text-left text-gray-700 hover:bg-blue-50 hover:shadow-md border border-transparent hover:border-blue-200 group"
                        >
                            <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                            </div>
                            <span class="font-medium">Главная</span>
                        </Link>
                    </nav>

                    <!-- Статистика в сайдбаре -->
                    <div class="p-6 rounded-2xl bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-200/50 shadow-lg">
                        <h3 class="text-sm font-bold text-gray-900 mb-4">Ваша активность</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Регистраций</span>
                                <span class="text-sm font-bold text-blue-600">{{ registrations.data?.length || 0 }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Посещений</span>
                                <span class="text-sm font-bold text-green-600">{{ attendances.data?.length || 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 px-12 py-12">
                <div class="max-w-4xl">
                    <div v-if="activeSection === 'profile'">
                        <div class="flex flex-col gap-8">
                            <div class="text-center">
                                <h2 class="text-4xl font-bold text-gray-900 mb-4">Настройки профиля</h2>
                                <p class="text-xl text-gray-600 leading-relaxed">Управляйте вашей фотографией и информацией профиля</p>
                            </div>

                            <div class="bg-gradient-to-r from-transparent via-blue-200/50 to-transparent h-px w-full"></div>

                            <div class="p-8 rounded-3xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-2xl">
                                <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2zm16 4.59V6H4v2.59l8 7.99 8-7.99zM4 10.59V18h16v-7.41l-8 7.99-8-7.99z"/>
                                        </svg>
                                    </div>
                                    Фото профиля
                                </h3>
                                <p class="text-gray-600 mb-8">Обновите фотографию вашего профиля для лучшего представления в системе</p>

                                <UpdateProfilePhotoForm
                                    update-route="profile.visitor.update"
                                    class="max-w-2xl"
                                />
                            </div>

                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>