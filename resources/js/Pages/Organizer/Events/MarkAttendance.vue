<script setup>
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    event: Object,
    registrations: Array,
    attendanceStatus: Object,
});

const searchForm = useForm({
    query: '',
});

// Вычисляемое свойство для фильтрации регистраций
const filteredRegistrations = computed(() => {
    if (!searchForm.query) {
        return props.registrations;
    }
    
    const query = searchForm.query.toLowerCase();
    return props.registrations.filter(registration =>
        registration.confirmation_code.toLowerCase().includes(query) ||
        registration.user.name.toLowerCase().includes(query)
    );
});

const handleSearch = (e) => {
    // Предотвращаем перезагрузку страницы
    e.preventDefault();
    // Фильтрация осуществляется через computed свойство filteredRegistrations
};

const toggleAttendance = (registrationId, currentStatus) => {
    const form = useForm({
        attended: !currentStatus,
    });
    
    form.post(route('organizer.events.update-attendance', { 
        event: props.event.id, 
        registration: registrationId 
    }), {
        preserveScroll: true,
        onSuccess: () => {
            // Обновляем статус в локальном состоянии
            // В реальном приложении лучше перезагрузить данные с сервера
        },
    });
};

// Функция для корректного отображения пути к изображению
const getImagePath = (path) => {
    if (!path) return null;
    // Убираем дублирование /storage/
    return path.replace('/storage//storage/', '/storage/');
};
</script>

<template>
    <Head title="Отметить посетителей" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div class="bg-white/80 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8">
                        <!-- Header -->
                        <div class="mb-10">
                            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6 gap-6">
                                <div class="flex items-center gap-4">
                                    <Link
                                        :href="route('organizer.events.index')"
                                        class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                    >
                                        <svg class="w-5 h-5 transform transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                        Назад
                                    </Link>
                                    <div>
                                        <h2 class="text-3xl font-bold text-gray-900">Отметить посетителей</h2>
                                        <p class="text-gray-600 mt-2 text-lg">Отметьте участников, которые посетили мероприятие</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Event Info -->
                            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-6 shadow-lg">
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ event.name }}</h3>
                                <p class="text-gray-600 text-lg mb-4">{{ event.description }}</p>
                                <div class="flex flex-wrap items-center gap-6 text-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-blue-200">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold">{{ new Date(event.start_time).toLocaleDateString() }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-sm border border-blue-200">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <span class="font-semibold">{{ new Date(event.start_time).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Search form -->
                        <form @submit.prevent="handleSearch" class="mb-8">
                            <div class="flex flex-col sm:flex-row gap-4">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <TextInput
                                        type="text"
                                        v-model="searchForm.query"
                                        placeholder="Поиск по коду или ФИО..."
                                        class="pl-12 w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 text-lg py-3"
                                    />
                                </div>
                                <div class="flex gap-3">
                                    <PrimaryButton
                                        class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 group"
                                    >
                                        <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Поиск
                                    </PrimaryButton>
                                    <button
                                        v-if="searchForm.query"
                                        type="button"
                                        @click="searchForm.query = ''"
                                        class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                    >
                                        <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Сбросить
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Registrations list -->
                        <div v-if="filteredRegistrations.length > 0" class="space-y-4">
                            <div
                                v-for="registration in filteredRegistrations"
                                :key="registration.id"
                                class="border border-gray-200 rounded-2xl p-6 bg-white hover:bg-blue-50/50 transition-all duration-300 shadow-lg hover:shadow-xl group"
                            >
                                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-6">
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
                                            <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full border-2 border-white shadow-lg"
                                                :class="attendanceStatus[registration.id] ? 'bg-green-500' : 'bg-red-500'">
                                            </div>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-bold text-gray-900">{{ registration.user.name }}</h4>
                                            <p class="text-gray-600 text-lg">
                                                {{ registration.user.email }}
                                            </p>
                                            <p class="text-gray-500 mt-2 font-medium">
                                                Код: <span class="font-bold text-blue-600">{{ registration.confirmation_code }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-4">
                                        <span
                                            :class="[
                                                'px-4 py-2 rounded-xl text-base font-bold border shadow-sm transition-all duration-300 group-hover:scale-105',
                                                attendanceStatus[registration.id]
                                                    ? 'bg-green-100 text-green-800 border-green-200'
                                                    : 'bg-red-100 text-red-800 border-red-200'
                                            ]"
                                        >
                                            {{ attendanceStatus[registration.id] ? 'Посетил' : 'Не посетил' }}
                                        </span>
                                        
                                        <button
                                            @click="toggleAttendance(registration.id, attendanceStatus[registration.id])"
                                            type="button"
                                            :class="[
                                                'px-6 py-3 rounded-xl text-base font-bold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border group/btn',
                                                attendanceStatus[registration.id]
                                                    ? 'bg-red-600 hover:bg-red-700 text-white border-red-600'
                                                    : 'bg-green-600 hover:bg-green-700 text-white border-green-600'
                                            ]"
                                        >
                                            <svg v-if="!attendanceStatus[registration.id]" class="w-5 h-5 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                            </svg>
                                            <svg v-else class="w-5 h-5 transform transition-transform group-hover/btn:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            {{ attendanceStatus[registration.id] ? 'Отменить' : 'Отметить' }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-3xl flex items-center justify-center shadow-lg">
                                    <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">
                                    {{ searchForm.query ? 'Участники не найдены' : 'Нет зарегистрированных участников' }}
                                </h3>
                                <p class="text-gray-600 text-lg">
                                    {{ searchForm.query ? 'Попробуйте изменить поисковый запрос' : 'На это мероприятие еще никто не зарегистрировался.' }}
                                </p>
                            </div>
                        </div>

                        <!-- Summary -->
                        <div v-if="registrations.length > 0" class="mt-12 pt-8 border-t border-gray-200">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="text-center p-6 bg-white border border-gray-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="text-4xl font-bold text-gray-900 mb-2">{{ registrations.length }}</div>
                                    <div class="text-gray-600 font-semibold text-lg">Всего зарегистрировано</div>
                                </div>
                                <div class="text-center p-6 bg-white border border-green-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="text-4xl font-bold text-green-600 mb-2">
                                        {{ Object.values(attendanceStatus).filter(status => status).length }}
                                    </div>
                                    <div class="text-gray-600 font-semibold text-lg">Посетили мероприятие</div>
                                </div>
                                <div class="text-center p-6 bg-white border border-red-200 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                                    <div class="text-4xl font-bold text-red-600 mb-2">
                                        {{ Object.values(attendanceStatus).filter(status => !status).length }}
                                    </div>
                                    <div class="text-gray-600 font-semibold text-lg">Не посетили</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>