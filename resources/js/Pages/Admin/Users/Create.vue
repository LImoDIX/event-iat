<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';
import { ref, onMounted } from 'vue';

const form = useForm({
    name: '',
    username: '',
    password: '',
    role: 'visitor',
});

// Реф для управления autofocus
const nameInput = ref(null);

// Устанавливаем фокус после монтирования компонента
onMounted(() => {
    setTimeout(() => {
        if (nameInput.value) {
            nameInput.value.focus();
        }
    }, 100);
});

const submit = () => {
    form.post(route('admin.users.store'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Создать пользователя" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок страницы -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Создание пользователя</h1>
                    <p class="text-gray-600">Добавьте нового пользователя в систему и назначьте ему роль</p>
                </div>

                <!-- Карточка формы -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Имя -->
                            <div>
                                <InputLabel for="name" value="Имя пользователя" class="text-gray-700 font-medium mb-3" />
                                <TextInput
                                    id="name"
                                    ref="nameInput"
                                    type="text"
                                    class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 px-4 py-3"
                                    placeholder="Введите полное имя"
                                    v-model="form.name"
                                    required
                                    autocomplete="name"
                                    :disabled="form.processing"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Username -->
                            <div>
                                <InputLabel for="username" value="Логин" class="text-gray-700 font-medium mb-3" />
                                <TextInput
                                    id="username"
                                    type="text"
                                    class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 px-4 py-3"
                                    placeholder="Введите логин"
                                    v-model="form.username"
                                    required
                                    autocomplete="username"
                                    :disabled="form.processing"
                                />
                                <InputError class="mt-2" :message="form.errors.username" />
                            </div>

                            <!-- Пароль -->
                            <div>
                                <InputLabel for="password" value="Пароль" class="text-gray-700 font-medium mb-3" />
                                <TextInput
                                    id="password"
                                    type="password"
                                    class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 px-4 py-3"
                                    placeholder="Введите надежный пароль"
                                    v-model="form.password"
                                    required
                                    autocomplete="new-password"
                                    :disabled="form.processing"
                                />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>

                            <!-- Роль -->
                            <div>
                                <InputLabel for="role" value="Роль пользователя" class="text-gray-700 font-medium mb-3" />
                                <div class="relative">
                                    <select
                                        id="role"
                                        class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 py-3 px-4 appearance-none"
                                        v-model="form.role"
                                        required
                                        :disabled="form.processing"
                                    >
                                        <option value="visitor">Посетитель</option>
                                        <option value="organizer">Организатор</option>
                                        <option value="admin">Администратор</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </div>
                                </div>
                                <InputError class="mt-2" :message="form.errors.role" />
                            </div>

                            <!-- Кнопки -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                                <Link
                                    :href="route('admin.users.index')"
                                    class="inline-flex items-center gap-2 w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20 text-center justify-center"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Вернуться к списку
                                </Link>
                                
                                <button
                                    :class="[
                                        'inline-flex items-center gap-2 px-8 py-3 rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border text-center justify-center w-full sm:w-auto',
                                        form.processing
                                            ? 'bg-blue-400 text-white border-blue-400 cursor-not-allowed'
                                            : 'bg-blue-600 hover:bg-blue-700 text-white border-blue-600/20 hover:scale-105'
                                    ]"
                                    :disabled="form.processing"
                                    type="submit"
                                >
                                    <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    {{ form.processing ? 'Создание...' : 'Создать пользователя' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>