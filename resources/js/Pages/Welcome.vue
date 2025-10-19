<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    laravelVersion: { type: String, required: true },
    phpVersion: { type: String, required: true },
});
</script>

<template>
    <Head title="Добро пожаловать" />

    <div class="min-h-screen flex flex-col bg-gradient-to-br from-slate-50 to-blue-50 text-gray-900 overflow-hidden">
        <!-- Header -->
        <header class="sticky top-0 z-50 w-full bg-white/80 backdrop-blur-lg border-b border-gray-200 shadow-sm">
            <div class="container mx-auto flex h-20 items-center justify-between px-6 lg:px-12">
                <div class="flex items-center gap-3 group cursor-pointer">
                    <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg transform transition-transform group-hover:scale-105">
                        <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="transform transition-transform group-hover:translate-x-1">
                        <h1 class="text-2xl font-bold text-gray-900">ИАТ Мероприятия</h1>
                        <p class="text-sm text-gray-600">Иркутский авиационный техникум</p>
                    </div>
                </div>

                <nav v-if="canLogin" class="flex items-center gap-4">
                    <template v-if="$page.props.auth.user">
                        <Link :href="route('dashboard.' + $page.props.auth.user.role)">
                            <button class="px-6 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white font-semibold shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl hover:from-blue-700 hover:to-indigo-800">
                                Личный кабинет
                            </button>
                        </Link>
                    </template>
                    <template v-else>
                        <Link :href="route('login')">
                            <button class="px-6 py-3 font-semibold rounded-xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-lg transition-all duration-300 hover:scale-105 hover:shadow-xl">
                                Войти
                            </button>
                        </Link>
                    </template>
                </nav>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="container mx-auto px-6 py-20 lg:py-32 text-center relative">
            <!-- Decorative elements -->
            <div class="absolute top-20 left-10 w-6 h-6 bg-blue-200 rounded-full animate-pulse"></div>
            <div class="absolute bottom-40 right-16 w-4 h-4 bg-indigo-200 rounded-full animate-pulse delay-500"></div>
            <div class="absolute top-40 right-20 w-8 h-8 bg-blue-100 rounded-full animate-pulse delay-700"></div>
            
            <div class="mx-auto max-w-4xl">
                <div class="inline-flex items-center gap-3 px-6 py-3 rounded-2xl bg-white/80 backdrop-blur-sm border border-blue-200 shadow-lg mb-10 transform transition-all duration-500 hover:scale-105 hover:shadow-xl">
                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-gradient-to-r from-blue-600 to-indigo-700">
                        <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C13.1 2 14 2.9 14 4C14 5.1 13.1 6 12 6C10.9 6 10 5.1 10 4C10 2.9 10.9 2 12 2ZM21 9V7L15 5.5V7H9V5.5L3 7V9L9 10.5V12L5 13V15L9 13.5V15H15V13.5L21 15V13L15 11.5V10.5L21 9Z"/>
                        </svg>
                    </div>
                    <span class="text-base font-semibold text-gray-700">Все мероприятия техникума в одном месте</span>
                </div>
                <h2 class="text-3xl sm:text-5xl lg:text-7xl font-bold tracking-tight mb-8 text-balance text-gray-900">
                    Мероприятия
                    <span class="bg-gradient-to-r from-blue-600 to-indigo-700 bg-clip-text text-transparent">для студентов</span>
                </h2>
                <p class="text-xl sm:text-2xl text-gray-600 mb-12 max-w-3xl mx-auto text-pretty leading-relaxed">
                    Узнавайте о событиях первыми, записывайтесь и отслеживайте свои посещения. 
                    Всё для активной студенческой жизни в Иркутском авиационном техникуме.
                </p>

                <div class="flex gap-6 flex-wrap justify-center">
                    <Link :href="route('login')">
                        <button class="px-6 py-3 sm:px-10 sm:py-5 text-lg font-bold rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-700 text-white shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1">
                            Войти на сайт
                        </button>
                    </Link>
                    <a href="#features" class="px-6 py-3 sm:px-10 sm:py-5 text-lg font-bold rounded-2xl bg-white text-gray-900 border border-gray-300 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1 hover:border-blue-300">
                        Узнать больше
                    </a>
                </div>
            </div>
        </section>

        <!-- Student Benefits -->
        <section id="features" class="container mx-auto px-6 lg:px-20 py-24 relative">
            <div class="text-center mb-20">
                <h3 class="text-2xl sm:text-4xl font-bold mb-6 text-gray-900">Что можно делать на сайте</h3>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Полезные функции для участия в жизни техникума
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="p-6 sm:p-10 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="mb-6 transform transition-transform group-hover:scale-110">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold mb-4 text-gray-900">Смотреть расписание</h4>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Все предстоящие лекции, семинары, конференции и культурные события
                    </p>
                </div>

                <div class="p-6 sm:p-10 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="mb-6 transform transition-transform group-hover:scale-110">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold mb-4 text-gray-900">Записываться на события</h4>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Регистрируйтесь на интересующие мероприятия в несколько кликов
                    </p>
                </div>

                <div class="p-6 sm:p-10 rounded-3xl bg-white border border-gray-200 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="mb-6 transform transition-transform group-hover:scale-110">
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl flex items-center justify-center shadow-lg">
                            <svg class="h-7 w-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </div>
                    <h4 class="text-2xl font-bold mb-4 text-gray-900">Видеть свою активность</h4>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        Следите за тем, какие мероприятия вы уже посетили в этом семестре
                    </p>
                </div>
            </div>
        </section>

        <!-- How it Works -->
        <section class="bg-white border-y border-gray-200 py-24">
            <div class="container mx-auto px-6 lg:px-20">
                <h3 class="text-2xl sm:text-4xl font-bold text-center mb-20 text-gray-900">Как это работает</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-12 max-w-6xl mx-auto">
                    <div class="text-center space-y-6 group">
                        <div class="relative">
                            <div class="w-20 h-20 flex items-center justify-center mx-auto bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl text-2xl font-bold shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                1
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900">Войдите на сайт</h4>
                        <p class="text-gray-600 leading-relaxed text-lg">Используйте логин и пароль от ИАТ</p>
                    </div>

                    <div class="text-center space-y-6 group">
                        <div class="relative">
                            <div class="w-20 h-20 flex items-center justify-center mx-auto bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl text-2xl font-bold shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                2
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900">Найдите мероприятия</h4>
                        <p class="text-gray-600 leading-relaxed text-lg">Изучите афишу событий техникума</p>
                    </div>

                    <div class="text-center space-y-6 group">
                        <div class="relative">
                            <div class="w-20 h-20 flex items-center justify-center mx-auto bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl text-2xl font-bold shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                3
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900">Запишитесь</h4>
                        <p class="text-gray-600 leading-relaxed text-lg">Выберите подходящие события и зарегистрируйтесь</p>
                    </div>

                    <div class="text-center space-y-6 group">
                        <div class="relative">
                            <div class="w-20 h-20 flex items-center justify-center mx-auto bg-gradient-to-br from-blue-600 to-indigo-700 text-white rounded-2xl text-2xl font-bold shadow-2xl transform transition-all duration-500 group-hover:scale-110 group-hover:rotate-3">
                                4
                            </div>
                        </div>
                        <h4 class="text-2xl font-bold text-gray-900">Участвуйте</h4>
                        <p class="text-gray-600 leading-relaxed text-lg">Приходите на мероприятия и отмечайтесь</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- For Students Section -->
        <section class="container mx-auto px-6 lg:px-20 py-24">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16">
                    <h3 class="text-2xl sm:text-4xl font-bold text-gray-900 mb-6">Для студентов ИАТ</h3>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        Наш сайт помогает быть в курсе всех событий техникума - от учебных лекций 
                        до культурных мероприятий и спортивных соревнований.
                    </p>
                </div>
        
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-3xl p-6 sm:p-8 text-center text-white shadow-2xl">
                    <div class="max-w-2xl mx-auto">
                        <h4 class="text-3xl font-bold mb-4">Присоединяйтесь к сообществу</h4>
                        <p class="text-xl mb-8 opacity-90">
                            Станьте частью активной студенческой жизни Иркутского авиационного техникума
                        </p>
                        <Link :href="route('login')">
                            <button class="px-4 py-2 sm:px-8 sm:py-4 font-bold rounded-2xl bg-white text-blue-600 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl transform hover:-translate-y-1">
                                Начать участвовать
                            </button>
                        </Link>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="container mx-auto px-6 lg:px-20 py-16">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
                    <div>
                        <div class="flex items-center gap-4 mb-6 group cursor-pointer">
                            <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-700 shadow-lg transform transition-transform group-hover:scale-105">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="transform transition-transform group-hover:translate-x-1">
                                <h1 class="text-2xl font-bold text-gray-900">ИАТ Мероприятия</h1>
                                <p class="text-sm text-gray-600">Иркутский авиационный техникум</p>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed">
                            Сайт мероприятий для студентов и преподавателей Иркутского авиационного техникума.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl mb-6 text-gray-900">Контакты</h4>
                        <div class="space-y-3 text-gray-600">
                            <p class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2v16z"/>
                                </svg>
                                Иркутский авиационный техникум
                            </p>
                            <p class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                г. Иркутск, ул. Ленина, 1
                            </p>
                            <p class="flex items-center gap-3">
                                <svg class="h-5 w-5 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                +7 (3952) 123-456
                            </p>
                        </div>
                    </div>
                    <div>
                        <h4 class="font-bold text-xl mb-6 text-gray-900">Полезные ссылки</h4>
                        <div class="space-y-3">
                            <a href="https://irkat.ru/" class="text-gray-600 hover:text-blue-600 transition-all duration-300 hover:translate-x-2 flex items-center gap-2 group">
                                <div class="w-2 h-2 bg-blue-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                Официальный сайт ИАТ
                            </a>
                            <Link href="/support" class="text-gray-600 hover:text-blue-600 transition-all duration-300 hover:translate-x-2 flex items-center gap-2 group">
                                <div class="w-2 h-2 bg-blue-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                Помощь и поддержка
                            </Link>
                            <Link href="/faq" class="text-gray-600 hover:text-blue-600 transition-all duration-300 hover:translate-x-2 flex items-center gap-2 group">
                                <div class="w-2 h-2 bg-blue-600 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                Частые вопросы
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="pt-8 border-t border-gray-200">
                    <div class="flex flex-col lg:flex-row justify-between items-center gap-6">
                        <p class="text-gray-600">© 2025 Иркутский авиационный техникум.</p>
                        <div class="flex gap-8">
                            <Link href="/privacy" class="text-gray-600 hover:text-blue-600 transition-all duration-300 hover:scale-105">
                                Политика конфиденциальности
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>