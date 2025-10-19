<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const props = defineProps({
    updateRoute: {
        type: String,
        default: 'password.update'
    }
});

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route(props.updateRoute), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section class="space-y-8">
        <form @submit.prevent="updatePassword" class="space-y-8">
            <!-- Скрытое поле имени пользователя для улучшения доступности -->
            <input
                type="text"
                :value="$page.props.auth.user.email"
                class="sr-only"
                tabindex="-1"
                aria-hidden="true"
                autocomplete="username"
            />
            
            <div class="bg-white border border-gray-300 rounded-xl p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="current_password" value="Текущий пароль" class="text-gray-700" />

                        <TextInput
                            id="current_password"
                            ref="currentPasswordInput"
                            v-model="form.current_password"
                            type="password"
                            class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            autocomplete="current-password"
                        />

                        <InputError
                            :message="form.errors.current_password"
                            class="mt-2"
                        />
                    </div>

                    <div></div>

                    <div>
                        <InputLabel for="password" value="Новый пароль" class="text-gray-700" />

                        <TextInput
                            id="password"
                            ref="passwordInput"
                            v-model="form.password"
                            type="password"
                            class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            autocomplete="new-password"
                        />

                        <InputError :message="form.errors.password" class="mt-2" />
                    </div>

                    <div>
                        <InputLabel
                            for="password_confirmation"
                            value="Подтвердите пароль"
                            class="text-gray-700"
                        />

                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="mt-2 block w-full rounded-lg bg-white border border-gray-300 text-black placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                            autocomplete="new-password"
                        />

                        <InputError
                            :message="form.errors.password_confirmation"
                            class="mt-2"
                            />
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-4">
                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-green-400"
                    >
                        Сохранено.
                    </p>
                </Transition>

                <button
                    type="submit"
                    :class="[
                        'inline-flex items-center rounded-lg border border-transparent bg-black px-4 py-2 text-sm font-medium text-white transition duration-200 ease-in-out hover:bg-gray-800 focus:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 active:bg-gray-900 disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg px-6 py-3 rounded-lg font-semibold transition duration-200',
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
                    Обновить пароль
                </button>
            </div>
        </form>
    </section>
</template>
