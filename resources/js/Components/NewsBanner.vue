<script setup>
import { ref, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    news: Array,
});

const currentNewsIndex = ref(0);
const currentNews = ref(null);

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

// Функция для перехода к следующей новости
const nextNews = () => {
    if (props.news && props.news.length > 0) {
        currentNewsIndex.value = (currentNewsIndex.value + 1) % props.news.length;
        currentNews.value = props.news[currentNewsIndex.value];
    }
};

// Функция для перехода к предыдущей новости
const prevNews = () => {
    if (props.news && props.news.length > 0) {
        currentNewsIndex.value = (currentNewsIndex.value - 1 + props.news.length) % props.news.length;
        currentNews.value = props.news[currentNewsIndex.value];
    }
};

// Инициализация первой новости
onMounted(() => {
    if (props.news && props.news.length > 0) {
        currentNews.value = props.news[0];
        // Автоматическая смена новостей каждые 8 секунд
        setInterval(() => {
            nextNews();
        }, 8000);
    }
});
</script>

<template>
    <div v-if="currentNews" class="relative bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-3xl shadow-2xl overflow-hidden mb-8 border border-white/20">
        <!-- Анимированный градиентный фон -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 via-indigo-600/90 to-purple-700/90"></div>
        
        <!-- Декоративные элементы -->
        <div class="absolute top-0 right-0 w-80 h-80 bg-gradient-to-br from-blue-400/30 to-transparent rounded-full -mt-40 -mr-40 animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-gradient-to-tr from-indigo-400/30 to-transparent rounded-full -mb-32 -ml-32 animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-400/20 to-blue-400/20 rounded-full blur-3xl"></div>
        
        <!-- Блестящие частицы -->
        <div class="absolute top-1/4 left-1/4 w-2 h-2 bg-white/40 rounded-full animate-bounce"></div>
        <div class="absolute top-3/4 right-1/3 w-1 h-1 bg-white/30 rounded-full animate-bounce delay-500"></div>
        <div class="absolute bottom-1/4 left-1/3 w-1.5 h-1.5 bg-white/50 rounded-full animate-bounce delay-700"></div>

        <div class="relative z-10 p-8 lg:p-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 lg:gap-12 items-center">
                <!-- Текстовая часть -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-2xl bg-white/20 backdrop-blur-lg text-white text-base font-semibold shadow-lg border border-white/30 group hover:bg-white/30 transition-all duration-500 hover:scale-105">
                        <div class="w-6 h-6 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center shadow-md">
                            <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <span class="tracking-wide">Свежие новости</span>
                    </div>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight">
                        {{ currentNews.title }}
                    </h2>
                    
                    <p class="text-blue-100 text-xl md:text-2xl leading-relaxed max-w-3xl opacity-90">
                        {{ currentNews.description }}
                    </p>
                    
                    <div class="flex flex-wrap gap-4 pt-4">
                        <Link 
                            :href="`/news/${currentNews.id}`" 
                            class="px-8 py-4 bg-white text-blue-600 font-bold rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-500 hover:scale-105 transform hover:-translate-y-1 flex items-center gap-3 group"
                        >
                            <span>Узнать подробности</span>
                            <svg class="w-5 h-5 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                            </svg>
                        </Link>
                        
                        <button 
                            v-if="news && news.length > 1"
                            @click="nextNews"
                            class="px-6 py-4 bg-white/10 backdrop-blur-lg text-white font-semibold rounded-2xl border border-white/30 shadow-lg hover:shadow-xl transition-all duration-500 hover:scale-105 hover:bg-white/20 flex items-center gap-3 group"
                        >
                            <svg class="w-5 h-5 transform transition-transform group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Следующая новость
                        </button>
                    </div>
                </div>
                
                <!-- Изображение -->
                <div class="flex justify-center lg:justify-end">
                    <div class="relative group">
                        <div
                            v-if="currentNews.photo_path"
                            class="relative w-72 h-72 lg:w-80 lg:h-80 rounded-3xl overflow-hidden shadow-2xl border-4 border-white/40 transform transition-all duration-700 group-hover:scale-105 group-hover:rotate-2"
                        >
                            <img
                                :src="getImageUrl(currentNews.photo_path)"
                                :alt="currentNews.title"
                                class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                                loading="lazy"
                                width="320"
                                height="320"
                                @error="(e) => { e.target.style.display = 'none'; }"
                            >
                            <!-- Градиентная overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-600/20 to-transparent"></div>
                        </div>
                        <div
                            v-else
                            class="w-72 h-72 lg:w-80 lg:h-80 bg-gradient-to-br from-blue-400/90 to-indigo-500/90 rounded-3xl flex items-center justify-center shadow-2xl border-4 border-white/40 transform transition-all duration-500 group-hover:scale-105"
                        >
                            <svg class="w-20 h-20 text-white/80 transform transition-transform duration-500 group-hover:scale-110" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        <!-- Декоративный элемент -->
                        <div class="absolute -bottom-4 -right-4 w-20 h-20 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:-rotate-12 border-2 border-white">
                            <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        
                        <!-- Анимированная рамка -->
                        <div class="absolute inset-0 rounded-3xl border-2 border-white/20 group-hover:border-white/40 transition-all duration-500"></div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Индикаторы прогресса -->
        <div v-if="news && news.length > 1" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex gap-2">
            <div 
                v-for="(item, index) in news" 
                :key="item.id"
                @click="currentNewsIndex = index; currentNews = item"
                class="cursor-pointer transition-all duration-500 group"
            >
                <div 
                    class="h-2 rounded-full transition-all duration-500 group-hover:bg-white/80"
                    :class="[
                        index === currentNewsIndex 
                            ? 'bg-white w-8 shadow-lg' 
                            : 'bg-white/40 w-3 hover:w-4'
                    ]"
                ></div>
            </div>
        </div>
    </div>
</template>