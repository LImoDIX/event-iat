<script setup>
import { ref } from 'vue';
import { Link, router, useForm } from '@inertiajs/vue3';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const form = useForm({
    title: '',
    description: '',
    photo: null,
    is_active: true,
});

const photoPreview = ref(null);

const handlePhotoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.photo = file;
        
        // Создаем превью изображения
        const reader = new FileReader();
        reader.onload = (e) => {
            photoPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    form.post(route('admin.news.store'), {
        forceFormData: true,
        onSuccess: () => {
            form.reset();
            photoPreview.value = null;
        },
    });
};
</script>

<template>
    <Head title="Создать новость" />

    <AdminOrganizerLayout>
        <div class="py-8 bg-gray-50 min-h-screen">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Создать новость</h1>
                    <p class="text-lg text-gray-600">Добавьте новую новость для отображения на сайте</p>
                </div>

                <!-- Form -->
                <div class="bg-white border border-gray-200 rounded-2xl shadow-lg p-8">
                    <form @submit.prevent="submit" class="space-y-8">
                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-lg font-medium text-gray-900 mb-3">
                                Заголовок новости
                            </label>
                            <input
                                id="title"
                                v-model="form.title"
                                type="text"
                                required
                                placeholder="Введите заголовок новости"
                                class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            />
                            <div v-if="form.errors.title" class="mt-2 text-red-600 text-sm">
                                {{ form.errors.title }}
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-lg font-medium text-gray-900 mb-3">
                                Описание новости
                            </label>
                            <textarea
                                id="description"
                                v-model="form.description"
                                rows="5"
                                placeholder="Введите описание новости"
                                class="block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 px-4 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all duration-200"
                            ></textarea>
                            <div v-if="form.errors.description" class="mt-2 text-red-600 text-sm">
                                {{ form.errors.description }}
                            </div>
                        </div>

                        <!-- Photo -->
                        <div>
                            <label class="block text-lg font-medium text-gray-900 mb-3">
                                Изображение новости
                            </label>
                            <div class="flex items-start gap-8">
                                <!-- Preview -->
                                <div class="flex-shrink-0">
                                    <div class="w-32 h-32 rounded-xl overflow-hidden border-2 border-dashed border-gray-300 flex items-center justify-center bg-gray-50">
                                        <img
                                            v-if="photoPreview"
                                            :src="photoPreview"
                                            alt="Предпросмотр"
                                            class="w-full h-full object-cover"
                                        />
                                        <svg
                                            v-else
                                            class="w-12 h-12 text-gray-400"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                            />
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Upload -->
                                <div class="flex-1">
                                    <div class="flex items-center justify-center w-full">
                                        <label
                                            for="photo"
                                            class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors"
                                        >
                                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                <svg
                                                    class="w-8 h-8 mb-4 text-gray-500"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                                    />
                                                </svg>
                                                <p class="mb-2 text-sm text-gray-500">
                                                    <span class="font-semibold">Нажмите для загрузки</span> или перетащите
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    PNG, JPG, GIF до 2MB
                                                </p>
                                            </div>
                                            <input
                                                id="photo"
                                                type="file"
                                                class="hidden"
                                                @change="handlePhotoChange"
                                                accept="image/*"
                                            />
                                        </label>
                                    </div>
                                    <div v-if="form.errors.photo" class="mt-2 text-red-600 text-sm">
                                        {{ form.errors.photo }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-lg font-medium text-gray-900 mb-3">
                                Статус новости
                            </label>
                            <div class="flex items-center">
                                <label class="flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        v-model="form.is_active"
                                        class="sr-only peer"
                                    />
                                    <div class="relative w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                    <span class="ml-3 text-sm font-medium text-gray-700">
                                        {{ form.is_active ? 'Активна' : 'Неактивна' }}
                                    </span>
                                </label>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                            <Link
                                :href="route('admin.news.index')"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-xl font-medium transition-all duration-200 shadow-sm hover:shadow-md border border-gray-600/20"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Отмена
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold transition-all duration-200 shadow-sm hover:shadow-md border border-blue-600/20 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <svg
                                    v-if="form.processing"
                                    class="animate-spin h-5 w-5 text-white"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                {{ form.processing ? 'Создание...' : 'Создать новость' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>