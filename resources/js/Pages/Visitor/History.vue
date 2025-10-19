<!-- History.vue -->
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    attendances: {
        type: Object,
        default: () => ({})
    }
});
</script>

<template>
    <Head title="История посещений" />

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
                                <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <span class="text-base font-semibold text-gray-700">История посещений</span>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl font-bold tracking-tight mb-6 text-balance text-gray-900">
                        Ваша 
                        <span class="bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">история</span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 max-w-2xl mx-auto text-pretty leading-relaxed">
                        Все мероприятия, которые вы уже посетили в Иркутском авиационном техникуме
                    </p>

                    <!-- Статистика -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-2xl mx-auto mb-8">
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">
                                {{ attendances.data?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Всего посещений</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-700 bg-clip-text text-transparent">
                                {{ attendances.data?.filter(a => a.has_rating)?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Оценено</div>
                        </div>
                        <div class="p-6 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 text-center">
                            <div class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-700 bg-clip-text text-transparent">
                                {{ attendances.data?.filter(a => !a.has_rating && a.event.is_completed)?.length || 0 }}
                            </div>
                            <div class="text-gray-600 text-sm">Ждут оценки</div>
                        </div>
                    </div>
                </div>

                <!-- Кнопка перехода в профиль -->
                <div class="flex justify-end mb-8">
                    <Link
                        :href="route('profile.visitor')"
                        class="px-6 py-3 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 flex items-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Перейти в профиль
                    </Link>
                </div>

                <!-- Карточки посещений -->
                <div v-if="attendances.data && attendances.data.length > 0" class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div
                        v-for="attendance in attendances.data"
                        :key="attendance.id"
                        class="group relative overflow-hidden"
                    >
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-600/10 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="rounded-3xl overflow-hidden">
                            <div class="relative p-8 rounded-3xl bg-white border border-gray-200 shadow-2xl hover:shadow-3xl transition-all duration-500 hover:-translate-y-2">
                            <!-- Заголовок и статус -->
                            <div class="flex justify-between items-start mb-6">
                                <h3 class="text-xl font-bold text-gray-900 flex-1 pr-4 group-hover:text-blue-700 transition-colors">
                                    {{ attendance.event.name }}
                                </h3>
                                <span
                                    class="px-4 py-2 rounded-xl text-sm font-bold border transition-all duration-300"
                                    :class="attendance.has_rating 
                                        ? 'bg-green-100 text-green-800 border-green-200 group-hover:bg-green-200' 
                                        : 'bg-yellow-100 text-yellow-800 border-yellow-200 group-hover:bg-yellow-200'"
                                >
                                    {{ attendance.has_rating ? '★ Оценено' : '✓ Завершено' }}
                                </span>
                            </div>

                            <!-- Даты -->
                            <div class="space-y-3 mb-6">
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-200">
                                    <svg class="w-5 h-5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-blue-600 font-medium">Дата события</div>
                                        <div class="text-gray-900">{{ new Date(attendance.event.start_time).toLocaleString('ru-RU') }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-green-50 border border-green-200">
                                    <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <div class="text-sm text-green-600 font-medium">Посещение</div>
                                        <div class="text-gray-900">{{ new Date(attendance.attended_at).toLocaleString('ru-RU') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="flex gap-3">
                                <Link
                                    :href="route('visitor.history.show', attendance.event.id)"
                                    class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-medium shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl text-center flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    Подробнее
                                </Link>

                                <Link
                                    v-if="!attendance.has_rating && attendance.event.is_completed"
                                    :href="route('visitor.events.rate', attendance.event.id)"
                                    class="flex-1 px-4 py-3 rounded-xl bg-gradient-to-r from-yellow-600 to-orange-600 text-white font-medium shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl text-center flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                    Оценить
                                </Link>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>

                <!-- Пустое состояние -->
                <div v-else class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-indigo-500/5 rounded-3xl blur-2xl"></div>
                    <div class="relative p-16 rounded-3xl bg-white/80 backdrop-blur-sm border border-blue-200/50 shadow-2xl text-center">
                        <div class="max-w-md mx-auto">
                            <div class="w-24 h-24 mx-auto mb-6 bg-gradient-to-br from-blue-100 to-blue-200 rounded-3xl flex items-center justify-center border border-blue-300">
                                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-3xl font-bold text-gray-900 mb-4">История пуста</h3>
                            <p class="text-gray-600 text-lg mb-8">Вы еще не посетили ни одного мероприятия. Самое время начать!</p>
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