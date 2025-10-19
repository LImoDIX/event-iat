<script setup>
import { ref } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingNavigationDropdown = ref(false);
const page = usePage();
const route = window.route;

const getProfilePhotoUrl = (photoPath) => {
    if (!photoPath) return null;
    
    if (photoPath.startsWith('http')) {
        return photoPath;
    }
    
    if (photoPath.startsWith('storage/')) {
        return `/storage/${photoPath.replace('storage/', '')}`;
    }
    
    return `/storage/${photoPath}`;
};

const getProfileRoute = () => {
    const user = page.props.auth.user;
    if (user.role === 'admin' || user.role === 'organizer') {
        return route('profile.admin-organizer');
    } else if (user.role === 'visitor') {
        return route('profile.visitor');
    } else {
        return route('profile.edit');
    }
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 text-gray-900">
            <nav class="sticky top-0 z-50 w-full bg-white/90 backdrop-blur-lg border-b border-gray-200 shadow-sm">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 items-center justify-between">
                        <!-- Левая часть -->
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

                        <!-- Правая часть - профиль -->
                        <div class="hidden sm:flex items-center gap-4">
                            <Dropdown align="right" width="48" content-classes="bg-white/95 backdrop-blur-lg border border-gray-200 rounded-xl shadow-2xl">
                                <template #trigger>
                                    <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-white text-gray-700 border border-gray-300 shadow-lg transition-all duration-300 hover:shadow-xl hover:border-blue-300 hover:scale-105">
                                        <div class="h-8 w-8 rounded-full flex items-center justify-center overflow-hidden border-2 border-blue-100">
                                            <img
                                                v-if="page.props.auth.user.profile_photo_path"
                                                :src="getProfilePhotoUrl(page.props.auth.user.profile_photo_path)"
                                                class="h-full w-full rounded-full object-cover"
                                                alt="Фото профиля"
                                                loading="lazy"
                                                width="32"
                                                height="32"
                                                @error="(e) => { e.target.style.display = 'none'; }"
                                            />
                                            <div
                                                v-else
                                                class="h-full w-full rounded-full flex items-center justify-center bg-gradient-to-br from-blue-100 to-indigo-100"
                                            >
                                                <span class="text-sm font-semibold text-blue-700">{{ page.props.auth.user.name.charAt(0).toUpperCase() }}</span>
                                            </div>
                                        </div>
                                        <span class="hidden md:inline text-sm font-medium text-gray-700">{{ page.props.auth.user.name }}</span>
                                        <svg class="h-4 w-4 text-gray-500 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <div class="bg-white border border-gray-200 rounded-xl shadow-2xl overflow-hidden">
                                        <div class="p-4 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
                                            <p class="text-sm font-semibold text-gray-900">{{ page.props.auth.user.name }}</p>
                                            <p class="text-xs text-gray-600 mt-1">{{ page.props.auth.user.email }}</p>
                                        </div>
                                        <div class="py-2">
                                            <DropdownLink
                                                :href="getProfileRoute()"
                                                class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-all duration-200"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                </svg>
                                                Профиль
                                            </DropdownLink>
                                            <button
                                                @click="logout"
                                                class="flex items-center gap-3 w-full px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-all duration-200 text-left"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Выйти
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </Dropdown>
                        </div>

                        <!-- Бургер -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center rounded-xl p-2 text-gray-700 hover:bg-blue-50 transition-all duration-300"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile menu -->
                <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">
                    <div class="border-t border-gray-200 bg-white/95 backdrop-blur-lg pb-1 pt-4">
                        <!-- Профиль в мобильном меню -->
                        <div class="border-t border-gray-200 pb-1 pt-4">
                            <div class="px-4 flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 flex items-center justify-center overflow-hidden border-2 border-blue-200">
                                        <img
                                            v-if="page.props.auth.user.profile_photo_path"
                                            :src="getProfilePhotoUrl(page.props.auth.user.profile_photo_path)"
                                            class="h-full w-full rounded-full object-cover"
                                            alt="Фото профиля"
                                            loading="lazy"
                                            width="40"
                                            height="40"
                                            @error="(e) => { e.target.style.display = 'none'; }"
                                        />
                                        <span
                                            v-else
                                            class="text-blue-700 font-semibold"
                                        >
                                            {{ page.props.auth.user.name.charAt(0).toUpperCase() }}
                                        </span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900">{{ page.props.auth.user.name }}</p>
                                        <p class="text-xs text-gray-600">{{ page.props.auth.user.email }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3 space-y-2 px-4">
                                <a
                                    :href="getProfileRoute()"
                                    class="flex items-center gap-3 px-4 py-3 text-base font-medium text-gray-700 rounded-xl bg-blue-50 border border-blue-200 hover:bg-blue-100 transition-all duration-300"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Профиль
                                </a>
                                <button
                                    @click="logout"
                                    class="flex items-center gap-3 w-full px-4 py-3 text-base font-medium text-gray-700 rounded-xl bg-red-50 border border-red-200 hover:bg-red-100 text-left transition-all duration-300"
                                >
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Выйти
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-8 px-8">
                <div class="mx-auto max-w-7xl">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>