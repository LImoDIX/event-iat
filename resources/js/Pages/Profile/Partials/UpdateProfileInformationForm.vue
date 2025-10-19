<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
    updateRoute: {
        type: String,
        default: 'profile.update'
    }
});

const user = usePage().props.auth.user;

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
   name: user.name,
   email: user.email,
   profile_photo_path: user.profile_photo_path,
   profile_photo: null,
   profile_photo_preview: null,
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
   if (!['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(file.type)) {
       alert('Пожалуйста, выберите файл JPG, PNG, GIF или WebP');
       return;
   }
   
   // Сохраняем файл для отправки
   form.profile_photo = file;
   
   // Создание превью
   const reader = new FileReader();
   reader.onload = (e) => {
       form.profile_photo_preview = e.target.result;
   };
   reader.readAsDataURL(file);
};

const clearPhotoSelection = () => {
    if (photoInput.value) {
        photoInput.value.value = null;
    }
    form.profile_photo = null;
    form.profile_photo_preview = null;
};

const updateProfile = () => {
    // Для администраторов и организаторов разрешаем только обновление фото
    if (user.role === 'admin' || user.role === 'organizer') {
        if (form.profile_photo) {
            // Используем FormData для загрузки файла
            form.transform((data) => {
                const formData = new FormData();
                formData.append('profile_photo', data.profile_photo);
                formData.append('_method', 'PATCH');
                return formData;
            }).post(route(props.updateRoute), {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    clearPhotoSelection();
                    window.dispatchEvent(new CustomEvent('profile-photo-updated', {
                        detail: { photoPath: user.profile_photo_path }
                    }));
                },
            });
        }
    } else {
        // Для других пользователей разрешаем обновление всех данных
        if (form.profile_photo) {
            // Используем FormData для загрузки файла
            form.transform((data) => {
                const formData = new FormData();
                formData.append('name', data.name);
                formData.append('email', data.email);
                formData.append('profile_photo', data.profile_photo);
                formData.append('_method', 'PATCH');
                return formData;
            }).post(route(props.updateRoute), {
                forceFormData: true,
                preserveScroll: true,
                onSuccess: () => {
                    clearPhotoSelection();
                    window.dispatchEvent(new CustomEvent('profile-photo-updated', {
                        detail: { photoPath: user.profile_photo_path }
                    }));
                },
            });
        } else {
            // Обычный запрос без файла
            form.patch(route(props.updateRoute), {
                preserveScroll: true,
                onSuccess: () => {
                    clearPhotoSelection();
                },
            });
        }
    }
};
</script>

<template>
    <section class="space-y-8">
        <form
           @submit.prevent="updateProfile"
           class="space-y-8"
           enctype="multipart/form-data"
       >
            <div v-if="user.role !== 'admin' && user.role !== 'organizer'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="name" value="Имя" class="text-gray-700" />

                    <TextInput
                        id="name"
                        type="text"
                        class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        v-model="form.name"
                        required
                        autocomplete="name"
                        :disabled="form.processing"
                    />

                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="email" value="Электронная почта" class="text-gray-700" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-500 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                        v-model="form.email"
                        required
                        autocomplete="username"
                        :disabled="form.processing"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>
            </div>
            <div v-else>
                <p class="text-muted-foreground">
                    Администраторы и организаторы не могут изменять имя и email.
                </p>
            </div>

            <!-- Поле для загрузки фотографии профиля -->
            <div>
                <InputLabel for="profile_photo" value="Фото профиля" class="text-gray-700" />
                
                <div class="mt-4 flex flex-col sm:flex-row items-center gap-8">
                    <!-- Отображение текущей фотографии -->
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img
                               v-if="form.profile_photo_preview"
                               :src="form.profile_photo_preview"
                               class="h-32 w-32 rounded-full object-cover shadow-xl border-2 border-gray-300"
                               alt="Фото профиля"
                               @error="(e) => { e.target.style.display = 'none'; }"
                           />
                           <img
                               v-else-if="form.profile_photo_path"
                               :src="getImageUrl(form.profile_photo_path)"
                               class="h-32 w-32 rounded-full object-cover shadow-xl border-2 border-gray-300"
                               alt="Фото профиля"
                               @error="(e) => { e.target.style.display = 'none'; }"
                           />
                           <div
                               v-else
                               class="h-32 w-32 rounded-full bg-gradient-to-br from-purple-600/20 to-pink-600/20 flex items-center justify-center shadow-xl border-2 border-gray-300"
                           >
                               <span class="text-3xl font-bold text-purple-600">
                                   {{ form.name.charAt(0).toUpperCase() }}
                               </span>
                           </div>
                        </div>
                    </div>
                    
                    <!-- Кнопка для выбора файла -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 mt-4">
                        <input
                            id="profile_photo"
                            type="file"
                            class="hidden"
                            @change="updatePhotoPreview"
                            ref="photoInput"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                        />
                        
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button
                                type="button"
                                @click="selectNewPhoto"
                                class="inline-flex items-center rounded-lg border border-transparent bg-black px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-gray-800 focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg font-semibold"
                                :disabled="form.processing"
                            >
                                Выбрать новое фото
                            </button>
                            
                            <button
                                v-if="form.profile_photo_preview"
                                type="button"
                                @click="clearPhotoSelection"
                                class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition duration-200 ease-in-out hover:bg-gray-100 focus:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 active:bg-gray-200 shadow-md hover:shadow-lg font-semibold"
                                :disabled="form.processing"
                            >
                                Отмена
                            </button>
                        </div>

                        <div class="text-sm text-gray-600 max-w-xs">
                            <p v-if="form.profile_photo_preview" class="text-green-600 font-medium">
                                Новое фото выбрано. Нажмите "Сохранить изменения" для загрузки.
                            </p>
                            <p v-else>
                                Выберите JPG, PNG, GIF или WebP до 2MB
                            </p>
                        </div>
                    </div>
                </div>

                <InputError class="mt-2" :message="form.errors.profile_photo" />
                
            </div>

            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 font-medium"
                    >
                        Профиль обновлен
                    </p>
                </Transition>

                <button
                    type="submit"
                    :class="[
                        'inline-flex items-center rounded-lg border border-transparent bg-black px-6 py-3 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-gray-800 focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg font-semibold',
                        { 'opacity-75 cursor-not-allowed': form.processing }
                    ]"
                    :disabled="form.processing"
                >
                    <span v-if="form.processing" class="mr-2 flex items-center">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    Сохранить изменения
                </button>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-yellow-800">Подтверждение электронной почты</h3>
                        <div class="mt-2 text-sm text-yellow-700">
                            <p>
                                Ваш адрес электронной почты не подтвержден.
                                <Link
                                    :href="route('verification.send')"
                                    method="post"
                                    as="button"
                                    class="font-medium underline hover:text-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded"
                                >
                                    Нажмите здесь, чтобы повторно отправить письмо с подтверждением.
                                </Link>
                            </p>
                        </div>
                        <div
                            v-show="status === 'verification-link-sent'"
                            class="mt-3 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-lg"
                        >
                            Новая ссылка для подтверждения была отправлена на ваш адрес электронной почты.
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</template>