<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import NewsBanner from '@/Components/NewsBanner.vue';

const props = defineProps({
    events: Object,
    registrations: Object,
    attendances: Object,
    news: Array,
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

const daysUntilEvent = (eventDate) => {
    const today = new Date();
    const eventDay = new Date(eventDate);
    const diffTime = eventDay - today;
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    return diffDays > 0 ? diffDays : 0;
};
</script>

<template>
    <Head title="Главная" />

    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
                <!-- News Banner -->
                <NewsBanner v-if="news && news.length > 0" :news="news" />

                <!-- Welcome Section -->
                <div class="text-center relative overflow-hidden">
                    <!-- Анимированный фон -->
                    <div class="absolute inset-0 bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50"></div>
                    
                    <!-- Декоративные элементы -->
                    <div class="absolute top-10 left-10 w-6 h-6 bg-blue-300 rounded-full animate-pulse"></div>
                    <div class="absolute top-20 right-20 w-4 h-4 bg-indigo-300 rounded-full animate-pulse delay-300"></div>
                    <div class="absolute bottom-10 left-20 w-8 h-8 bg-blue-200 rounded-full animate-pulse delay-700"></div>
                    <div class="absolute bottom-20 right-10 w-5 h-5 bg-indigo-200 rounded-full animate-pulse delay-1000"></div>
                    
                    <div class="relative mx-auto max-w-4xl">    
                        
                        <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold tracking-tight mb-6 text-balance text-gray-900">
                            Привет,
                            <span class="bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-700 bg-clip-text text-transparent animate-gradient">{{ $page.props.auth.user.name }}</span>!
                        </h1>
                        <p class="text-2xl text-gray-600 mb-12 max-w-3xl mx-auto text-pretty leading-relaxed bg-gradient-to-r from-gray-600 to-gray-700 bg-clip-text text-transparent">
                            Открывайте новые возможности и становитесь частью событий техникума
                        </p>

                        <!-- Статистика -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto">
                            <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                                    {{ registrations.data.length }}
                                </div>
                                <div class="text-gray-600 text-sm">Мои регистрации</div>
                            </div>
                            <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent">
                                    {{ attendances?.length || 0 }}
                                </div>
                                <div class="text-gray-600 text-sm">Посещено событий</div>
                            </div>
                            <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-700 bg-clip-text text-transparent">
                                    {{ events.data.length }}
                                </div>
                                <div class="text-gray-600 text-sm">Доступно сейчас</div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Предстоящие мероприятия -->
                <div v-if="registrations.data.length > 0" class="space-y-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-6">
                            <div class="relative">
                                <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-2xl transform rotate-3">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-lg">
                                    {{ registrations.data.length }}
                                </div>
                            </div>
                            <div>
                                <h2 class="text-4xl font-bold text-gray-900">Мои мероприятия</h2>
                                <p class="text-gray-600 text-lg">События, на которые вы зарегистрированы</p>
                            </div>
                        </div>
                        <Link 
                            :href="route('visitor.events.index')" 
                            class="group px-8 py-4 rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl hover:border-blue-300 flex items-center gap-3"
                        >
                            <span>Все мероприятия</span>
                            <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>

                    <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="registration in registrations.data.slice(0, 3)"
                            :key="registration.id"
                            class="group relative"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-emerald-600/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            <div class="rounded-3xl overflow-hidden">
                                <div class="relative p-8 rounded-3xl bg-white border border-gray-200 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:-translate-y-2">
                                <!-- Фотография мероприятия -->
                                <div v-if="registration.event.photo_path" class="h-48 rounded-2xl overflow-hidden mb-6 border-2 border-gray-200 group-hover:border-green-300 transition-colors relative">
                                    <img
                                        :src="getImageUrl(registration.event.photo_path)"
                                        :alt="registration.event.name"
                                        class="w-full h-full object-cover transform transition-transform group-hover:scale-110 duration-700"
                                        loading="lazy"
                                        width="300"
                                        height="200"
                                    />
                                    <div class="absolute top-4 right-4">
                                        <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                            Вы записаны
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-green-700 transition-colors">{{ registration.event.name }}</h3>
                                    
                                    <p class="text-gray-600 leading-relaxed line-clamp-2">{{ registration.event.description }}</p>
                                    
                                    <!-- Организатор мероприятия -->
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200">
                                        <div class="relative">
                                            <div v-if="registration.event.organizer.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border-2 border-green-300">
                                                <img
                                                    :src="getImageUrl(registration.event.organizer.profile_photo_path)"
                                                    :alt="registration.event.organizer.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="40"
                                                    height="40"
                                                />
                                            </div>
                                            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-r from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold border-2 border-green-300">
                                                {{ registration.event.organizer.name.charAt(0).toUpperCase() }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-700 font-medium block">{{ registration.event.organizer.name }}</span>
                                            <span class="text-green-600 text-sm">Организатор</span>
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
                                            <span class="text-purple-700 font-medium">{{ daysUntilEvent(registration.event.start_time) }} дней</span>
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
                    </div>
                </div>

                <!-- Новые мероприятия -->
                <div class="space-y-8">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-6">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center shadow-2xl transform -rotate-3">
                                <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-4xl font-bold text-gray-900">Новые мероприятия</h2>
                                <p class="text-gray-600 text-lg">Откройте для себя интересные события</p>
                            </div>
                        </div>
                    </div>

                    <div v-if="events.data.length > 0" class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                        <div
                            v-for="event in events.data.slice(0, 3)"
                            :key="event.id"
                            class="group relative"
                        >
                            <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-cyan-600/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                            <div class="rounded-3xl overflow-hidden">
                                <div class="relative p-8 rounded-3xl bg-white border border-gray-200 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:-translate-y-2 overflow-hidden">
                                <!-- Фотография мероприятия -->
                                <div v-if="event.photo_path" class="h-48 rounded-2xl overflow-hidden mb-6 border-2 border-gray-200 group-hover:border-blue-300 transition-colors relative">
                                    <img
                                        :src="getImageUrl(event.photo_path)"
                                        :alt="event.name"
                                        class="w-full h-full object-cover transform transition-transform group-hover:scale-110 duration-700"
                                        loading="lazy"
                                        width="300"
                                        height="200"
                                    />
                                    <div v-if="event.user_registered" class="absolute top-4 right-4">
                                        <span class="bg-green-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                            Вы записаны
                                        </span>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    <h3 class="text-xl font-bold text-gray-900 line-clamp-2 group-hover:text-blue-700 transition-colors">{{ event.name }}</h3>
                                    
                                    <p class="text-gray-600 leading-relaxed line-clamp-2">{{ event.description }}</p>
                                    
                                    <!-- Организатор мероприятия -->
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-gradient-to-r from-blue-50 to-cyan-50 border border-blue-200">
                                        <div class="relative">
                                            <div v-if="event.organizer.profile_photo_path" class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-300">
                                                <img
                                                    :src="getImageUrl(event.organizer.profile_photo_path)"
                                                    :alt="event.organizer.name"
                                                    class="w-full h-full object-cover"
                                                    loading="lazy"
                                                    width="40"
                                                    height="40"
                                                />
                                            </div>
                                            <div v-else class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-500 to-cyan-600 flex items-center justify-center text-white font-bold border-2 border-blue-300">
                                                {{ event.organizer.name.charAt(0).toUpperCase() }}
                                            </div>
                                        </div>
                                        <div>
                                            <span class="text-gray-700 font-medium block">{{ event.organizer.name }}</span>
                                            <span class="text-blue-600 text-sm">Организатор</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-xl border border-blue-200">
                                            <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-blue-700 font-medium">{{ new Date(event.start_time).toLocaleDateString('ru-RU') }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 bg-purple-50 px-3 py-2 rounded-xl border border-purple-200">
                                            <svg class="w-4 h-4 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-purple-700 font-medium">{{ event.duration }} мин</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-3 pt-4">
                                        <Link
                                            :href="route('visitor.events.show', event.id)"
                                            class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl text-center flex items-center justify-center gap-2"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            Подробнее
                                        </Link>
                                        
                                        <form
                                            v-if="event.user_registered && new Date(event.start_time) > new Date()"
                                            @submit.prevent="$event.target.submit()"
                                            :action="route('visitor.events.cancel-registration', event.id)"
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
                    </div>
                    
                    <!-- Кнопка для просмотра всех мероприятий -->
                    <div class="flex justify-center mt-8">
                        <Link
                            :href="route('visitor.events.index')"
                            class="px-8 py-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 inline-flex items-center gap-2"
                        >
                            <span>Посмотреть все мероприятия</span>
                            <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </Link>
                    </div>

                </div>

                <!-- Empty State для предстоящих мероприятий -->
                <div v-if="registrations.data.length === 0" class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-500/5 rounded-3xl blur-2xl"></div>
                    <div class="relative p-16 rounded-3xl bg-white/80 backdrop-blur-sm border border-green-200/50 shadow-2xl text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-green-100 to-emerald-200 rounded-3xl flex items-center justify-center border border-green-300 transform -rotate-6">
                                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">Начните участвовать!</h3>
                            <p class="text-gray-600 text-lg mb-8">Зарегистрируйтесь на мероприятия, чтобы увидеть их здесь</p>
                            <Link 
                                :href="route('visitor.events.index')" 
                                class="px-8 py-4 rounded-2xl bg-gradient-to-r from-green-600 to-emerald-700 text-white font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 inline-flex items-center gap-2"
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

<style>
@keyframes gradient {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}
</style>