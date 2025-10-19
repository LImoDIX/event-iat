<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    event: Object,
    user: Object,
});

const form = useForm({
    confirmed: false,
});
</script>

<template>
    <Head title="Подтверждение регистрации" />

    <AuthenticatedLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Контент -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8 transition-all duration-500 animate-fadeInUp">
                    <!-- Заголовок -->
                    <div class="text-center mb-8">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Регистрация на мероприятие</h2>
                        <p class="text-gray-600">Пожалуйста, подтвердите ваши данные для завершения регистрации</p>
                    </div>

                    <!-- Информация о мероприятии -->
                    <div class="mb-8 p-6 bg-blue-50 rounded-xl border border-blue-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/>
                            </svg>
                            Мероприятие
                        </h3>
                        <p class="text-gray-700 text-lg font-medium">{{ event.name }}</p>
                    </div>

                    <!-- Данные пользователя -->
                    <div class="mb-8 p-6 bg-green-50 rounded-xl border border-green-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                            Ваши данные
                        </h3>
                        <p class="text-gray-700 text-lg font-medium">{{ user.name }}</p>
                        <p class="text-gray-600">{{ user.email }}</p>
                    </div>

                    <!-- Чекбокс подтверждения -->
                    <div class="mb-8 p-6 bg-yellow-50 rounded-xl border border-yellow-200">
                        <label class="flex items-start gap-4 cursor-pointer group">
                            <input
                                type="checkbox"
                                v-model="form.confirmed"
                                class="mt-1 h-6 w-6 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 transition duration-200 group-hover:border-blue-400"
                            >
                            <div>
                                <span class="text-lg font-semibold text-gray-900 block mb-2">
                                    Подтверждение данных
                                </span>
                                <span class="text-gray-700 leading-relaxed">
                                    Я подтверждаю свои данные и хочу зарегистрироваться на мероприятие "{{ event.name }}"
                                </span>
                            </div>
                        </label>
                    </div>

                    <!-- Индикатор подтверждения -->
                    <div v-if="form.confirmed" class="mb-8 p-4 bg-green-100 border border-green-300 rounded-xl text-center">
                        <div class="flex items-center justify-center gap-2 text-green-800 font-medium">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                            Данные подтверждены. Готово к регистрации!
                        </div>
                    </div>

                    <!-- Кнопки действий -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                        <Link
                            :href="route('visitor.events.show', event.id)"
                            class="inline-flex items-center gap-2 w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20 text-center justify-center"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Назад к мероприятию
                        </Link>

                        <form
                            @submit.prevent="form.post(route('visitor.events.confirm-registration', event.id))"
                            class="w-full sm:w-auto"
                        >
                            <button
                                type="submit"
                                :disabled="!form.confirmed"
                                :class="[
                                    'inline-flex items-center gap-2 w-full px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border text-center justify-center',
                                    form.confirmed
                                        ? 'bg-blue-600 hover:bg-blue-700 text-white border-blue-600/20 hover:scale-105'
                                        : 'bg-gray-200 text-gray-500 border-gray-300 cursor-not-allowed'
                                ]"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Подтвердить регистрацию
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes fadeInUp {
  0% { 
    opacity: 0; 
    transform: translateY(20px); 
  }
  100% { 
    opacity: 1; 
    transform: translateY(0); 
  }
}
.animate-fadeInUp {
  animation: fadeInUp 0.6s ease-out;
}
</style>