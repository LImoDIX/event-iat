<!-- MyEvents.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    registrations: Object,
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
</script>

<template>
    <Head title="Мои мероприятия" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="text-center relative mb-12">
                    <!-- Декоративные элементы -->
                    <div class="absolute top-0 left-10 w-6 h-6 bg-blue-200 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-0 right-16 w-4 h-4 bg-indigo-200 rounded-full animate-pulse delay-500"></div>
                    <div class="absolute top-10 right-20 w-8 h-8 bg-blue-100 rounded-full animate-pulse delay-700"></div>
                    
                    <div class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200 shadow-lg mb-8 transform transition-all duration-500 hover:scale-105 hover:shadow-xl">
                        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-indigo-700">
                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <span class="text-base font-semibold text-gray-700">Мои мероприятия</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6 text-balance text-gray-900">
                        Ваши 
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">регистрации</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto text-pretty leading-relaxed">
                        Все мероприятия, на которые вы зарегистрированы в Иркутском авиационном техникуме
                    </p>

                    <!-- Статистика -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto mb-8">
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                                {{ registrations.data?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Всего регистраций</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent">
                                {{ registrations.data?.filter(r => new Date(r.event.start_time) > new Date())?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Предстоящих</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-700 bg-clip-text text-transparent">
                                {{ registrations.data?.filter(r => new Date(r.event.start_time) <= new Date())?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Прошедших</div>
                        </div>
                    </div>

                    <!-- Кнопка перехода на дашборд -->
                    <div class="flex justify-center">
                        <Link
                            :href="route('dashboard.visitor')"
                            class="px-6 py-3 rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 hover:border-blue-300 flex items-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            На главную
                        </Link>
                    </div>
                </div>

                <!-- Events grid -->
                <div v-if="registrations.data.length > 0">
                    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="registration in registrations.data"
                            :key="registration.id"
                            class="group relative overflow-hidden"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-600/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            <div class="relative p-8 rounded-3xl bg-white border border-gray-200 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:-translate-y-2">
                                <!-- Фотография мероприятия -->
                                <div v-if="registration.event.photo_path" class="h-48 rounded-2xl overflow-hidden mb-6 border-2 border-gray-200 group-hover:border-blue-300 transition-colors">
                                    <img
                                        :src="getImageUrl(registration.event.photo_path)"
                                        :alt="registration.event.name"
                                        class="w-full h-full object-cover transform transition-transform group-hover:scale-110 duration-700"
                                    />
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                            Вы записаны
                                        </span>
                                    </div>
                                </div>
                                <div v-else class="h-48 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center mb-6 border-2 border-gray-200 group-hover:border-blue-300 transition-colors">
                                    <svg class="w-16 h-16 text-gray-400" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                    </svg>
                                </div>
                                
                                <div class="space-y-4">
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-blue-700 transition-colors">{{ registration.event.name }}</h3>
                                    
                                    <p class="text-gray-600 leading-relaxed line-clamp-2">{{ registration.event.description }}</p>
                                    
                                    <!-- Организатор мероприятия -->
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-200">
                                        <div class="relative">
                                            <div v-if="registration.event.organizer.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-300">
                                                <img
                                                    :src="getImageUrl(registration.event.organizer.profile_photo_path)"
                                                    :alt="registration.event.organizer.name"
                                                    class="w-full h-full object-cover"
                                                />
                                            </div>
                                            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold border-2 border-blue-300">
                                                {{ registration.event.organizer.name.charAt(0).toUpperCase() }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-700 font-medium block">{{ registration.event.organizer.name }}</span>
                                            <span class="text-blue-600 text-sm">Организатор</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-xl border border-blue-200">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-blue-700 font-medium">{{ new Date(registration.event.start_time).toLocaleDateString('ru-RU') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-purple-50 px-3 py-2 rounded-xl border border-purple-200">
                                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-purple-700 font-medium">{{ registration.event.duration }} мин</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-4">
                                        <Link
                                            :href="route('visitor.events.show', registration.event.id)"
                                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl text-center flex items-center justify-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Подробнее
                                        </Link>
                                        
                                        <form 
                                            v-if="new Date(registration.event.start_time) > new Date()"
                                            @submit.prevent="$event.target.submit()"
                                            :action="route('visitor.events.cancel-registration', registration.event.id)"
                                            method="post"
                                            class="flex-1"
                                        >
                                            <input type="hidden" name="_method" value="delete">
                                            <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                            <button
                                                type="submit"
                                                class="w-full px-4 py-3 rounded-xl bg-gradient-to-r from-red-500 to-pink-600 text-white font-medium shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center justify-center gap-2"
                                                onclick="return confirm('Вы уверены, что хотите отменить регистрацию на это мероприятие?')"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                                Отменить
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-12 flex justify-between items-center">
                        <div v-if="registrations.prev_page_url">
                            <Link
                                :href="registrations.prev_page_url"
                                class="px-6 py-3 rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl hover:border-blue-300 flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Назад
                            </Link>
                        </div>
                        <div v-else>
                            <span class="px-6 py-3 rounded-2xl bg-gray-100 text-gray-400 border border-gray-200 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                                Назад
                            </span>
                        </div>
                        
                        <div class="text-gray-600 text-sm">
                            Страница {{ registrations.current_page }} из {{ registrations.last_page }}
                        </div>
                        
                        <div v-if="registrations.next_page_url">
                            <Link
                                :href="registrations.next_page_url"
                                class="px-6 py-3 rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl hover:border-blue-300 flex items-center gap-2"
                            >
                                Вперед
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </Link>
                        </div>
                        <div v-else>
                            <span class="px-6 py-3 rounded-2xl bg-gray-100 text-gray-400 border border-gray-200 flex items-center gap-2">
                                Вперед
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                
                <!-- Empty State -->
                <div v-else class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-indigo-500/5 rounded-3xl blur-2xl"></div>
                    <div class="relative p-16 rounded-3xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-2xl text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl flex items-center justify-center border border-blue-300">
                                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">Регистраций нет</h3>
                            <p class="text-gray-600 text-lg mb-8">Вы не зарегистрированы ни на одно мероприятие</p>
                            <Link
                                :href="route('visitor.events.index')"
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 inline-flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Найти мероприятия
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>