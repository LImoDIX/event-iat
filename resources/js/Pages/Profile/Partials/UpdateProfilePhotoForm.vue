<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
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
};
</script>

<template>
    <section class="space-y-8">
        <form
           @submit.prevent="updateProfile"
           class="space-y-8"
           enctype="multipart/form-data"
       >
            <!-- Поле для загрузки фотографии профиля -->
            <div>
                <InputLabel for="profile_photo" value="Фото профиля" class="text-gray-700 font-medium text-lg" />
                
                <div class="mt-6 flex flex-col sm:flex-row items-center gap-8">
                    <!-- Отображение текущей фотографии -->
                    <div class="flex-shrink-0">
                        <div class="relative">
                            <img
                               v-if="form.profile_photo_preview"
                               :src="form.profile_photo_preview"
                               class="h-32 w-32 rounded-full object-cover shadow-xl border-4 border-white"
                               alt="Фото профиля"
                               loading="lazy"
                               width="128"
                               height="128"
                               @error="(e) => { e.target.style.display = 'none'; }"
                           />
                           <img
                               v-else-if="form.profile_photo_path"
                               :src="getImageUrl(form.profile_photo_path)"
                               class="h-32 w-32 rounded-full object-cover shadow-xl border-4 border-white"
                               alt="Фото профиля"
                               loading="lazy"
                               width="128"
                               height="128"
                               @error="(e) => { e.target.style.display = 'none'; }"
                           />
                           <div
                               v-else
                               class="h-32 w-32 rounded-full bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center shadow-xl border-4 border-white"
                           >
                               <span class="text-3xl font-bold text-blue-600">
                                   {{ user.name.charAt(0).toUpperCase() }}
                               </span>
                           </div>
                        </div>
                    </div>
                    
                    <!-- Кнопка для выбора файла -->
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <input
                            id="profile_photo"
                            type="file"
                            class="hidden"
                            @change="updatePhotoPreview"
                            ref="photoInput"
                            accept="image/jpeg,image/png,image/gif,image/webp"
                        />
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <button
                                type="button"
                                @click="selectNewPhoto"
                                class="inline-flex items-center gap-2 rounded-xl border border-transparent bg-blue-600 px-6 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md"
                                :disabled="form.processing"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Выбрать новое фото
                            </button>
                            
                            <button
                                v-if="form.profile_photo_preview"
                                type="button"
                                @click="clearPhotoSelection"
                                class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-6 py-3 text-sm font-medium text-gray-700 transition-all duration-200 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50 shadow-sm hover:shadow-md"
                                :disabled="form.processing"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Отмена
                            </button>
                        </div>

                        <div class="text-sm text-gray-600 max-w-xs">
                            <p v-if="form.profile_photo_preview" class="text-green-600 font-medium bg-green-50 px-3 py-2 rounded-lg">
                                Новое фото выбрано. Нажмите "Сохранить изменения" для загрузки.
                            </p>
                            <p v-else class="bg-gray-50 px-3 py-2 rounded-lg">
                                Выберите JPG, PNG, GIF или WebP до 2MB
                            </p>
                        </div>
                    </div>
                </div>

                <InputError class="mt-3" :message="form.errors.profile_photo" />
                
            </div>

            <div class="flex items-center justify-end gap-4 pt-8 border-t border-gray-200">
                <Transition
                    enter-active-class="transition ease-in-out duration-300"
                    enter-from-class="opacity-0 translate-y-1"
                    enter-to-class="opacity-100 translate-y-0"
                    leave-active-class="transition ease-in-out duration-300"
                    leave-from-class="opacity-100 translate-y-0"
                    leave-to-class="opacity-0 translate-y-1"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-600 font-medium bg-green-50 px-4 py-2 rounded-lg"
                    >
                        ✅ Фото успешно обновлено
                    </p>
                </Transition>

                <button
                    type="submit"
                    :class="[
                        'inline-flex items-center gap-2 rounded-xl border border-transparent bg-blue-600 px-8 py-3 text-sm font-medium text-white transition-all duration-200 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed shadow-sm hover:shadow-md',
                        { 'opacity-75 cursor-not-allowed': form.processing || !form.profile_photo }
                    ]"
                    :disabled="form.processing || !form.profile_photo"
                >
                    <span v-if="form.processing" class="flex items-center">
                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                    <span v-else>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </span>
                    {{ form.processing ? 'Сохранение...' : 'Сохранить изменения' }}
                </button>
            </div>
        </form>
    </section>
</template>