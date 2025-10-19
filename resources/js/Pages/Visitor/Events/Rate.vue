<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
});

const form = useForm({
    rating: null,
});

const submit = () => {
    form.post(route('visitor.events.rate', props.event.id));
};
</script>

<template>
    <Head title="Оценка мероприятия" />

    <AuthenticatedLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                        Оценка мероприятия
                    </h1>
                    <p class="text-gray-600 text-lg max-w-md mx-auto">
                        Поделитесь своим мнением о мероприятии
                    </p>
                </div>

                <!-- Карточка оценки -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <!-- Информация о мероприятии -->
                        <div class="text-center mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Мероприятие</h3>
                            <p class="text-gray-700 text-xl font-medium">{{ event.name }}</p>
                        </div>

                        <!-- Оценка -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold text-gray-900 text-center mb-8">
                                Как вам мероприятие?
                            </h3>
                            <div class="flex flex-col sm:flex-row justify-center gap-6">
                                <!-- Лайк -->
                                <button 
                                    type="button"
                                    @click="form.rating = 1"
                                    :class="[
                                        'flex flex-col items-center justify-center px-8 py-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg w-full sm:w-1/2',
                                        form.rating === 1
                                            ? 'bg-green-100 border-4 border-green-500 scale-105 shadow-xl'
                                            : 'bg-gray-50 border-2 border-gray-300 hover:border-green-400 hover:bg-green-50'
                                    ]"
                                >
                                    <span class="text-5xl mb-4">👍</span>
                                    <span class="text-lg font-semibold text-gray-900">Понравилось</span>
                                </button>

                                <!-- Дизлайк -->
                                <button 
                                    type="button"
                                    @click="form.rating = -1"
                                    :class="[
                                        'flex flex-col items-center justify-center px-8 py-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-lg w-full sm:w-1/2',
                                        form.rating === -1
                                            ? 'bg-red-100 border-4 border-red-500 scale-105 shadow-xl'
                                            : 'bg-gray-50 border-2 border-gray-300 hover:border-red-400 hover:bg-red-50'
                                    ]"
                                >
                                    <span class="text-5xl mb-4">👎</span>
                                    <span class="text-lg font-semibold text-gray-900">Не понравилось</span>
                                </button>
                            </div>
                        </div>

                        <!-- Индикатор выбора -->
                        <div v-if="form.rating !== null" class="text-center mb-8">
                            <div :class="[
                                'inline-flex items-center gap-2 px-6 py-3 rounded-xl font-medium',
                                form.rating === 1 
                                    ? 'bg-green-100 text-green-800 border border-green-200'
                                    : 'bg-red-100 text-red-800 border border-red-200'
                            ]">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ form.rating === 1 ? 'Вы выбрали "Понравилось"' : 'Вы выбрали "Не понравилось"' }}
                            </div>
                        </div>

                        <!-- Кнопки -->
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('visitor.history')"
                                class="inline-flex items-center gap-2 w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20 text-center justify-center"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Назад к истории
                            </Link>
                            
                            <button 
                                @click="submit"
                                :disabled="form.rating === null"
                                :class="[
                                    'inline-flex items-center gap-2 w-full sm:w-auto px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border text-center justify-center',
                                    form.rating !== null
                                        ? 'bg-blue-600 hover:bg-blue-700 text-white border-blue-600/20 hover:scale-105'
                                        : 'bg-gray-200 text-gray-500 border-gray-300 cursor-not-allowed'
                                ]"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Отправить оценку
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>