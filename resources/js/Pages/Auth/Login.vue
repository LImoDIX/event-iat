<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: String,
});

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Вход" />

    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 text-gray-900">
        <header class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-lg border-b border-gray-200 shadow-sm">
            <div class="container mx-auto flex h-16 items-center justify-between px-6 lg:px-12">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg">
                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">ИАТ Мероприятия</h1>
                        <p class="text-xs text-gray-600">Иркутский авиационный техникум</p>
                    </div>
                </div>

                <nav class="flex items-center gap-4">
                    <Link href="/">
                        <button class="px-4 py-2 font-medium rounded-xl bg-white text-gray-700 border border-gray-300 shadow-lg transition-all duration-300 hover:shadow-xl hover:border-blue-300 hover:scale-105">
                            На главную
                        </button>
                    </Link>
                </nav>
            </div>
        </header>

        <div class="flex items-center justify-center flex-1 px-4 py-12">
            <div class="w-full max-w-md bg-white border border-gray-200 rounded-2xl p-8 shadow-2xl transition-all duration-300 hover:shadow-3xl">
                <!-- Заголовок -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Вход в систему
                    </h1>
                    <p class="text-gray-600 mt-2">Введите свои учетные данные</p>
                </div>

                <!-- Статус -->
                <div v-if="status" class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200">
                    <div class="flex items-center gap-3">
                        <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm font-medium text-green-800">{{ status }}</p>
                    </div>
                </div>

                <!-- Форма -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Username -->
                    <div>
                        <InputLabel for="username" value="Логин" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <TextInput
                                id="username"
                                type="text"
                                v-model="form.username"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Введите логин"
                                class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 pl-12 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            </div>
                        </div>
                        <InputError class="mt-2 text-red-600 text-sm" :message="form.errors.username" />
                    </div>

                    <!-- Password -->
                    <div>
                        <InputLabel for="password" value="Пароль" class="text-gray-700 font-medium mb-2" />
                        <div class="relative">
                            <TextInput
                                id="password"
                                type="password"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 pl-12 pr-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-300"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            </div>
                        </div>
                        <InputError class="mt-2 text-red-600 text-sm" :message="form.errors.password" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center">
                        <label class="flex items-center text-gray-700 cursor-pointer">
                            <Checkbox name="remember" v-model:checked="form.remember" class="mr-3 border-gray-300 text-blue-600 focus:ring-blue-500" />
                            <span class="text-sm">Запомнить меня</span>
                        </label>
                    </div>

                    <!-- Button -->
                    <button
                        class="w-full flex items-center justify-center py-4 px-6 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="form.processing"
                    >
                        <span v-if="form.processing" class="mr-3 flex items-center">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        <span>{{ form.processing ? 'Вход...' : 'Войти' }}</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>