<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const props = defineProps({
    user: Object,
    authUser: Object,
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

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    role: props.user.role,
});

const submit = () => {
    form.put(route('admin.users.update', props.user.id), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Редактировать пользователя" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Заголовок -->
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Редактирование пользователя</h1>
                    <p class="text-gray-600">Измените данные пользователя и его роль</p>
                </div>

                <!-- Карточка формы -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg overflow-hidden">
                    <div class="p-8">
                        <!-- User Info Preview -->
                        <div class="bg-gray-50 rounded-xl p-6 mb-8 border border-gray-200">
                            <div class="flex items-center gap-4">
                                <div v-if="user.profile_photo_path" class="w-16 h-16 rounded-full overflow-hidden border-2 border-white shadow-sm">
                                    <img
                                        :src="getImageUrl(user.profile_photo_path)"
                                        :alt="user.name"
                                        class="w-full h-full object-cover"
                                        @error="(e) => { e.target.style.display = 'none'; }"
                                        loading="lazy"
                                        width="64"
                                        height="64"
                                    >
                                </div>
                                <div v-else class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center border-2 border-white shadow-sm">
                                    <span class="text-2xl font-bold text-blue-600">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-semibold text-gray-900 text-lg">{{ user.name }}</p>
                                    <p class="text-gray-600">@{{ user.username }}</p>
                                </div>
                                <div>
                                    <span
                                        :class="[
                                            'px-4 py-2 text-sm font-semibold rounded-full border',
                                            user.role === 'admin' ? 'bg-red-100 text-red-800 border-red-200' :
                                            user.role === 'organizer' ? 'bg-blue-100 text-blue-800 border-blue-200' :
                                            'bg-green-100 text-green-800 border-green-200'
                                        ]"
                                    >
                                        {{ user.role === 'admin' ? 'Администратор' : user.role === 'organizer' ? 'Организатор' : 'Посетитель' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Form -->
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Name Field -->
                            <div>
                                <InputLabel for="name" value="Имя пользователя" class="text-gray-700 font-medium mb-3" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 px-4 py-3"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    :disabled="form.processing"
                                />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>

                            <!-- Username Field -->
                            <div>
                                <InputLabel for="username" value="Логин" class="text-gray-700 font-medium mb-3" />
                                <TextInput
                                    id="username"
                                    type="text"
                                    class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all duration-200 px-4 py-3"
                                    v-model="form.username"
                                    required
                                    :disabled="form.processing"
                                />
                                <InputError class="mt-2" :message="form.errors.username" />
                            </div>

                            <!-- Role Field -->
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

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 border-t border-gray-200">
                                <Link
                                    :href="route('admin.users.index')"
                                    class="inline-flex items-center gap-2 w-full sm:w-auto px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20 text-center justify-center"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    Назад к списку
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
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    {{ form.processing ? 'Обновление...' : 'Обновить пользователя' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>