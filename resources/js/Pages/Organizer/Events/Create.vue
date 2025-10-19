<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import AdminOrganizerLayout from '@/Layouts/AdminOrganizerLayout.vue';

const form = useForm({
    name: '',
    description: '',
    photo: null,
    photoPreview: null,
    start_time: '',
    duration: '',
    max_attendees: '',
});

const photoInput = ref(null);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = (e) => {
    const file = e.target.files[0];
    
    if (!file) return;
    
    // Проверка размера файла (2MB максимум)
    if (file.size > 2048 * 1024) {
        alert('Размер файла превышает 2MB');
        return;
    }
    
    // Проверка типа файла
    if (!['image/jpeg', 'image/png', 'image/gif'].includes(file.type)) {
        alert('Пожалуйста, выберите файл JPG, PNG или GIF');
        return;
    }
    
    // Сохраняем файл для отправки
    form.photo = file;
    
    // Создание превью
    const reader = new FileReader();
    reader.onload = (e) => {
        form.photoPreview = e.target.result;
    };
    reader.readAsDataURL(file);
};

const submit = () => {
    // Создаем FormData для отправки файла
    const formData = new FormData();
    formData.append('name', form.name);
    formData.append('description', form.description);
    formData.append('start_time', form.start_time);
    formData.append('duration', form.duration);
    formData.append('max_attendees', form.max_attendees);
    if (form.photo) {
        formData.append('photo', form.photo);
    }
    
    // Отправляем форму
    form.post(route('organizer.events.store'), {
        data: formData,
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Создать мероприятие" />

    <AdminOrganizerLayout>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <!-- Main Card -->
                <div class="bg-white/80 backdrop-blur-lg border border-gray-200 rounded-3xl shadow-2xl overflow-hidden">
                    <div class="p-8">
                        <!-- Header -->
                        <div class="mb-10">
                            <h2 class="text-3xl font-bold text-gray-900 mb-3">Создать мероприятие</h2>
                            <p class="text-gray-600 text-lg">Заполните информацию о новом мероприятии</p>
                        </div>
                        
                        <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-8">
                            <!-- Название мероприятия -->
                            <div>
                                <InputLabel for="name" value="Название мероприятия" class="text-gray-900 font-semibold text-lg mb-3" />
                                <TextInput
                                    id="name"
                                    type="text"
                                    class="mt-2 block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 text-lg py-3 px-4"
                                    v-model="form.name"
                                    required
                                    autofocus
                                    placeholder="Введите название мероприятия"
                                />
                                <InputError class="mt-3 text-lg" :message="form.errors.name" />
                            </div>

                            <!-- Описание мероприятия -->
                            <div>
                                <InputLabel for="description" value="Описание" class="text-gray-900 font-semibold text-lg mb-3" />
                                <textarea
                                    id="description"
                                    class="mt-2 block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 text-lg py-3 px-4"
                                    v-model="form.description"
                                    rows="5"
                                    placeholder="Опишите ваше мероприятие..."
                                ></textarea>
                                <InputError class="mt-3 text-lg" :message="form.errors.description" />
                            </div>

                            <!-- Фото мероприятия -->
                            <div>
                                <InputLabel for="photo" value="Фото мероприятия" class="text-gray-900 font-semibold text-lg mb-3" />
                                
                                <div class="mt-4 flex items-start gap-6">
                                    <!-- Превью фотографии -->
                                    <div class="flex-shrink-0">
                                        <div class="relative">
                                            <div v-if="form.photoPreview" class="h-32 w-32 rounded-2xl overflow-hidden border-2 border-white shadow-lg group hover:scale-105 transition-transform duration-300">
                                                <img
                                                    :src="form.photoPreview"
                                                    class="h-full w-full object-cover"
                                                    alt="Превью фото мероприятия"
                                                    loading="lazy"
                                                    width="128"
                                                    height="128"
                                                />
                                            </div>
                                            <div v-else class="h-32 w-32 rounded-2xl bg-gradient-to-r from-blue-100 to-indigo-100 border-2 border-white shadow-lg flex items-center justify-center group hover:scale-105 transition-transform duration-300">
                                                <svg class="h-12 w-12 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Кнопка выбора файла -->
                                    <div class="flex-1">
                                        <input
                                            id="photo"
                                            type="file"
                                            class="hidden"
                                            @change="updatePhotoPreview"
                                            ref="photoInput"
                                            accept="image/*"
                                        />
                                        
                                        <button
                                            type="button"
                                            @click="selectNewPhoto"
                                            class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                        >
                                            <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            Выбрать фото
                                        </button>
                                        
                                        <p class="mt-3 text-gray-600 text-lg">
                                            JPG, PNG или GIF. Максимальный размер 2MB.
                                        </p>
                                        <InputError class="mt-3 text-lg" :message="form.errors.photo" />
                                    </div>
                                </div>
                            </div>

                            <!-- Дата и время начала -->
                            <div>
                                <InputLabel for="start_time" value="Дата и время начала" class="text-gray-900 font-semibold text-lg mb-3" />
                                <TextInput
                                    id="start_time"
                                    type="datetime-local"
                                    class="mt-2 block w-full rounded-xl bg-white border border-gray-300 text-gray-900 focus:border-blue-500 focus:ring-blue-500 text-lg py-3 px-4"
                                    v-model="form.start_time"
                                    required
                                />
                                <InputError class="mt-3 text-lg" :message="form.errors.start_time" />
                            </div>

                            <!-- Продолжительность -->
                            <div>
                                <InputLabel for="duration" value="Продолжительность (в минутах)" class="text-gray-900 font-semibold text-lg mb-3" />
                                <TextInput
                                    id="duration"
                                    type="number"
                                    class="mt-2 block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 text-lg py-3 px-4"
                                    v-model="form.duration"
                                    required
                                    min="1"
                                    placeholder="120"
                                />
                                <InputError class="mt-3 text-lg" :message="form.errors.duration" />
                            </div>

                            <!-- Максимум участников -->
                            <div>
                                <InputLabel for="max_attendees" value="Максимум участников" class="text-gray-900 font-semibold text-lg mb-3" />
                                <TextInput
                                    id="max_attendees"
                                    type="number"
                                    class="mt-2 block w-full rounded-xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:border-blue-500 focus:ring-blue-500 text-lg py-3 px-4"
                                    v-model="form.max_attendees"
                                    required
                                    min="1"
                                    placeholder="50"
                                />
                                <InputError class="mt-3 text-lg" :message="form.errors.max_attendees" />
                            </div>

                            <!-- Кнопки действий -->
                            <div class="flex items-center justify-end gap-6 pt-8 border-t border-gray-200">
                                <Link
                                    :href="route('organizer.events.index')"
                                    class="px-6 py-3 bg-white hover:bg-gray-50 text-gray-700 rounded-xl font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl flex items-center gap-3 border border-gray-200 group"
                                >
                                    <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Отмена
                                </Link>
                                
                                <button
                                    :class="[
                                        'px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white hover:from-blue-700 hover:to-indigo-800 rounded-xl font-bold shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl flex items-center gap-3 group transform hover:-translate-y-1',
                                        { 'opacity-75 cursor-not-allowed': form.processing }
                                    ]"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing" class="flex items-center gap-3">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Создание...
                                    </span>
                                    <span v-else class="flex items-center gap-3">
                                        <svg class="w-5 h-5 transform transition-transform group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Создать мероприятие
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AdminOrganizerLayout>
</template>